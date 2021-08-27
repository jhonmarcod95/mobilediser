<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\InventoryTransactionHeader;
use App\InventoryTransactionItem;
use App\TransactionOfftake;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransactionOfftakeController extends Controller
{

    public function getBeginningBalance(Request $request){
        $customer_code = $request->customer_code;

        // todo:: use eloquent here
        // returns carried material of a specific customer with the last ending balance and base UOM and expiry dates
        // if no ending balance result, there must be an identifier to determine if first time inventory
        // based uom = entered qty * (numerator / denom)

        $beginningBalance = DB::select("CALL p_beginning_balance('$customer_code')");
        $beginningBalance = collect($beginningBalance);
        return [
            'items' => $beginningBalance,
            'count' => $beginningBalance->count()
        ];
    }

    public function addTransactionOfftake($transaction_number){ // change to storeInventory

        /* params :
        inventories : [
            {
                Type : Inbound
                Inbound Type : Delivery, Transfer
                Material : Item 1
                Qty : 1
                UOM : CV
                Date Delivered : 2021-01-01
                Remarks :
            },
            {
                Type : Return
                Material :
                Qty : 1
                UOM : CV
                Date Returnd : 2021-01-01
                Expry Date : 2021-01-01
                Remarks :
            },
            {
                Type : Phyisical
                Material : Item 1
                Location : Shelves, warehouse
                Qty : 1
                UOM : CV
                Expry Date : 2021-01-01
                Remarks :
            },
            {
                Type : Phyisical
                Material : Item 1
                Location : Shelves, warehouse
                Qty : 10
                UOM : CV
                Expry Date : 2021-01-01
                Remarks :
            },
        ],
        Image :
         */

        // step 1 generate transaction #
        // step 2 loop each material to get base UOM conversion
        // step 3 compute physical, offtake, endingbalance *note: group by type, material code and sum qty
        // step 4 save to inventory header and items, images table
        // step 4 save to offtake table and return status




        $result = 'false';

        /*----------------------------- transaction is not yet added -------------------------------*/
        if (!TransactionOfftake::where('transaction_number', $transaction_number)->exists()) {
            $customerCode = InventoryTransactionHeader::where('transaction_number', $transaction_number)
                ->pluck('customer_code')
                ->first();

            $transactionItems = InventoryTransactionItem::where('transaction_number', $transaction_number)
                ->groupBy(
                    'material_code',
                    'inventory_type',
                    'base_uom'
                )
                ->select(
                    'material_code',
                    DB::raw('SUM(base_qty) AS base_qty'),
                    'base_uom',
                    'inventory_type'
                )
                ->get()
                ->groupBy('material_code');

            /*----------- loop all inventory types from materials to set beg.bal&whse etc... --------*/
            foreach ($transactionItems as $materials){
                $beginning_balance = 0;
                $delivery = 0;
                $rtv = 0;
                $warehouse_area = 0;
                $bo_area = 0;
                $shelves_area = 0;

                foreach ($materials as $material){

                    $inventoryType = $material->inventory_type;

                    if($inventoryType == '1'){ #warehouse
                        $warehouse_area = $material->base_qty;
                    }
                    elseif($inventoryType == '2'){ #shelves
                        $shelves_area = $material->base_qty;
                    }
                    elseif($inventoryType == '3'){ #bo
                        $bo_area = $material->base_qty;
                    }
                    elseif($inventoryType == '4'){ #delivery
                        $delivery = $material->base_qty;
                    }
                    elseif($inventoryType == '5'){ #return
                        $rtv = $material->base_qty;
                    }
                    elseif($inventoryType == '6'){ #beginning_balance
                        $beginning_balance = $material->base_qty;
                    }
                }
                /*-------------------------------------------------------------------------------------*/

                $physical_count = $this->computePhysicalCount($warehouse_area, $bo_area, $shelves_area);
                $offtake = $this->computeOfftake($beginning_balance,$physical_count,$rtv,$delivery);
                $ending_balance = $this->computeEndingBalance($beginning_balance,$offtake,$delivery,$rtv);

                $transactionOfftake = new TransactionOfftake();
                $transactionOfftake->transaction_number = $transaction_number;
                $transactionOfftake->customer_code = $customerCode;
                $transactionOfftake->material_code = $material->material_code;
                $transactionOfftake->base_uom = $material->base_uom;
                $transactionOfftake->beginning_balance = $beginning_balance;
                $transactionOfftake->delivery = $delivery;
                $transactionOfftake->rtv = $rtv;
                $transactionOfftake->physical_count = $physical_count;
                $transactionOfftake->warehouse_area = $warehouse_area;
                $transactionOfftake->bo_area = $bo_area;
                $transactionOfftake->shelves_area = $shelves_area;
                $transactionOfftake->offtake = $offtake;
                $transactionOfftake->ending_balance = $ending_balance;
                $transactionOfftake->save();
            }
            $result = 'success';
        }
        return $result;
    }

    private function computePhysicalCount($warehouse_area, $bo_area, $shelves_area){
        $result = $warehouse_area + $bo_area + $shelves_area;
        return $result;
    }

    private function computeOfftake($beginning, $physical, $return, $inbound){
        $result = ($beginning - $physical - $return) + ($inbound);
        return $result;
    }

    private function computeEndingBalance($beginning, $offtake, $inbound, $return)
    {
        $result = ($beginning - $offtake) + ($inbound - $return);
        return $result;
    }

}
