<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.head')
</head>


<body class="hold-transition skin-blue-light sidebar-mini" onload="document.getElementById('chat_message').scrollTop = document.getElementById('chat_message').scrollHeight; ">

    <div class="direct-chat direct-chat-warning">
        @if(count($physicalCountItems))
        <div class="row">
            <div class="col-md-12">
                <label>Physical Count</label>
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <thead>
                        <td>Material Code</td>
                        <td>Material Description</td>
                        <td>Inventory Type</td>
                        <td>Entry UOM</td>
                        <td>Entry Qty</td>
                        <td>Base UOM</td>
                        <td>Base Qty</td>
                        <td>Expiration Date</td>
                        </thead>
                        <tbody>
                        @foreach($physicalCountItems as $physicalCountItem)
                            <tr>
                                <td>{{ $physicalCountItem->material_code }}</td>
                                <td>{{ $physicalCountItem->material_description }}</td>
                                <td>{{ $physicalCountItem->type }}</td>
                                <td>{{ $physicalCountItem->entry_uom }}</td>
                                <td>{{ $physicalCountItem->entry_qty }}</td>
                                <td>{{ $physicalCountItem->base_uom }}</td>
                                <td>{{ $physicalCountItem->base_qty }}</td>
                                <td>{{ $physicalCountItem->expiration_date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(count($deliveryCountItems))
        <div class="row">
            <div class="col-md-12">
                <label>Delivery Count</label>
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <thead>
                        <td>Material Code</td>
                        <td>Material Description</td>
                        <td>Inventory Type</td>
                        <td>Entry UOM</td>
                        <td>Entry Qty</td>
                        <td>Base UOM</td>
                        <td>Base Qty</td>
                        <td>Delivery Date</td>
                        </thead>
                        <tbody>
                        @foreach($deliveryCountItems as $deliveryCountItem)
                            <tr>
                                <td>{{ $deliveryCountItem->material_code }}</td>
                                <td>{{ $deliveryCountItem->material_description }}</td>
                                <td>{{ $deliveryCountItem->type }}</td>
                                <td>{{ $deliveryCountItem->entry_uom }}</td>
                                <td>{{ $deliveryCountItem->entry_qty }}</td>
                                <td>{{ $deliveryCountItem->base_uom }}</td>
                                <td>{{ $deliveryCountItem->base_qty }}</td>
                                <td>{{ $deliveryCountItem->delivery_date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(count($returnCountItems))
        <div class="row">
            <div class="col-md-12">
                <label>Return Count</label>
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <thead>
                        <th>Material Code</th>
                        <th>Material Description</th>
                        <th>Inventory Type</th>
                        <th>Entry UOM</th>
                        <th>Entry Qty</th>
                        <th>Base UOM</th>
                        <th>Base Qty</th>
                        <th>Return Date</th>
                        <th>Expiration Date</th>
                        </thead>
                        <tbody>
                        @foreach($returnCountItems as $returnCountItem)
                            <tr>
                                <td>{{ $returnCountItem->material_code }}</td>
                                <td>{{ $returnCountItem->material_description }}</td>
                                <td>{{ $returnCountItem->type }}</td>
                                <td>{{ $returnCountItem->entry_uom }}</td>
                                <td>{{ $returnCountItem->entry_qty }}</td>
                                <td>{{ $returnCountItem->base_uom }}</td>
                                <td>{{ $returnCountItem->base_qty }}</td>
                                <td>{{ $returnCountItem->return_date }}</td>
                                <td>{{ $returnCountItem->expiration_date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <label>Screen Shot</label>
                <div align="center">
                    <img id="inventoryImage" src="{{ asset('storage/' . $transactionImage) }}">
                </div>
            </div>
        </div>
    </div>

</body>