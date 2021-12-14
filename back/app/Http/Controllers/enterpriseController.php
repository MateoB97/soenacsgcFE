<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jobs\requestApi;
use App\enterprise;
use App\general;
use App;
use App\Http\Exports\FiltroApi;
use App\Jobs\requestApi as JobsRequestApi;
use App\Tools;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use stdClass;
    /** --- CONTIENE DOS VISTAS ADMINENTERPRISE & ENTERPRISE --- */
class enterpriseController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /** Petición para tabla */
    public function index()
    {
        return $index = enterprise::all();
    }
    /** Petición para select */
    public function adminIndex()
    {
        $arrayBuckets = array();
        $index = enterprise::all()->toArray();
        foreach ($index as $key => $value) {
            $arr = array();
            foreach ($value as $key2 => $value2) {
                if ($key2 === 'business_name' || $key2 === 'nit' || $key2 === 'id') {
                    array_push($arr, $value2);
                }
            }
            $unir = implode("-", $arr);
            array_push($arrayBuckets, $unir);
        }
        // return $index;
        return $arrayBuckets;
    }
    /** Consulta api listados soenac para selects */
    public function soenacCampos()
    {

        $structureToFilter = ['type_document_identifications', 'type_organizations', 'type_regimes', 'type_liabilities','municipalities', 'type_environments'];
        $fieldsToFilter = ['name','id'];
        $url = "https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/listings";

        return json_encode(Tools::filterApi($url, $fieldsToFilter , $structureToFilter));
    }
    /** Consulta BD empresas a soenac */
    public function verEmpresa($id)
    {
        $enterprise = enterprise::find($id);

        $urlGet = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/' . strval($enterprise->nit);

        if ($enterprise->token !== null) {
            $authorization = "Authorization: Bearer ".$enterprise->token;
            $response = Tools::http_get($urlGet, $authorization);
            if (gettype($response) === "string") {
                if ( preg_match('/{"message":"The given data was invalid.",/', $response) === 1 ) {
                    return json_encode($response); // no se pudo crear
                } else if (preg_match('/{"message":"The given data was invalid.",/', $response) === 0) {
                    return json_encode($response); // el token fue creado y guardado en BD
                }
            } else if (gettype($response) === "array" || gettype($response) === "object") {
                return json_encode($response);
            }
        } else if ($enterprise->token === null) {
            $response = new stdClass();
            $response->Error = 'No existe token para consultar en soenac';
            return json_encode($response);
        }
    }
    /** Consulta soft info soenac */
    public function softInfo($id)
    {
        $enterprise = enterprise::find($id);
        $general = general::all()->first();

        $data = new stdClass();

        $data->id  = $enterprise->software_id;
        $data->pin = $enterprise->software_pin;
        $data->url = $enterprise->software_url;

        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/software';

        $authorization = 'Authorization: Bearer '.$enterprise->token;

        // if ($enterprise->type_environments === 2) {
        //     $authorization .= $general->masterToken;
        // } else if ($enterprise->type_environments === 1) {
        //     $authorization .= $enterprise->token;
        // }

        $response = json_decode(Tools::http_put($urlPost, $data, $authorization));

        $enterprise->last_software_response = $response->message;

        $enterprise->save();

        // if ($datos === true) {
        //     $info->mensaje = 'Positivo';
        // }
        // else {
        //     $info->mensaje = 'Negativo';
        // }
        return json_encode($response);
    }
    /** Consulta prefijos asociados */
    public function productionNumbers($id)
    {
        $enterprise = enterprise::find(intval($id));
        $general = json_decode(general::all()->first(),false);

        $data = array();
        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/numbering/range' . '/' . $enterprise->nit . '/' . $enterprise->nit . '/' . $enterprise->software_id;

        $authorization = 'Authorization: Bearer ';

        if ($enterprise->type_environments === "2") {
            $authorization .= $general->masterToken;
        } else if ($enterprise->type_environments === "1"){
            $authorization .= $enterprise->token;
        }

        $datos = json_encode(Tools::http_post($urlPost, $data, $authorization));

        return $datos;
    }
    /** Creación resolución en hab-DIAN */
    public function resolucionPrueba($id)
    {
        $enterprise = enterprise::find(intval($id));

        $datos = [["tp_dc"=>1,"prefix"=>"SETP"],["tp_dc"=>5,"prefix"=>"SENC"],["tp_dc"=>6,"prefix"=>"SEND"]];
        $disk = 'local';
        $name = 'certificadoHab.txt';

        if (Storage::disk($disk)->exists($name)) {
            $eliminar = Storage::delete($name);
        }

        for ($i=0; $i < 3; $i++) {

            $data = new stdClass();

            $data->from             = 990000000;
            $data->to               = 995000000;
            $data->resolution       = 18760000001;
            $data->resolution_date  = "0001-01-01";
            $data->technical_key    = "fc8eac422eba16e22ffd8c6f94b3f40a6e38162c";
            $data->date_from        = "2019-01-19";
            $data->date_to          = "2030-01-19";
            $data->type_document_id = $datos[$i]["tp_dc"];
            $data->prefix           = $datos[$i]["prefix"];

            $urlPost = "https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/resolution";

            $authorization = 'Authorization: Bearer '.$enterprise->token;

            $response = Tools::http_post($urlPost, $data, $authorization);

            if (Storage::disk($disk)->exists($name)) {
                $escribir = Storage::append($name, $response);
                // dd($escribir);
            } else {
                $crear = Storage::disk($disk)->put($name, $response, 'public');
                // dd($crear);
            }

            if($i>=2) {
                $contents = Storage::get($name);

                return $contents;
            }

        }
    }
    /** Creación facturas de prueba---revisar */
    public function facPruebas($id, $dataNumber = 0)
    {
        $enterprise = enterprise::find(intval($id));

        $idSoft = $enterprise->software_id;

        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/invoice/'.$idSoft;

        $data = array();

        if ($dataNumber != 0) {
            $data["number"] = $dataNumber;
        } else {
            $data["number"]    = 990000000;
        }
        $data["type_document_id"] = 1;
        $data["customer"] = (object) [
            "identification_number" => 1094925334,
            "name" => "Frank Aguirre",
            "email" => "faguirre@soenac.com",
            "municipality_id" => 1
        ];
        $data["legal_monetary_totals"]  = (object) [
            "line_extension_amount"  => "300000.00",
            "tax_exclusive_amount"  => "300000.00",
            "tax_inclusive_amount"  => "357000.00",
            "allowance_total_amount"  => "0.00",
            "charge_total_amount"  => "0.00",
            "payable_amount"  => "357000.00"
        ];
        $data["invoice_lines"]  = [(object)[
            "unit_measure_id" => 642,
            "invoiced_quantity" => "1.000000",
            "line_extension_amount" => "300000.00",
            "free_of_charge_indicator" => false,
            "tax_totals" => [(object)[
               "tax_id" => 1,
               "tax_amount" => "57000.00",
               "taxable_amount" => "300000.00",
               "percent" => "19.00"
            ]],
            "description" => "Base para TV",
            "code" => "6543542313534",
            "type_item_identification_id" => 4,
            "price_amount" => "300000.00",
            "base_quantity" => "1.000000"
        ]];

        $authorization = 'Authorization: Bearer '.$enterprise->token;

        $factura = Tools::http_post($urlPost, $data, $authorization);

        $disk = 'local';
        $name = 'codigoZipConfirm.txt';

        // if (Storage::disk($disk)->exists($name)) {
        //     $eliminar = Storage::delete($name);
        // }

        $crear = Storage::disk($disk)->put($name, $factura, 'public');

        $factura = json_decode($factura);

        if ($factura->zip_key) {
            $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/status/zip/'.$factura->zip_key;
            $dataZip = array();
            $codigoZip = json_decode(Tools::http_post($urlPost, $dataZip, $authorization));
            // dd($codigoZip);

            if ($codigoZip->is_valid === true) {
                $mensaje = new stdClass();
                $suma = $data["number"];
                $suma++;
                $mensaje->status_description = $codigoZip->status_description;
                $mensaje->status_message = $codigoZip->status_message;
                $mensaje->zip_key = $codigoZip->zip_key;
                $mensaje->uuid = $codigoZip->uuid;
                $mensaje->number = $codigoZip->number;
                $escribir = Storage::append($name, json_encode($mensaje));
                if ($suma === 990000010) {
                    $contents = Storage::get($name);
                    return $contents;
                } else {
                    $this->facPruebas($id, $suma);
                }
            } else if ($codigoZip->is_valid === false) {
                $mensaje = new stdClass();
                $mensaje->status_description = $codigoZip->status_description;
                $mensaje->status_message = $codigoZip->status_message;
                $mensaje->zip_key = $codigoZip->zip_key;
                $mensaje->uuid = $codigoZip->uuid;
                $mensaje->number = $codigoZip->number;
                $escribir = Storage::append($name, json_encode($mensaje));
                $contents = Storage::get($name);

                return $contents;
            }
        }

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
    /** Crea nueva empresa en BD */
    public function store(Request $request)
    {
        $nuevaEmpresa = new enterprise ($request->all());
        $nuevaEmpresa->save();
        // $tipo = enterprise::find();
        return 'done';

    }
    /** Creación empresa soenac */
    public function confirmEnterpriseDian($id)
    {

        $enterprise = enterprise::find($id);
        $general = general::all()->first();

        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/' . $enterprise->nit;

        $data = new stdClass();

        $data->type_document_identification_id   = $enterprise->type_document_identification_id;
        $data->type_organization_id              = $enterprise->type_organization_id;
        $data->type_regime_id                    = $enterprise->type_regime_id;
        $data->type_liability_id                 = $enterprise->type_liability_id;
        $data->business_name                     = $enterprise->business_name;
        $data->merchant_registration             = $enterprise->merchant_registration;
        $data->municipality_id                   = $enterprise->municipality_id;
        $data->address                           = $enterprise->address;
        $data->phone                             = $enterprise->phone;
        $data->email                             = $enterprise->email;

        $authorization = "Authorization: Bearer DjSeSssNuNaE3ihrqcWLIMUsHk7XMwWQm5vgp7PR8JPmVcIhHbWI9zFrcMoNBVIUhg51OouaCVUZYTwO";
        $authorization = "Authorization: Bearer ".$general->masterToken;

        $response = Tools::http_post($urlPost, $data, $authorization);
        // $response = 'confirmEntreprise';
        if ( preg_match('/{"message":"The given data was invalid.",/', $response) === 1 ) {
            $enterprise->token = null;
            $enterprise->save();
            return $response; // no se pudo crear
        } else if (preg_match('/{"message":"The given data was invalid.",/', $response) === 0) {
            $response = json_decode($response);
            $enterprise->token = $response->token;
            $enterprise->save();
            $response = json_encode($response); // revisar si esto si llega bien
            return $response; // el token fue creado y guardado en BD
        }
    }
    /** Creación de la resolucion */
    public function resolutions($r)
    {
        // dd($r);
        $request = json_decode($r,false);
        $enterprise = enterprise::find(intval($request->id));
        // $general = json_decode(general::all()->first(),false);

        $resolutionData = new stdClass();

        $resolutionData->type_document_id  = intval($request->type_document_id);
        $resolutionData->from              = intval($request->FromNumber);
        $resolutionData->to                = intval($request->ToNumber);
        $resolutionData->resolution        = intval($request->ResolutionNumber);
        $resolutionData->resolution_date   = $request->ResolutionDate;
        $resolutionData->technical_key     = $request->TechnicalKey;
        $resolutionData->date_from         = $request->ValidDateFrom;
        $resolutionData->date_to           = $request->ValidDateTo;
        $resolutionData->prefix            = $request->Prefix;

        $urlPost = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/resolution';

        $authorization = 'Authorization: Bearer '.$enterprise->token;

        // if ($enterprise->type_environments === 2) {
        //     $authorization .= $general->masterToken;
        // } else if ($enterprise->type_environments === 1){
        //     $authorization .= $enterprise->token;
        // }
        $response = json_encode(Tools::http_post($urlPost, $resolutionData, $authorization));

        return $response;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\enterprise  $enterprise
     * @return \Illuminate\Http\Response
     */
    /** Mostrar para editar en vista principal */
    public function show(enterprise $enterprise, $id)
    {
        return $index = $enterprise->find($id);
    }
    /** Data para dialogos y tabs (adminData) */
    public function showAdmin($id)
    {
        $empresa = enterprise::where('id', '=', $id)->get()->first();
        return $empresa;
    }
    /** Descarga documento resultados creación resolución */
    public function downloadTxt ($r)
    {
        $request = json_decode($r,false);

        // return $request;
        switch ($request->type_document_id) {
            case 1:
                $request->tipoDocNom = 'Factura';
                break;
            case 5:
                $request->tipoDocNom = 'Nota credito';
                break;
            case 6:
                $request->tipoDocNom = 'Nota debito';
                break;
        }

        $name = $request->business_name.'_'.$request->prefix.'_'.$request->to.'_'.$request->from.'_'.$request->created_at.'.txt';
        $disk = 'local';

        $content = 'Nombre: '.$request->business_name.PHP_EOL;
        $content .= 'Token: '.$request->token.PHP_EOL;
        $content .= 'Tipo de documento: '.$request->tipoDocNom.PHP_EOL;
        $content .= 'Datos de la resolución: '.PHP_EOL;
        $content .= 'Tipo de documento '.$request->type_document_id.PHP_EOL;
        $content .= 'Prefijo '.$request->prefix.PHP_EOL;
        $content .= 'Resolución '.$request->resolution.PHP_EOL;
        $content .= 'Fecha resolución '.$request->resolution_date.PHP_EOL;
        $content .= 'Clave técnica '.$request->technical_key.PHP_EOL;
        $content .= 'Consecutivo desde '.$request->from.PHP_EOL;
        $content .= 'Consecutivo hasta '.$request->to.PHP_EOL;
        $content .= 'Fecha desde '.$request->date_from.PHP_EOL;
        $content .= 'Actualizado '.$request->updated_at.PHP_EOL;
        $content .= 'Creado '.$request->created_at.PHP_EOL;
        $content .= 'ID '.$request->id.PHP_EOL;
        $content .= 'Numero '.$request->number.PHP_EOL;
        $content .= 'Consecutivo siguiente '.$request->next_consecutive;

        $guardado = Storage::disk($disk)->put($name, $content, 'public');

        $url = Storage::disk($disk)->getDriver()->getAdapter()->applyPathPrefix($name);

        $headers = [
            'Content-Type: application/txt',
        ];
        $descarga = response()->download( $url, $name, $headers);
        return $descarga;
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
    /** Actualizar en BD */
    public function update(Request $request, $id)
    {
        $editarEmpresa = enterprise::find($id);
        $editarEmpresa->fill($request->all());
        $editarEmpresa->save();
        return 'done';
    }
    /** Actualizar en soenac */
    public function enterpriseUpdating($id)
    {
        $enterprise = enterprise::find(intval($id));

        $data = new stdClass();

        $data->id                              = $enterprise->id;
        $data->type_document_identification_id = $enterprise->type_document_identification_id;
        $data->type_environment_id             = $enterprise->type_environments;
        $data->type_organization_id            = $enterprise->type_organization_id;
        $data->type_regime_id                  = $enterprise->type_regime_id;
        $data->type_liability_id               = $enterprise->type_liability_id;
        $data->business_name                   = $enterprise->business_name;
        $data->merchant_registration           = $enterprise->merchant_registration;
        $data->municipality_id                 = $enterprise->municipality_id;
        $data->address                         = $enterprise->address;
        $data->phone                           = $enterprise->phone;
        $data->email                           = $enterprise->email;

        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/'.$enterprise->nit;

        $authorization = 'Authorization: Bearer '.$enterprise->token;

        $response = Tools::http_put($urlPut, $data, $authorization);

        return $response;
    }
    /** Actualizar certificado */
    public function certificateUp($id)
    {
        $enterprise = enterprise::find(intval($id));

        $data = new stdClass();

        $data->certificate   = $enterprise->certificate;
        $data->password      = $enterprise->certificate_password;

        $urlPut = 'https://supercarnes-jh.apifacturacionelectronica.xyz/api/ubl2.1/config/certificate';

        $authorization = 'Authorization: Bearer '.$enterprise->token;

        $response = json_decode(Tools::http_put($urlPut, $data, $authorization));

        $enterprise->last_certificate_response = $response->message;
        $enterprise->save();
        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\enterprise  $enterprise
     * @return \Illuminate\Http\Response
     */
    /** Eliminar de BD */
    public function destroy($id)
    {
        enterprise::where('id', $id)->delete();
        return 'done';
    }
    /** Testing */
    // public function testing() {
    //    return Carbon::now();
    // }

}
