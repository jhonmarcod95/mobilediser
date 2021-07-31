<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteExcessImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:ExcessImages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $inventory_images = DB::select(
            "SELECT * FROM 
            `inventory_transaction_image` 
            LEFT JOIN
            (
            SELECT MIN(id) as id, transaction_number FROM 
            `inventory_transaction_image` 
            GROUP BY 
            transaction_number 
            ORDER BY id ASC
            
            ) AS inv 
            ON
            inventory_transaction_image.id =  inv.id
            WHERE
            inv.id IS NULL"
        );

        $ctr = 0;
        foreach ($inventory_images as $k => $inventory_image){
            Storage::delete('/public/' . $inventory_image->image_path); // delete image in storage
            $ctr++;
        }

        dd('done : ' . $ctr);
    }
}
