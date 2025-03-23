<!-- Modal -->
<div class="modal fade" id="modal-confirm" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="staticBackdropLabel">{{@$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-confirm" action="{{$action}}" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>{{$des}}</h6>
                    <input type="hidden" name="id" id="m_cf_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmDelete()">Xác Nhận</button>
                </div>
            <form>
        </div>
    </div>
</div>