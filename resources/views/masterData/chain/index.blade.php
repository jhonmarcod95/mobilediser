@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Chain Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Chain Master Data</a></li>
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
                                    <input id="search-text" type="text" class="form-control input-sm" placeholder="Search Chain">
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
                        <div id="div-table-chain" class="table-responsive mailbox-messages"></div>
                    </div>
                    <div id="page-nav" class="box-footer"></div>
                    <div id="loading-chain"></div>
                </div>
            </div>

            {{-- Details Box --}}
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chain Details</h3>
                    </div>

                    {{-- Form Chain --}}
                    <div class="box-body">
                        <form id="form-chain">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Chain Code</label>
                                        <input id="chain-code" name="chain_code" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Description</label>
                                        <input id="description" name="description" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Account</label>
                                        {!! Form::select('account', $customerAccounts, null, ['class' => 'form-control select2', 'placeholder' => 'Select Account', 'id' => 'account']) !!}
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
                                <button id="btn-add" class="btn btn-primary btn-sm">Add Chain</button>
                                <button id="btn-update" class="btn btn-primary btn-sm" style="display:none">Update Chain</button>
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
        let chains;
        let totalChain = 0;
        let chainUrl = '/chain-all';
        let chainSaveUrl = '/chains/save';
        let chainUpdateUrl = '/chains/update';
        let selectedId;

        //searching
        $('#search-text').keyup(function () {
            clearTimeout(timeoutId);
            searchText = $('#search-text').val();
            timeoutId = setTimeout(function() {
                fetchChains(chainUrl);
            }, 750)
        });

        //show all
        $('#btn-show-all').click(function () {
            paginate = totalChain;
            fetchChains(chainUrl);
        });

        //fetch chains
        function fetchChains(url){
            showLoading('loading-chain', true);
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
                    chains = data.data;

                    let tbody = '';
                    totalChain = data.total;

                    //body
                    for (let chain of chains){
                        tbody +=
                            '<tr>' +
                                '<td>' + '<a href="#" onclick="fillDetails(' + chain.id + ')">' + chain.chain_code  + '</a>' +
                                '<td>' + chain.chain_description +
                                '<td>' + chain.account_description +
                            '</tr>';
                    }

                    //table
                    let table =
                        '<table id="data-table-freeze" style="white-space: nowrap; width: 100%" class="table table-bordered">' +
                        '<thead>' +
                            '<th>Chain Code' +
                            '<th>Description' +
                            '<th>Account' +
                        '</thead>' +
                        '<tbody>' + tbody +
                        '</tbody>' +
                        '</table>';

                    $('#div-table-chain').html(table);
                    setDataTable(2);
                    /*--------------------------------------------------------------------------*/

                    /*------------------------------ paging ------------------------------------*/
                    let prevButton = '<button class="btn btn-default btn-sm" onclick="fetchChains(\'' + data.prev_page_url + '\')"><i class="fa fa-arrow-left"></i></button>';
                    let nextButton = '<button class="btn btn-default btn-sm" onclick="fetchChains(\'' + data.next_page_url + '\')"><i class="fa fa-arrow-right"></i></button>';
                    $('#page-nav').html(showPageNavigation(data, prevButton, nextButton));
                    /*--------------------------------------------------------------------------*/
                    showLoading('loading-chain', false);
                }
            });
        }

        //page load
        function onLoad(){
            fetchChains(chainUrl);
        }

        /* Agency Details Script  **********************************************************************/
        //add button
        $('#btn-add').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-chain'));

            $.ajax({
                type: 'POST',
                url: chainSaveUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Agency has been added.'));
                    showLoading('loading-details', false);
                    fetchChains(chainUrl);
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

            let datas = new FormData(document.getElementById('form-chain'));
            datas.append('id', selectedId);

            $.ajax({
                type: 'POST',
                url: chainUpdateUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Agency has been updated.'));
                    showLoading('loading-details', false);
                    fetchChains(chainUrl);
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
            $('#form-chain').trigger("reset");
            $('#account').trigger('change');
        }

        //fill-up form details once table id is clicked
        function fillDetails(id) {
            setEvent('update');

            let chain = alasql("SELECT * FROM ? WHERE id = " + id + "", [chains])[0];
            selectedId = chain.id;
            $('#chain-code').val(chain.chain_code);
            $('#description').val(chain.chain_description);
            $('#account').val(chain.account_code).trigger('change');
        }
        /* *******************************************************************************************/


        onLoad();
    </script>
@endsection