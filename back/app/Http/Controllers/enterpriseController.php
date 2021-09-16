<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\enterprise;
use App;
use App\Http\Exports\FiltroApi;
use App\Tools;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class enterpriseController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response 
     */
    public function index() {
        return $index = enterprise::all();
    }
    
    public function adminIndex() {
        $arrayBuckets = array();
        $index = enterprise::all()->toArray();

        foreach ($index as $key => $value) {
            $arr = array();
            foreach ($value as $key2 => $value2) {
                if ($key2 === 'business_name' || $key2 === 'nit' ) {
                    array_push($arr, $value2);
                }
            }
            $unir = implode("-", $arr);
            array_push($arrayBuckets, $unir);
        }
        // return $index;
        return $arrayBuckets;
    }
    
    public function soenacCampos() {
    
        $structureToFilter = ['type_document_identifications', 'type_organizations', 'type_regimes', 'type_liabilities','municipalities', 'type_environments'];
        $fieldsToFilter = ['name','id'];
        $url = "https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/listings";

        return json_encode(Tools::filterApi($url, $fieldsToFilter , $structureToFilter));
    }

    public function resolutions() {
        $url = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/resolutions';
        $datos = Tools::http_get($url);
        // dd(json_encode($datos));
        return json_encode($datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    //     return view('empresa.create');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nuevaEmpresa = new enterprise ($request->all());
        $nuevaEmpresa->save();
        return 'done';
        
    }
    public function softInfo(Request $request)
    {
        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/software';
        $datos = Tools::http_post($urlPost, $request);
        $campoEdit = enterprise::find($request->id);
        $campoEdit->last_software_response = $datos;
        $campoEdit->save();
        return 'done';
    }

    public function productionNumbers(Request $request) 
    {
        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/numbering/range' + '/' + $request->nit + '/' + $request->nit + '/' + $request->software_id;
        $datos = Tools::http_post($urlPost, $request);
        return $datos;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\enterprise  $enterprise
     * @return \Illuminate\Http\Response
     */
    public function show(enterprise $enterprise, $id)
    {
        return $index = $enterprise->find($id);
    }

    public function showAdmin($nit)
    {
        return enterprise::where('nit', '=', $nit)->get()->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\enterprise  $enterprise
     * @return \Illuminate\Http\Response
     */
    // public function edit(enterprise $enterprise)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\enterprise  $enterprise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $editarEmpresa = enterprise::find($id);
        $editarEmpresa->fill($request->all());
        // $editarEmpresa->updated_at = null;
        $editarEmpresa->save();
        return 'done';
    }

    public function enterpriseUpdate(Request $request, $id)
    {
        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/901383689';
        $datos = Tools::http_put($urlPut, $request);
        return 'done';
    }

    public function certificateUp(Request $request)
    {
        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/certificate';
        $datos = Tools::http_put($urlPut, $request);
        $campoEdit = enterprise::find($request->id);
        $campoEdit->last_certificate_response = $datos;
        $campoEdit->save();
        return 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\enterprise  $enterprise
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        enterprise::where('id', $id)->delete();
        return 'done';
    }

    public function testing() {
       return Carbon::now();
    }

}
