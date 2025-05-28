<div class="modal fade" id="modal-verify-search" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header md-head-dark">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Xác Thực Thông Tin</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-verify" action="{{ route('client.quyy.check-detail') }}" method="POST">
                    <i class="text-danger">* Vui Lòng Nhập Số Điện Thoại để xem phái Quy Y</i>
                    <div class="form-group">
                        <label for="name">Họ và Tên:</label>
                        <input type="hidden" name="uid" id="m_uid">
                        <input type="text" class="form-control" id="m_name" value="" readonly>
                    </div>

                    <div class="form-group">
                        <label for="m_nickname">Pháp Danh:</label>
                        <input type="text" class="form-control" id="m_nickname" value="" readonly>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại: </label>
                        <input type="text" name="phone" class="form-control" id="m_phone" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Đóng</button>
                <button type="button" class="btn btn-primary" onClick="verifyPhone()">Xác Nhận</button>
            </div>
        </div>
    </div>
</div>