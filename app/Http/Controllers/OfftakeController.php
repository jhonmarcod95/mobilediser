<?php

namespace App\Http\Controllers;

use App\Chain;
use App\Customer;
use App\CustomerCategory;
use App\Island;
use App\Municipality;
use App\Province;
use App\Region;
use Illuminate\Http\Request;

class OfftakeController extends Controller
{
    public function index(){

        $customerAccounts = CustomerCategory::showCodeAndName()
            ->put('%', 'All');

        $chains = Chain::showCodeAndName()
            ->put('%', 'All');

        $customers = Customer::showCodeAndName()
            ->put('%', 'All');

        $islands = Island::showCodeAndName()
            ->put('%', 'All');

        $regions = Region::showCodeAndName()
            ->put('%', 'All');

        $provinces = Province::showCodeAndName()
            ->put('%', 'All');

        $municipalities = Municipality::showCodeAndName()
            ->put('%', 'All');



        $places = [
            '%' => 'All',
            'island' => 'Island',
            'region' => 'Region',
            'province' => 'Province',
            'municipality' => 'Municipality',
        ];

        return view('offtake.index', compact(
            'places',
            'customers',
            'customerAccounts',
            'chains',
            'islands',
            'regions',
            'provinces',
            'municipalities'
        ));
    }

    public function getIslands(){
        return Island::all();
    }

    public function getRegions(){

    }

    public function getProvinces(){

    }

    public function getMunicipalities(){

    }

    public function indexData(){

    }

}
