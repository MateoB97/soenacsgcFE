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
use Illuminate\Support\Collection;

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
          
        $resolutionData = collect();

        $resolutionData->type_document_id = 1;
        $resolutionData->from = 990000000;
        $resolutionData->to = 995000000;
        $resolutionData->resolution = 18760000001;
        $resolutionData->resolution_date = "0001-01-01";
        $resolutionData->technical_key = "fc8eac422eba16e22ffd8c6f94b3f40a6e38162c";
        $resolutionData->date_from = "2019-01-19";
        $resolutionData->date_to = "2030-01-19";
        $resolutionData->prefix = "SETP";

        $url = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/resolutions';
        $datos = Tools::http_post($url, $resolutionData);
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

    public function confirmEnterpriseDian($id)
    {

        return $id;
        // $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/' . $request->nit;
        
        // // $data = collect();
        // $data = new \stdClass();
        
        // $data->type_document_identification_id = $request->type_document_identification_id;
        // $data->type_organization_id = $request->type_organization_id;
        // $data->type_regime_id = $request->type_regime_id;
        // $data->type_liability_id = $request->type_liability_id;
        // $data->business_name = $request->business_name;
        // $data->merchant_registration = $request->merchant_registration;
        // $data->municipality_id = $request->municipality_id;
        // $data->address = $request->address;
        // $data->phone = $request->phone;
        // $data->email = $request->email;

        // $response = Tools::http_post($urlPost, $data);

        // if ($response === true) {
        //     $request->sendConfirm = true;
        //     $campoEdit = enterprise::find($request->id);
        //     $campoEdit->sendConfirm = $request->sendConfirm;
        //     $campoEdit->save();

        //     $info = collect();
        //     $info->token = $response;
        //     $info->mensaje = 'empresa guardada';
        //     return $info;
        // }
        // else {
        //     $info = collect();
        //     $info->token = $response;
        //     $info->mensaje = 'la empresa no se guardo';
        //     return $info;
        // }
    }

    public function softInfo($id)
    {
        $data = enterprise::find($id);

        $enterprise = collect();

        $enterprise->id = $data->id;
        $enterprise->software_id = $data->software_id;
        $enterprise->software_pin = $data->software_pin;
        $enterprise->software_url = $data->software_url;

        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/software';
        $datos = Tools::http_post($urlPost, $enterprise);
        
        $data->last_software_response = $datos;
        $data->save();
        $info = collect();
        $info->response = $datos;
        if ($datos === true) {
            $info->mensaje = 'Respuesta de software positiva';
        }
        else {
            $info->mensaje = 'Respuesta de software negativa';
        }
        return $info;
    }

    public function productionNumbers($id) 
    {
        $data = enterprise::find($id);

        $enterprise = collect();
        $enterprise->nit = $data->nit;
        $enterprise->software_id = $data->software_id;

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

    public function enterpriseUpdate($id)
    {
        $data = enterprise::find($id);

        $enterprise = collect();
        $enterprise->id = $data->id;
        $enterprise->type_document_identification_id = $data->type_document_identification_id;
        $enterprise->type_environment_id = $data->type_environment_id;
        $enterprise->type_organization_id = $data->type_organization_id;
        $enterprise->type_regime_id = $data->type_regime_id;
        $enterprise->type_liability_id = $data->type_liability_id;
        $enterprise->business_name = $data->business_name;
        $enterprise->merchant_registration = $data->merchant_registration;
        $enterprise->municipality_id = $data->municipality_id;
        $enterprise->address = $data->address;
        $enterprise->phone = $data->phone;
        $enterprise->email = $data->email;

        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/'.$data->nit;
        $datos = Tools::http_put($urlPut, $enterprise);

        $info = collect();
        if ($datos === true) {
            $info->response = $datos;
            $info->mensaje = 'Actualización DIAN exitosa';
        }
        else {
            $info->mensaje = 'Actualización DIAN fallo';
        }
        
        return $info;
    }

    public function certificateUp(Request $request)
    {
        $data = enterprise::find($id);

        $enterprise = collect();
        $enterprise->id = $data->id;
        $enterprise->certificate = $data->certificate;
        $enterprise->certificate_password = $data->certificate_password;

        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/certificate';
        $datos = Tools::http_put($urlPut, $request);
        
        $data->last_certificate_response = $datos;
        $data->save();
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
