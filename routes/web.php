<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get("/getStock",function(){
    $materials = \App\Material::all();
    $stock = [];

    foreach( $materials as $material){
        $stock[$material->id] = $material->initial;
    }
    return Response::json(array($stock));
})->name("getStock");

Route::get("/getRegistry/{year}/{month}/{day}",function($year,$month,$day){
    $results = [];

    $materials = \App\Material::all();
    foreach($materials as $material){
        $results[$material->id] = [
            "giv" => 0,
            "rec" => 0
        ];
    }

    $registry = \App\Registry::where("date",$year."-".$month."-".$day)->get();
    foreach($registry as $reg){
        $results[$reg->material_id] = [
            "giv" => $reg->given,
            "rec" => $reg->received
        ];
    }

    return Response::json(array($results));
})->name("getResgistry");

Route::post("/updateRegistry","LaundryController@updateRegistry")->name("updateRegistry");

/*
Route::get("/getStock",function(){
    $registry = DB::table('registry')
    ->select(["date",DB::raw("SUM(given) as total_given"), DB::raw("SUM(received) as total_received")])
     ->whereYear('date', '=', $year)->whereMonth('date', '=', $month)
     ->groupBy('date')
     ->get();


    $registry_formated = [];

    foreach ($registry as $reg){
        $registry_formated[$reg->date] = $reg->total_given - $reg->total_received;
    }
})->name("getStock");*/

Route::get("/getStock",function(){
    $registry = DB::table('registry')->select(["date","material_id","given","received"])
    ->whereBetween('date', [ date('2021-01-01'), date("Y-m-d")])
    ->orderBy("date")
    //->select(["*",DB::raw("SUM(given) as total_given"), DB::raw("SUM(received) as total_received")])
     //->whereYear('date', '=', date("Y"))->whereMonth('date', '<=', date("m"))
     //->groupBy('material_id')
     ->get();

    

    $registry_formated = [];
    $daily_rolling_total = [];

    foreach ($registry as $reg){
        $registry_formated[$reg->date][$reg->material_id] =  $reg->given - $reg->received;
        if( !isset($daily_rolling_total[$reg->date]) ) $daily_rolling_total[$reg->date] = 0;
        $daily_rolling_total[$reg->date] += $reg->given - $reg->received;
    }

    //accumulate results
    /*for( $i = 0 ; $i < count($registry_formated) ; $i++){
        foreach($registry_formated[$i] as $material_id){
            $registry_formated[$i][$material_id] += $registry_formated[$i-1][$material_id];
        }
    }*/

    //accumulate results

    $prev = null;
    foreach($registry_formated as $date=>$materials){
        reset($registry_formated);
        if ($date === key($registry_formated)){//FIRST ELEMENT
            $prev =  $registry_formated[$date];
            continue;    
        }

        foreach($materials as $id=>$amount){
            $registry_formated[$date][$id] += $prev[$id];
        }

        $prev =  $registry_formated[$date];
        /*end($array);
        if ($key === key($array))*/
    }

    $prev = null;
    foreach($daily_rolling_total as $date=>$sum){
        reset($daily_rolling_total);
        if ($date === key($daily_rolling_total)){//FIRST ELEMENT
            $prev =  $daily_rolling_total[$date];
            continue;    
        }

        $daily_rolling_total[$date] += $prev;

        $prev = $daily_rolling_total[$date];
    }


    return Response::json([ "per_item" => $registry_formated , "daily_totals" => $daily_rolling_total]);
})->name("getStock");

Route::get("/calendar/{year?}/{month?}",function($year = null ,$month = null){
    $year == null ? $year = date("Y") : null;
    $month == null ? $month =  date("m") : null;
    //$registry = \App\Registry::whereYear('date', '=', $year)->whereMonth('date', '=', $month)->get();
    $registry = DB::table('registry')
            ->select(["date",DB::raw("SUM(given) as total_given"), DB::raw("SUM(received) as total_received")])
             ->whereYear('date', '=', $year)->whereMonth('date', '=', $month)
             ->groupBy('date')
             ->get();


    $registry_formated = [];

    foreach ($registry as $reg){
        $registry_formated[$reg->date] = $reg->total_given - $reg->total_received;
    }

    //dd($registry_formated);

    return view("calendar",[
        "month" => $month,
        "year" => $year,
        "registry" => $registry_formated
    ]);
})->name("calendar");


/*
Route::get("/calendar/{year}/{month}",function($year,$month){
    $registry = DB::table('registry')
    ->select(["date",DB::raw("SUM(given) as total_given"), DB::raw("SUM(received) as total_received")])
     ->whereYear('date', '=', $year)->whereMonth('date', '=', $month)
     ->groupBy('date')
     ->get();

     $registry_formated = [];

    foreach ($registry as $reg){
        $registry_formated[$reg->date] = $reg->total_given - $reg->total_received;
    }

    return view("calendar",[
        "month" => $month,
        "year" => $year,
        "registry" => $registry_formated
    ]);
})->name("monthly");*/

Route::get("/calendar/{year}/{month}/{day}",function($year,$month,$day){
    return view("daily",[
        "month" => $month,
        "year" => $year,
        "day" => $day
    ]);
})->name("daily");

Route::get("/testinit",function(){
    dd();
    $materials = \App\Material::all();

    foreach($materials as $material){
        $registry = new \App\Registry();
        $registry->date = "2021-07-14";
        $registry->material_id = $material->id;
        $registry->received = rand(0,100);
        $registry->given = rand(0,100);
        $registry->save();
    }
});