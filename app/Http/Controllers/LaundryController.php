<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaundryController extends Controller
{
    public function updateRegistry(Request $request){
        /*return [
            $request->registry,
            $request->date,
        ];*/

        foreach($request->registry as $id=>$amount){
            
            $registry = \App\Registry::where([
                ["material_id" , "=" , $id],
                ["date", "=" , $request->date]
            ])->first();

            //return [$registry->id];

            if ($registry == null){
                $registry = new \App\Registry();
                $registry->material_id = $id;
                $registry->date = $request->date;
            }

            $registry->received = $amount["rec"];
            $registry->given =  $amount["giv"];
            $registry->save();
        }

        return ["ok"];
    }
}
