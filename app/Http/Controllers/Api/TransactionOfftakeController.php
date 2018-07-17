<?php

namespace App\Http\Controllers\Api;

use App\InventoryTransactionItem;
use App\TransactionOfftake;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransactionOfftakeController extends Controller
{
    public function getBeginningBalance(Request $request){
        $customer_code = $request->customer_code;

        $beginningBalance = TransactionOfftake::where('customer_code', $customer_code)
            ->orderByDesc('created_at')
            ->get([
                'material_code',
                'base_uom',
                'ending_balance',
                'created_at'
            ])
            ->unique('material_code')

        ;

        return [
            'items' => $beginningBalance,
            'count' => $beginningBalance->count()
        ];
    }

    public function addTransactionOfftake(Request $request){
        $transaction_number = $request->transaction_number;

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

        foreach ($transactionItems as $material_codes){
            $beginning_balance = 0;
            $delivery = 0;
            $rtv = 0;
            $physical_count = 0;
            $warehouse_area = 0;
            $bo_area = 0;
            $shelves_area = 0;
            $offtake = 0;
            $ending_balance = 0;

            foreach ($material_codes as $material_code){

                $inventoryType = $material_code->inventory_type;


                if($inventoryType == '1'){ #warehouse
                    $warehouse_area = $material_code->base_qty;
                }
                elseif($inventoryType == 2){ #shelves
                    $shelves_area = $material_code->base_qty;
                }
                elseif($inventoryType == 3){ #bo
                    $bo_area = $material_code->base_qty;
                }
                elseif($inventoryType == 4){ #delivery
                    $delivery = $material_code->base_qty;
                }
                elseif($inventoryType == 5){ #return
                    $rtv = $material_code->base_qty;
                }
                elseif($inventoryType == 6){ #beginning
                    $beginning_balance = $material_code->base_qty;
                }

            }

            return $warehouse_area;


        }

        return $transactionItems;

    }
}
