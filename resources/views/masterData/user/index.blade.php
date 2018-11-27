@extends('layouts.app')

@section('content')


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>User Master Data</a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            {{-- Table --}}
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="has-feedback">
                                    <input id="search-text" type="text" class="form-control input-sm" placeholder="Search Merchandiser">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="has-feedback">
                                    <button id="btn-show-all" class="btn btn-primary btn-sm pull-right">Show All</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div-table-users" class="table-responsive mailbox-messages"></div>
                    </div>
                    <div id="page-nav" class="box-footer no-padding"></div>
                    <div id="loading-users"></div>
                </div>
            </div>

            {{-- Details Box --}}
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">User Details</h3>
                    </div>

                    <div class="box-body">
                        <form id="form-user">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="image">
                                            <img class="img-circle" id="img-user-src" src="{{ asset('storage/avatars/avatar.png') }}"
                                                 alt="{{ asset('storage/avatars/avatar.png') }}"
                                                 height="100px" width="100px"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn btn-default btn-sm btn-file">
                                                <span>Choose Image</span>
                                                <input id="img-user" name="img_user" type="file">
                                            </span>
                                            <span class="fileinput-filename"></span><span class="fileinput-new"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="text-muted">Account Type</label>
                                        {!! Form::select('account_type', $accountTypes, null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'account-type']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label class="text-muted">Account Status</label>
                                        {!! Form::select('account_status', ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'], null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'account-status']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label class="text-muted">Role</label>
                                        {!! Form::select('role', $roles, null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'role']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Last Name</label>
                                        <input id="last-name" name="last_name" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">First Name</label>
                                        <input id="first-name" name="first_name" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-muted">Gender</label>
                                        {!! Form::select('gender', ['Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'gender']) !!}
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="text-muted">Contact #</label>
                                        <input id="contact-number" name="contact_number" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Address</label>
                                        <textarea id="address" name="address" class="form-control" maxlength="255" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Birthday</label>
                                        <input id="birthday" name="birthday" class="form-control" type="date">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Agency</label>
                                        {!! Form::select('agency', $agencies, null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'agency']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Email</label>
                                        <input id="email" name="email" type="email" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Username</label>
                                        <input id="username" name="username" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Password</label>
                                        <input name="password" type="password" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id="response-details" class="col-md-12">
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button id="btn-add" class="btn btn-primary btn-sm">Add User</button>
                                <button id="btn-update" class="btn btn-primary btn-sm" style="display:none">Update User</button>
                                <button id="btn-cancel" class="btn btn-warning btn-sm pull-right" style="display: none">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <div id="loading-details"></div>
                </div>

            </div>

        </div>

    </section>

@endsection

@section('script')
    <script>

        let timeoutId = 0;
        let searchText;
        let paginate = '20';
        let users;
        let totalUser = 0;
        let userUrl = '/users-all';
        let userSaveUrl = '/users/save';
        let userUpdateUrl = '/users/update';
        let selectedId;

        //searching
        $('#search-text').keyup(function () {
            clearTimeout(timeoutId);
            searchText = $('#search-text').val();
            timeoutId = setTimeout(function() {
                getUsers(userUrl);
            }, 750)
        });

        //show all
        $('#btn-show-all').click(function () {
            paginate = totalUser;
            getUsers(userUrl);
        });

        //fetch users
        function getUsers(url){
            showLoading('loading-users', true);
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                contentType: 'application/json',
                data: {
                    search: searchText,
                    paginate: paginate
                },
                success: function(data){
                    /*--------------------------- table content ---------------------------------*/
                    users = data.data;
                    let tbody = '';

                    totalUser = data.total;

                    //body
                    for (let user of users){
                        //status
                        let status = user.account_status;
                        if(status == 'ACTIVE'){
                            status = '<small class="label label-success">Active</small>';
                        }
                        else{
                            status = '<small class="label label-danger">Inactive</small>';
                        }

                        tbody +=
                        '<tr>' +
                            '<td>' + '<a href="#" onclick="fillDetails(' + user.merchandiser_id + ')">' + user.merchandiser_id  + '</a>' +
                            '<td>' + user.first_name + ' ' + user.last_name +
                            '<td>' + user.address +
                            '<td>' + user.contact_number +
                            '<td>' + user.agency_name +
                            '<td>' + user.account_type +
                            '<td>' + status +
                            '<td>' + user.username +
                            '<td>' + user.email +
                        '</tr>';
                    }

                    //table
                    let table =
                        '<table id="data-table-freeze" style="white-space: nowrap; width: 100%" class="table table-bordered">' +
                            '<thead>' +
                                '<th>ID' +
                                '<th>Name' +
                                '<th>Address' +
                                '<th>Contact#' +
                                '<th>Agency' +
                                '<th>Type' +
                                '<th>Status' +
                                '<th>Username' +
                                '<th>Email' +
                            '</thead>' +
                            '<tbody>' + tbody +
                            '</tbody>' +
                        '</table>';

                    $('#div-table-users').html(table);
                    setDataTable(2);
                    /*--------------------------------------------------------------------------*/

                    /*------------------------------ paging ------------------------------------*/
                    let prevButton = '<button class="btn btn-default btn-sm" onclick="getUsers(\'' + data.prev_page_url + '\')"><i class="fa fa-arrow-left"></i></button>';
                    let nextButton = '<button class="btn btn-default btn-sm" onclick="getUsers(\'' + data.next_page_url + '\')"><i class="fa fa-arrow-right"></i></button>';
                    $('#page-nav').html(showPageNavigation(data, prevButton, nextButton));
                    /*--------------------------------------------------------------------------*/
                    showLoading('loading-users', false);
                }
            });
        }

        //page load
        function onLoad(){
            getUsers(userUrl);
        }

        /* User Details Script  **********************************************************************/
        //add user button
        $('#btn-add').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-user'));
            let file = document.getElementById('img-user').files[0];

            if (file) {
                datas.append('img_user', file);
            }

            $.ajax({
                type: 'POST',
                url: userSaveUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('User has been added.'));
                    showLoading('loading-details', false);
                    getUsers(userUrl);
                },
                error: function(data){
                    $("#response-details").html(showErrorAlert(data));
                    showLoading('loading-details', false);
                },
            });
        });

        //update user button
        $('#btn-update').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-user'));
            let file = document.getElementById('img-user').files[0];

            if (file) {
                datas.append('img_user', file);
            }
            datas.append('merchandiser_id', selectedId);

            $.ajax({
                type: 'POST',
                url: userUpdateUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('User has been updated.'));
                    showLoading('loading-details', false);
                    getUsers(userUrl);
                },
                error: function(data){
                    $("#response-details").html(showErrorAlert(data));
                    showLoading('loading-details', false);
                },
            });
        });

        //cancel button
        $('#btn-cancel').click(function () {
            setEvent('save');
            $("#response-details").html("");
        });

        function setEvent(event){
            resetDetails();
            if(event == 'save'){
                $('#btn-cancel').css('display','none');
                $('#btn-update').css('display','none');
                $('#btn-add').css('display','block');
            }
            else{
                $('#btn-cancel').css('display','');
                $('#btn-update').css('display','');
                $('#btn-add').css('display','none');
            }
        }

        function resetDetails() {
            //reset form and image
            $("#img-user-src").attr("src","{{ asset('storage/avatars/avatar.png') }}");
            $('#form-user').trigger("reset");
        }

        function fillDetails(id) {

            setEvent('update');

            let user = alasql("SELECT * FROM ? WHERE merchandiser_id = " + id + "", [users])[0];
            selectedId = user.merchandiser_id;
            $('#account-type').val(user.account_id);
            $('#account-status').val(user.account_status);
            $('#last-name').val(user.last_name);
            $('#first-name').val(user.first_name);
            $('#role').val(user.role_id);
            $('#gender').val(user.gender);
            $('#contact-number').val(user.contact_number);
            $('#address').val(user.address);
            $('#birthday').val(user.birth_date);
            $('#agency').val(user.agency_code);
            $('#email').val(user.email);
            $('#username').val(user.username);
            $("#img-user-src").attr("src","../storage/" + user.image_path);
        }

        //show image upon upload
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#img-user-src').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#img-user").change(function () {
            readURL(this);
        });
        /* *******************************************************************************************/


        onLoad();
    </script>
@endsection