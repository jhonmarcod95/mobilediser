<?php
namespace App\Http\Controllers;

use FontLib\Table\Type\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;


class MaatwebsiteController extends Controller
{
    public function getSundays($y,$m){
        $date = "$y-$m-01";
        $first_day = date('N',strtotime($date));
        $first_day = 7 - $first_day + 7;
        $last_day =  date('t',strtotime($date));
        $days = array();
        for($i=$first_day; $i<=$last_day; $i=$i+7 ){
            $days[] = $i;
        }
        return  $days;
    }


    public function importExport()
    {

        $days = $this->getSundays(2018,8);

        dd($days);

        for($i=1; $i<=31; $i++){
            dd(date("M d Y", strtotime(''.$i.' Mon')));
        }

        return view('importExcel');
    }
    public function downloadExcel($type)
    {
        $data = Post::get()->toArray();
        return Excel::create('laravelcode', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
    public function importExcel(Request $request)
    {
        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                foreach ($reader->toArray() as $key => $row) {

                    $x[] = $row['code'];

//                    $data['title'] = $row['title'];
//                    $data['description'] = $row['description'];
//
//                    if(!empty($data)) {
//                        DB::table('post')->insert($data);
//                    }
                }
                dd($x);

            });


        }

        Session::put('success', 'Youe file successfully import in database!!!');

        return back();
    }
}
