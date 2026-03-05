<div class="modal fade" id="modal-verify-search" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header md-head-dark border-bottom-0 pb-3" style="box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); position: relative;">
                <h5 class="modal-title fw-bold text-uppercase w-100 text-center" id="staticBackdropLabel" style="letter-spacing: 1px; font-size: 1.1rem;">
                    Xác Thực Thông Tin
                </h5>
                <button type="button" class="btn-close btn-close-white opacity-75 position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close" style="top: 50%; transform: translateY(-50%); filter: invert(1) grayscale(100%) brightness(200%);"></button>
            </div>
            <div class="modal-body px-4 pt-4 pb-3" style="background-color: #fcf9f2;">
                <form id="form-verify" action="{{ route('client.quyy.check-detail') }}" method="POST">
                    
                    <div class="alert text-center mb-4 py-2" style="background-color: #fef3cd; color: #925001; border: 1px solid #ffd666; border-radius: 8px; font-size: 0.95rem;" role="alert">
                        <span>Vui lòng nhập <strong class="text-danger">Số điện thoại</strong> để xem phái Quy Y</span>
                    </div>

                    <div class="mb-3">
                        <label for="m_name" class="form-label fw-bold mb-1" style="color: #6f200e; font-size: 0.9rem;">Họ và Tên:</label>
                        <input type="hidden" name="uid" id="m_uid">
                        <input type="text" class="form-control bg-white fw-medium border" id="m_name" value="" readonly style="color: #495057; border-color: #e0d5c1; border-radius: 8px;">
                    </div>

                    <div class="mb-3">
                        <label for="m_nickname" class="form-label fw-bold mb-1" style="color: #6f200e; font-size: 0.9rem;">Pháp Danh:</label>
                        <input type="text" class="form-control bg-white fw-medium border" id="m_nickname" value="" readonly style="color: #495057; border-color: #e0d5c1; border-radius: 8px;">
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label fw-bold mb-1" style="color: #6f200e; font-size: 0.9rem;">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control py-2 shadow-sm" id="m_phone" placeholder="Nhập số điện thoại của bạn..." required style="border-color: #e0d5c1; border-radius: 8px;">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 d-flex justify-content-center pt-0 pb-4 px-4" style="background-color: #fcf9f2;">
                <button type="button" class="btn btn-light px-4 py-2 fw-semibold" data-bs-dismiss="modal" style="border: 1px solid #ddd; background-color: #fff; color: #555; border-radius: 8px; min-width: 120px;">
                    Đóng
                </button>
                <button type="button" class="btn px-4 py-2 fw-semibold" onClick="verifyPhone()" style="background: linear-gradient(#d83c17, #9a2b11); color: #fff; border: none; border-radius: 8px; box-shadow: 0 4px 6px rgba(154, 43, 17, 0.3); min-width: 120px;">
                    Xác Nhận
                </button>
            </div>
        </div>
    </div>
</div>