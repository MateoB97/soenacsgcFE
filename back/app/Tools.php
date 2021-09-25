<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    public static function dateTimeSql(){
		// return 'Y-m-d H:i:s';
		return 'Y-d-m H:i:s.v';
	}

    public static function  http_get($url){

        $ch = curl_init();

        $curlConfig = [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_RETURNTRANSFER => true
        ];

        curl_setopt_array($ch, $curlConfig);

        $response = curl_exec($ch);

        $response = json_decode($response, true);
        curl_close($ch);

        return $response;
    }

    public static function http_post($url, $body){

        $ch = curl_init();

		$curlConfig = [
	        CURLOPT_URL            => $url,
	        CURLOPT_CUSTOMREQUEST  => "POST",
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_HTTPHEADER => TRUE,
	        CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Accept:application/json'),
	        CURLOPT_POSTFIELDS => json_encode($body)
	    ];

	    curl_setopt_array($ch, $curlConfig);

	    $response = curl_exec($ch);

		curl_close($ch);

        return $response;
    }

    public static function http_put($url, $body){

        $ch = curl_init();

		$curlConfig = [
	        CURLOPT_URL            => $url,
	        CURLOPT_CUSTOMREQUEST  => "PUT",
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_HTTPHEADER => TRUE,
	        CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Accept:application/json'),
	        CURLOPT_POSTFIELDS => json_encode($body)
	    ];

	    curl_setopt_array($ch, $curlConfig);

	    $response = curl_exec($ch);

		curl_close($ch);

        return $response;
    }

    public static function filterApi ($url, $fieldsToFilter, $structureToFilter) 
    {
        $datos = Tools::http_get($url);
        $arrayBuckets = array();
        foreach ($structureToFilter as $v) {

            foreach($datos[$v] as $item){

                $newItems = array();
                foreach($fieldsToFilter as $field){
                    $newItems[$field] = $item[$field];
                }
                
                $arrayBuckets[$v][] = $newItems;
            }
        }
        return $arrayBuckets;
    }

    public static function curlTest ()
    {
        json_encode(
            [
                "metodo" => $_SERVER["REQUEST_METHOD"],
                "encabezados" => getallheaders(),
                "datos" => file_get_contents("php://input"),
                "post" => $_POST,
                "cadena_consulta" => $_GET
            ],
            JSON_PRETTY_PRINT
        );
    }


}