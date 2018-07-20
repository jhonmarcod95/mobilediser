<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionOfftake extends Model
{
    protected $table = 'transaction_offtake';

    public static function getMaterialOfftake(){
//        $date = Carbon::now()->format('Y-m-d');
        $date = '2018-07-18';

        $result = TransactionOfftake::whereDate('transaction_offtake.created_at', $date)
            ->join('customer_master_data', 'customer_master_data.customer_code', 'transaction_offtake.customer_code')
            ->join('chain', 'chain.chain_code', 'customer_master_data.chain_code')
            ->join('material_master_data', 'material_master_data.material_code', 'transaction_offtake.material_code')
            ->get([
                'material_master_data.material_code',
                'material_master_data.material_description',
                'transaction_offtake.offtake',
                'transaction_offtake.ending_balance',
                'customer_master_data.customer_code',
                'customer_master_data.name',
                'customer_master_data.branch',
                'chain.chain_code',
                'chain.description',
                'transaction_offtake.created_at'
            ])
        ;

        return $result;
    }
}
