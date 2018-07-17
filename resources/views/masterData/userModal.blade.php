<div class="modal fade" id="modal-default">
    <div class="modal-dialog direct-chat direct-chat-warning" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="box-header">
                    <h3 id="chat_title" class="box-title">Register New User</h3>

                    <div class="box-tools pull-right">

                        <button type="button" class="btn btn-box-tool" onclick="document.getElementById('msgFrame').contentWindow.location.reload();"><i class="fa fa-refresh"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-dismiss="modal"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <iframe  id="frameUser" src="{{ url('/users/register') }}" height="400px" width="100%" frameBorder="0"></iframe>
                </div>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>