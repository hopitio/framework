<style>
    .modal-user-picker .modal-content{padding: 0;}
</style>

<!-- Modal -->
<div class="modal fade modal-user-picker" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Chọn người sử dụng</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="presentation" class="active"><a href="#user-picker1" aria-controls="home" role="tab" data-toggle="tab">Tìm theo đơn vị</a></li>
                    <li role="presentation"><a href="#user-picker2" aria-controls="profile" role="tab" data-toggle="tab">Tìm theo nhóm</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="user-picker1">
                        
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="user-picker2">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
                <button type="button" class="btn btn-primary">Chọn</button>
            </div>
        </div>
    </div>
</div>