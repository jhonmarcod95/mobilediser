@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Material Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Material Master Data</a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="has-feedback">
                                    <input id="search-text" type="text" class="form-control input-sm" placeholder="Search Material">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="has-feedback">
                                    <button id="btn-show-all" class="btn btn-primary btn-sm pull-right">Show All</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="box-body">
                        <div id="div-table-material" class="table-responsive mailbox-messages"></div>
                    </div>

                    <div id="page-nav" class="box-footer"></div>
                    <div id="loading-material"></div>
                </div>
            </div>

            {{-- Details Box --}}
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Material Details</h3>
                    </div>

                    {{-- Form Material --}}
                    <div class="box-body">
                        <form id="form-material">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="text-muted">Material Code</label>
                                        <input id="material-code" name="material_code" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Material Description</label>
                                        <input id="material-description" name="material_description" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">Base Unit</label>
                                        <input id="base-unit" name="base_unit" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Main Category</label>
                                        {!! Form::select('main_category', $materialMainGroups, null, ['class' => 'form-control select2', 'placeholder' => 'Select main', 'id' => 'main-category']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Sub Category</label>
                                        {!! Form::select('sub_category', $materialSubGroups, null, ['class' => 'form-control select2', 'placeholder' => 'Select sub', 'id' => 'sub-category']) !!}
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
                                <button id="btn-add" class="btn btn-primary btn-sm">Add Material</button>
                                <button id="btn-update" class="btn btn-primary btn-sm" style="display:none">Update Material</button>
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
        let materials;
        let totalMaterial = 0;
        let materialUrl = '/material-all';
        let materialSaveUrl = '/materials/save';
        let materialUpdateUrl = '/materials/update';
        let selectedId;

        //searching
        $('#search-text').keyup(function () {
            clearTimeout(timeoutId);
            searchText = $('#search-text').val();
            timeoutId = setTimeout(function() {
                fetchMaterials(materialUrl);
            }, 750)
        });

        //show all
        $('#btn-show-all').click(function () {
            paginate = totalMaterial;
            fetchMaterials(materialUrl);
        });

        //fetch materials
        function fetchMaterials(url){
            showLoading('loading-material', true);
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
                    materials = data.data;

                    let tbody = '';
                    totalMaterial = data.total;

                    //body
                    for (let material of materials){
                        tbody +=
                        '<tr>' +
                            '<td>' + '<a href="#" onclick="fillDetails(' + material.id + ')">' + material.material_code  + '</a>' +
                            '<td>' + material.material_description +
                            '<td>' + material.base_unit +
                            '<td>' + material.main_group_description +
                            '<td>' + material.sub_group_description +
                        '</tr>';
                    }

                    //table
                    let table =
                        '<table id="data-table-freeze" style="white-space: nowrap; width: 100%" class="table table-bordered">' +
                        '<thead>' +
                            '<th>Material Code' +
                            '<th>Material Description' +
                            '<th>Base Unit' +
                            '<th>Main Category' +
                            '<th>Sub Category' +
                        '</thead>' +
                        '<tbody>' + tbody +
                        '</tbody>' +
                        '</table>';

                    $('#div-table-material').html(table);
                    setDataTable(2);
                    /*--------------------------------------------------------------------------*/

                    /*------------------------------ paging ------------------------------------*/
                    let prevButton = '<button class="btn btn-default btn-sm" onclick="fetchMaterials(\'' + data.prev_page_url + '\')"><i class="fa fa-arrow-left"></i></button>';
                    let nextButton = '<button class="btn btn-default btn-sm" onclick="fetchMaterials(\'' + data.next_page_url + '\')"><i class="fa fa-arrow-right"></i></button>';
                    $('#page-nav').html(showPageNavigation(data, prevButton, nextButton));
                    /*--------------------------------------------------------------------------*/
                    showLoading('loading-material', false);
                }
            });
        }

        //page load
        function onLoad(){
            fetchMaterials(materialUrl);
        }

        /* Agency Details Script  **********************************************************************/
        //add button
        $('#btn-add').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-material'));

            $.ajax({
                type: 'POST',
                url: materialSaveUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Material has been added.'));
                    showLoading('loading-details', false);
                    fetchMaterials(materialUrl);
                },
                error: function(data){
                    $("#response-details").html(showErrorAlert(data));
                    showLoading('loading-details', false);
                },
            });
        });

        //update button
        $('#btn-update').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-material'));
            datas.append('id', selectedId);

            $.ajax({
                type: 'POST',
                url: materialUpdateUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Material has been updated.'));
                    showLoading('loading-details', false);
                    fetchMaterials(materialUrl);
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

        //set if add or update
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

        //clear form inputs
        function resetDetails() {
            //reset form inputs
            $('#form-material').trigger("reset");
            $('#main-category').trigger('change');
            $('#sub-category').trigger('change');
        }

        //fill-up form details once table id is clicked
        function fillDetails(id) {
            setEvent('update');

            let material = alasql("SELECT * FROM ? WHERE id = " + id + "", [materials])[0];
            selectedId = material.id;
            $('#material-code').val(material.material_code);
            $('#material-description').val(material.material_description);
            $('#base-unit').val(material.base_unit);
            $('#main-category').val(material.main_group).trigger('change');
            $('#sub-category').val(material.sub_group).trigger('change');
        }
        /* *******************************************************************************************/


        onLoad();
    </script>
@endsection