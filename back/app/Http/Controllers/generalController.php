<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\general;
use App\Http\Exports\FiltroApi;
use App\Tools;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class generalController extends Controller
{
    public function index (){

        return $inicio;
    }

    public function store(Request $request)
    {
        //
        if (general::all()->isNotEmpty()) {
            $generalUp = general::first();
            $generalUp->fill($request->all());
            $generalUp->save();
        }
        else {
            $nuevoGeneral = new general ($request->all());
            $nuevoGeneral->save();
        }

        return 'done';
    }

    public function show ($id){

        return $detalle->masterToken;
    }

    public function update($id)
    {
        //        

        $editar->save();

        return $editar;
    }

    public function destroy ($id)
    {
        //
        $eliminar->delete();
    }
}
