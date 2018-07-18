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
        return $this->getLatestEndingBalance($request->customer_code);
    }

    public function addTransactionOfftake(Request $request){
        $transaction_number = $request->transaction_number;

        $result = 'false';

        if (!TransactionOfftake::where('transaction_number', $transaction_number)->exists()) { #if transaction is not yet added
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

            $beginningBalances = $this->getLatestEndingBalance($customerCode);

            #loop all inventory types from materials to set beg.bal&whse etc...
            foreach ($transactionItems as $materials){

                $beginning_balance = $beginningBalances
                    ->where('material_code', $materials->first()->material_code)
                    ->pluck('ending_balance')
                    ->first();

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

                }

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

    private function getLatestEndingBalance($customer_code){

        $beginningBalance = TransactionOfftake::where('customer_code', $customer_code)
            ->orderByDesc('created_at')
            ->get([
                'material_code',
                'base_uom',
                'ending_balance',
                'created_at'
            ])
            ->unique('material_code')
            ->values()
            ->all();

        return collect($beginningBalance);
    }

    public function backgroundTransactionOfftake(){

        $customers = Customer::all();
        foreach ($customers as $customer) {

            $customer_code = $customer->customer_code;

            $lastInventory = InventoryTransactionHeader::where('customer_code', $customer_code)
                ->latest()
                ->first();


            if(!empty($lastInventory)){

                $transaction_number = $lastInventory->transaction_number;

                if (!TransactionOfftake::where('transaction_number', $transaction_number)->exists()) { #if transaction is not yet added

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

                    $beginningBalances = $this->getLatestEndingBalance($customer_code);

                    #loop all inventory types from materials to set beg.bal&whse etc...
                    foreach ($transactionItems as $materials){

                        $beginning_balance = $beginningBalances
                            ->where('material_code', $materials->first()->material_code)
                            ->pluck('ending_balance')
                            ->first();

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
                        }



                        $physical_count = $this->computePhysicalCount($warehouse_area, $bo_area, $shelves_area);
                        $offtake = $this->computeOfftake($beginning_balance,$physical_count,$rtv,$delivery);
                        $ending_balance = $this->computeEndingBalance($beginning_balance,$offtake,$delivery,$rtv);

                        if($offtake < 0) $offtake = 0; #to handle negative values during first time inventory

                        $transactionOfftake = new TransactionOfftake();
                        $transactionOfftake->transaction_number = $transaction_number;
                        $transactionOfftake->customer_code = $customer_code;
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
                }


            }


        }



    }
}
