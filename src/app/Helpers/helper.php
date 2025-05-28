<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

if (!function_exists('load_php_files')) {
    /**
     *
     * @param string $directory
     * @return void
     */
    function load_php_files(string $directory)
    {
        try {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator((string)$directory));
            $file_array = [];
            while ($iterator->valid()) {
                if (!$iterator->isDot() && $iterator->isFile() && $iterator->isReadable() && 'php' === $iterator->current()->getExtension()) {
                    $file_array[] = $iterator->key();
                }

                $iterator->next();
            }

            rsort($file_array);

            foreach ($file_array as $file) {
                require_once $file;
            }
        } catch (Exception $exception) {
            report($exception);
        }
    }

    if (!function_exists('is_json')) {
        /**
         *
         * @param string $string
         * @return string
         */
        function is_json($string)
        {
            return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        }
    }

    if (!function_exists('create_uid')) {
        function create_uid($id)
        {
            $uid = format_uid($id+1);
            return config('conts.uid_str').$uid;
        }
    }

    if (!function_exists('format_uid')) {
        function format_uid($number, $length = 5)
        {
            return str_pad($number, $length, '0', STR_PAD_LEFT);
        }
    }

    if (!function_exists('format_phone')) {
        function format_phone($phone)
        {
             // Xóa tất cả ký tự không phải số
            $phone = preg_replace('/\D/', '', $phone);

            // Lấy các phần cần hiển thị
            $part1 = substr($phone, 0, 3);  // 3 số đầu
            $part3 = substr($phone, 7);     // 3 số cuối

            return "{$part1} xxxx {$part3}";
        }
    }


    if (!function_exists('formatHiddenEmail')) {
        function formatHiddenEmail($email)
        {
            // Kiểm tra xem có phải email hợp lệ không
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }

            // Tách phần username và domain
            list($username, $domain) = explode('@', $email);

            // Xử lý phần username
            $length = strlen($username);
            if ($length <= 2) {
                $hidden = str_repeat('*', $length); // ẩn toàn bộ nếu quá ngắn
            } else {
                $visible = substr($username, 0, 2); // giữ lại 2 ký tự đầu
                $last = substr($username, $length - 1); // giữ lại 2 ký tự cuối
                $hidden = $visible . str_repeat('*', $length - 3) . $last;
            }

            return $hidden . '@' . $domain;
        }
    }

    if (!function_exists('check_is_admin')) {
        function check_is_admin(){
            $auth = Auth::guard('admin');
            $role = $auth->user()->roles[0];
            $roleConfig = config('conts.roles');
            if(!in_array($role, array_values($roleConfig))){
                return false;
            }
            return true;
        }
    }
}