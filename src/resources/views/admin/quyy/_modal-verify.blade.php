<!-- Modal -->
<div class="modal fade" id="modal-verify" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Xác Thực Thông Tin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-verify" action="{{ route('admin.quyy.verify') }}" >
                    <div class="form-group">
                        <label for="name">Đặt Pháp Danh:</label>
                        <input type="hidden" name="id" id="m_id">
                        <input type="text" class="form-control" id="m_nick_name" placeholder="Nhập Pháp Danh..." required>
                        <button type="button" class="mt-2 btn-suguest-nickname btn btn-warning" data-route-suggest="{{ route('admin.quyy.gererate') }}"><i class="bi bi-robot"></i> AI Gợi ý</button>
                        <div id="loader" class="loader"></div>
                        <div class="m-list-nickname"></div>
                    </div>
                    <div class="form-group">
                        <label for="name">Họ và Tên:</label>
                        <input type="text" class="form-control" id="m_fullname" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Giới Tính:</label>
                        <span id="m_gender"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Năm sinh:</label>
                        <span id="m_birthday"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Địa Chỉ: </label>
                        <span id="m_address"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Số điện thoại: </label>
                        <span id="m_phone"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Email: </label>
                        <span id="m_email"></span>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onClick="verify()">Xác Nhận</button>
            </div>
        </div>
    </div>
</div>