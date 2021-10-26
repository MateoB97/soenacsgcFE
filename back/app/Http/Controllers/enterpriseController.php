<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\enterprise;
use App\general;
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

    public function resolutions($request) {

        $resolutionData = array();

        $resolutionData['type_document_id'] = 1;
        $resolutionData['from'] = 990000000;
        $resolutionData['to'] = 995000000;
        $resolutionData['resolution'] = 18760000001;
        $resolutionData['resolution_date'] = "0001-01-01";
        $resolutionData['technical_key'] = "fc8eac422eba16e22ffd8c6f94b3f40a6e38162c";
        $resolutionData['date_from'] = "2019-01-19";
        $resolutionData['date_to'] = "2030-01-19";
        $resolutionData['prefix'] = "SETP";

        $url = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/resolutions';

        if ($enterprise->type_environments === 1) {
            $authorization = "Authorization: Bearer ". $general->masterToken;
        } else if ($enterprise->type_environments === 2){
            $authorization = "Authorization: Bearer ". $enterprise->token;
        }

        $datos = Tools::http_post($url, $resolutionData, $authorization);

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

        $enterprise = enterprise::find($id);

        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/' . $enterprise->nit;

        $data = array();

        $data['type_document_identification_id']   = $enterprise->type_document_identification_id;
        $data['type_organization_id']              = $enterprise->type_organization_id;
        $data['type_regime_id']                    = $enterprise->type_regime_id;
        $data['type_liability_id']                 = $enterprise->type_liability_id;
        $data['business_name']                     = $enterprise->business_name;
        $data['merchant_registration']             = $enterprise->merchant_registration;
        $data['municipality_id']                   = $enterprise->municipality_id;
        $data['address']                           = $enterprise->address;
        $data['phone']                             = $enterprise->phone;
        $data['email']                             = $enterprise->email;

        $authorization = "Authorization: Bearer DjSeSssNuNaE3ihrqcWLIMUsHk7XMwWQm5vgp7PR8JPmVcIhHbWI9zFrcMoNBVIUhg51OouaCVUZYTwO";

        $response = Tools::http_post($urlPost, $data, $authorization);

        $enterprise->token = $response; // validar sin errores para no introducir en bd el error

        $enterprise->save();

        return $response;
    }

    public function softInfo($id)
    {
        $enterprise = enterprise::find($id);

        $data = array();

        $data['software_id']  = $enterprise->software_id;
        $data['software_pin'] = $enterprise->software_pin;
        $data['software_url'] = $enterprise->software_url;

        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/software';

        if ($enterprise->type_environments === 2) {
            $authorization = "Authorization: Bearer ". $general->masterToken;
        } else if ($enterprise->type_environments === 1){
            $authorization = "Authorization: Bearer ". $enterprise->token;
        }

        $response = Tools::http_post($urlPost, $data, $authorization);

        $enterprise->last_software_response = $response;

        $enterprise->save();

        $info = collect();
        $info->response = $respoonse;
        if ($datos === true) {
            $info->mensaje = 'Positivo';
        }
        else {
            $info->mensaje = 'Negativo';
        }
        return $info;
    }

    public function productionNumbers($id)
    {
        $enterprise = enterprise::find($id);
        $general = general::all()->first();

        $data = array();
        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/numbering/range' . '/' . $enterprise->nit . '/' . $enterprise->nit . '/' . $enterprise->software_id;

        if ($enterprise->type_environments === '1') {
            $authorization = "Authorization: Bearer ". $general->masterToken;
        } else if ($enterprise->type_environments === '2'){
            $authorization = "Authorization: Bearer ". $enterprise->token;
        }

        $datos = Tools::http_post($urlPost, $data, $authorization);
        // dd(json_decode($datos));
        // json_encode($datos);
        // // $response = $datos['data']['responseDian']['Envelope']['Body']['GetNumberingRangeResponse']['GetNumberingRangeResult']['ResponseList'];
        // $response = $datos->data->responseDian->Envelope->Body->GetNumberingRangeResponse->GetNumberingRangeResult->ResponseList;
        // dd($response);
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

    public function enterpriseUpdating($id)
    {
        $enterprise = enterprise::find($id);

        $data = array();

        $data['id']                              = $enterprise->id;
        $data['type_document_identification_id'] = $enterprise->type_document_identification_id;
        $data['type_environment_id']             = $enterprise->type_environment_id;
        $data['type_organization_id']            = $enterprise->type_organization_id;
        $data['type_regime_id']                  = $enterprise->type_regime_id;
        $data['type_liability_id']               = $enterprise->type_liability_id;
        $data['business_name']                   = $enterprise->business_name;
        $data['merchant_registration']           = $enterprise->merchant_registration;
        $data['municipality_id']                 = $enterprise->municipality_id;
        $data['address']                         = $enterprise->address;
        $data['phone']                           = $enterprise->phone;
        $data['email']                           = $enterprise->email;

        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/'.$enterprise->nit;

        if ($enterprise->type_environments === 2) {
            $authorization = "Authorization: Bearer ". $general->masterToken;
        } else if ($enterprise->type_environments === 1){
            $authorization = "Authorization: Bearer ". $enterprise->token;
        }

        $datos = Tools::http_put($urlPut, $data, $authorization);

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

    public function certificateUp($id)
    {
        $enterprise = enterprise::find($id);

        $data = array();

        $data['certificate']          = $enterprise->certificate;
        $data['certificate_password'] = $enterprise->certificate_password;

        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/certificate';

        if ($enterprise->type_environments === 2) {
            $authorization = "Authorization: Bearer ". $general->masterToken;
        } else if ($enterprise->type_environments === 1){
            $authorization = "Authorization: Bearer ". $enterprise->token;
        }

        $response = Tools::http_put($urlPut, $data, $authorization);

        $enterprise->last_certificate_response = $response;
        $enterprise->save();
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
