<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Import\ImportRequest;
use Illuminate\Http\Request;
use App\Services\TemporaryUserService\ListTemporaryUserServicesService;
use App\Services\UserService\CreateUserServiceService;
use App\Services\UserService\ListUserServicesService;
use App\Services\UserService\ImportUserServiceService;
use App\Services\Import\ImportUserService;
use App\Services\UserService\UpdateUserServiceService;
use Illuminate\Support\Facades\Hash;
use Exception;
use DB;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Traits\ConvertHeaderTrait;
use App\Traits\QRcodeGenerateTrait;
use App\Http\Requests\CreateUserFromAdminRequest;
use Carbon\Carbon;

class QuyYController extends Controller
{
    use ConvertHeaderTrait, QRcodeGenerateTrait;
    public function index(
        ListUserServicesService $listUserServicesService,
        Request $request,
    ) {
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Danh Sách Quy Y', 'url' => null]
        ];
        $search = $request->get('search');
        $search_year = $request->get('year');
        $no_nickname = $request->get('no_nickname');

        $conditions = [
            // 'active' => 1,
            'search' => $search,
            'year' => $search_year,
            'no_nickname' => $no_nickname,
            'orders' => [
                'id' => 'desc',
            ],
        ];
        // dd($conditions);
        $lists = $listUserServicesService->paginate($conditions)->appends(['search' => $search, 'year' => $search_year, 'no_nickname' => $no_nickname]);
        $years = DB::table('users')->select(DB::raw('YEAR(date_registered) as year'))->groupBy('year')->get();

        return view('admin.quyy.index', compact('breadcrumbs', 'lists', 'search', 'search_year', 'years', 'no_nickname'));
    }

    public function list(
        ListTemporaryUserServicesService $listTemporaryUserServicesService,
        Request $request,
    ) {
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Danh Sách Quy Y', 'url' => null]
        ];
        $conditions = [
            "raw_select_columns" => "temporary_users.*, users.nickname",
            'orders' => [
                'id' => 'desc',
            ],
            'join_tables' => [
                [
                    "type" => "LEFT",
                    "table" => "users",
                    "second" => "users.id",
                    "first" => "temporary_users.temporary_user_id"
                ]

            ],
            'approved' => 0,
        ];
        $lists = $listTemporaryUserServicesService->paginate($conditions);
        return view('admin.quyy.list', compact('lists', 'breadcrumbs'));
    }
    public function create()
    {
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Thêm Phật Tử', 'url' => null]
        ];
        return view('admin.quyy.create', ['breadcrumbs' => $breadcrumbs]);
    }

    public function import()
    {
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Nhập Hàng Loạt', 'url' => null]
        ];
        return view('admin.quyy.import', ['breadcrumbs' => $breadcrumbs]);
    }

    public function postImport(
        ImportRequest $request,
        ImportUserServiceService $importUserService,
    ) {
        $validated = $request->validated();
        try {
            $file = $request->file('file');
            $path = $file->getRealPath();

            $handle = fopen($path, 'r');

            // Bỏ qua các dòng trống đầu file nếu có
            do {
                $header = fgetcsv($handle, 0, ',', '"');
            } while ($header && count(array_filter($header)) === 0);

            if (!$header || count(array_filter($header)) < 2) {
                return back()->withErrors('File CSV không có tiêu đề hợp lệ.');
            }

            if (empty($header[0])) {
                array_shift($header);
            }

            $header = $this->convertToSnakeHeaders($header);

            $insertData = [];
            $lineNumber = 1;

            while (($row = fgetcsv($handle, 0, ',', '"')) !== false) {
                $lineNumber++;
                if (count($row) === 1) {
                    $row = str_getcsv($row[0], ',', '"');
                }

                if (empty($row[0])) {
                    array_shift($row);
                }

                // Bỏ qua dòng trống
                if (count(array_filter($row)) === 0) {
                    continue;
                }

                // Kiểm tra số cột
                if (count($header) !== count($row)) {
                    Log::warning("Lỗi dòng $lineNumber: số cột không khớp", [
                        'header' => $header,
                        'row' => $row
                    ]);
                    continue;
                }

                $data = array_combine($header, $row);

                if ($data && !empty($data['ten'])) {

                    $arr_address = $data['dia_chi'] ? explode(',', $data['dia_chi']) : [];
                    $data['country'] = $arr_address[3] ?? null;
                    $data['city'] = $arr_address[2] ?? null;
                    $data['state'] = $arr_address[1] ?? null;
                    $data['address'] = $arr_address[0] ?? null;
                    $uid = Str::uuid()->toString();
                    $phone = $data['sdt'] ? preg_replace('/[^0-9]/', '', $data['sdt']) : null;
                    $uid_code = $data['ma_so_phai'] ?? null;

                    if ($uid_code) {
                        $url = route('client.quyy.short_detail', ['uid' => $uid]);
                    } else {
                        // Fallback in case uid_code is somehow null
                        $url = route('client.quyy.detail', ['uid' => $uid]);
                    }
                    $qr_code = $this->createQR($url);

                    $insertData[] = [
                        'uid_code' => $data['ma_so_phai'] ?? null,
                        'uid' => $uid,
                        'email' => $data['email'] ?? null,
                        'name' => $data['ten'] ?? null,
                        'nickname' => $data['phap_danh'] ?? null,
                        'country' => $data['country'] ?? null,
                        'city' => $data['city'] ?? null,
                        'state' => $data['state'] ?? null,
                        'address' => $data['address'] ?? null,
                        'phone' => $phone,
                        'birth_date' => !empty($data['nam_sinh']) ? formatBirthDate($data['nam_sinh']) : null,
                        'date_registered' => Carbon::createFromFormat('d/m/Y', $data['ngay_quy_y'])->format('Y-m-d'),
                        'is_active' => 1,
                        'gender' => $data['gioi_tinh'] == 'Nam' ? 'male' : 'female' ?? null,
                        'qr_code' => $qr_code ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'password' => Hash::make($phone),
                    ];
                }
            }

            try {
                DB::BeginTransaction();

                if (!empty($insertData)) {
                    $chunk = array_chunk($insertData, 50);
                    foreach ($chunk as $item) {
                        $importUserService->import($item);
                    }
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }

            return redirect()->route('admin.quyy.index')->with('success', 'Nhập dữ liệu thành công');

        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }

    public function verify(
        Request $request,
        ListUserServicesService $listUserServicesService,
        ListTemporaryUserServicesService $listTemporaryUserServicesService,
        CreateUserServiceService $createUserServiceService,
        $id
    ) {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ID'
                ], 400);
            }
            $data = $request->all();
            $conditions_check = [
                'nick_name' => $data['nick_name']
            ];
            $check_nick_name = $listUserServicesService->list($conditions_check);

            if (!$check_nick_name->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pháp Danh đã tồn tại, vui lòng đặt tên khác!'
                ], 200);
            }

            $data_temp = $listTemporaryUserServicesService->find($id);

            if (is_null($data_temp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ID'
                ], 400);
            }

            $last_user = $listUserServicesService->getLastest();
            $uid = 'CPL_00001';
            if (!is_null($last_user)) {
                $uid = create_uid($last_user->id);
            }

            $request->merge([
                'nick_name' => $data['nick_name'],
                'uid' => $uid,
            ]);

            $user = $createUserServiceService->updateOrCreate($data_temp);

            $data_temp->update(['approved' => 1, 'temporary_user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(
        Request $request,
        ListTemporaryUserServicesService $listTemporaryUserServicesService,
        $id
    ) {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ID'
                ], 400);
            }
            $data = $listTemporaryUserServicesService->find($id);
            $data->delete();
            return redirect()->route('admin.quyy.list');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyUser(
        Request $request,
        ListUserServicesService $listUserServicesService,
        $uid
    ) {
        try {
            $conditions = [
                'uid' => $uid,
            ];

            $data = $listUserServicesService->list($conditions)->first();
            if (empty($data)) {
                return abort(404);
            }
            $data->delete();
            return redirect()->route('admin.quyy.index');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function gererateName(
        Request $request,
        ListTemporaryUserServicesService $listTemporaryUserServicesService,
    ) {
        try {
            $gender = null;
            $first_name_start = "Tâm Viên, Tuệ Quang, Tâm Như, Tuệ Minh";
            $real_name = $request->full_name;
            $api_key = config('conts.GEMINI_API_KEY');

            if ($request->has('gender') && $request->get('gender') == 'Nam') {
                $gender = 'Nam';
                $first_name_start = "Tuệ Quang, Tuệ Minh, Tuệ Đức";
            }
            if ($request->has('gender') && $request->get('gender') == 'Nữ') {
                $gender = 'Nữ';
                $first_name_start = "Tâm Viên, Tâm Như, Tâm Thanh";
            }
            $prompt = 'Hãy liệt kê 10 tên pháp danh gồm 3 từ, mỗi tên mang ý nghĩa thanh tịnh, trí tuệ, từ bi. Tất cả tên phải bắt đầu bằng các từ như: ' . $first_name_start;
            if ($gender != null) {
                $prompt .= "Người cần đặt pháp danh có giới tính ." . $gender;
            }

            if (!empty($real_name)) {
                $prompt .= "Có tên thật là: " . $real_name;
            }

            $prompt .= 'Chỉ liệt kê tên, không tiêu đề, không giải thích, không đánh số. Mỗi tên cách nhau bởi dấu ,';
            $prompt .= ' Hãy sáng tạo theo cảm hứng ngẫu nhiên, nhưng phải kết quả tên không vượt quá 3 từ. Thời điểm hiện tại là: ' . now()->toDateTimeString();

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$api_key}", [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => "{$prompt}"
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 1.0,
                            'topK' => 40,
                            'topP' => 0.95,
                        ]
                    ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()['candidates'][0]['content']['parts'][0]['text']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy dữ liệu từ API'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detail(
        Request $request,
        ListUserServicesService $listUserServicesService,
        $uid
    ) {
        $breadcrumbs = [
            ['title' => 'Danh Sách Phật tử', 'url' => route('admin.quyy.index')],
            ['title' => $uid, 'url' => null]
        ];

        $conditions = [
            'uid' => $uid,
        ];
        $data = $listUserServicesService->list($conditions)->first();
        return view('admin.quyy.detail', ['data' => $data, 'breadcrumbs' => $breadcrumbs]);
    }

    public function update(
        Request $request,
        ListUserServicesService $listUserServicesService,
        UpdateUserServiceService $updateUserServiceService,
        $uid
    ) {
        try {
            $conditions = [
                'uid' => $uid,
            ];

            $data = $listUserServicesService->list($conditions)->first();
            if (empty($data)) {
                return abort(404);
            }

            $qr_code = $data->qr_code;
            if (empty($qr_code)) {
                $url = route('client.quyy.short_detail', ['uid' => $data->uid]);
                $qr_code = $this->createQR($url);
            }

            $request->merge([
                'uid' => $uid,
                'country' => $request->province,
                'city' => $request->district,
                'state' => $request->ward,
                'date_registered' => $request->date_registered ? Carbon::parse($request->date_registered)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                'qr_code' => $qr_code,
            ]);

            $updateUserServiceService->updateByUid($request->all());
            return redirect()->route('admin.quyy.index');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(
        CreateUserFromAdminRequest $request,
        CreateUserServiceService $createUserServiceService,
        ListUserServicesService $listUserServicesService
    ) {
        try {
            $data = $request->all();

            $conditions_check = [
                'nick_name' => $data['nickname']
            ];
            if (isset($data['nick_name']) && $data['nick_name'] != '') {
                $check_nick_name = $listUserServicesService->list($conditions_check);

                if (!$check_nick_name->isEmpty()) {
                    return redirect()->back()
                        ->withErrors(['nickname' => 'Pháp Danh đã tồn tại, vui lòng đặt tên khác!'])
                        ->withInput();
                }
            }

            $last_user = $listUserServicesService->getLastest();
            $uid_code = 'CPL_00001';
            if (!is_null($last_user)) {
                $uid_code = create_uid($last_user->uid_code);
            }

            $uid = Str::uuid()->toString();
            $phone = '';
            if (isset($request->phone_number)) {
                $phone = $request->phone_number ? preg_replace('/[^0-9]/', '', $request->phone_number) : null;
            }
            $url = route('client.quyy.short_detail', ['uid' => $uid]);
            $qr_code = $this->createQR($url);

            $request->merge([
                'nick_name' => $data['nickname'],
                'uid_code' => $uid_code,
                'uid' => $uid,
                'qr_code' => $qr_code
            ]);

            $createUserServiceService->create($request->all());
            return redirect()->route('admin.quyy.index');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
