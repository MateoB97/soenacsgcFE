<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tools;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class enterprise extends Model
{
    protected $table = 'enterprises';

    protected $fillable = [
        'business_name',
        'merchant_registration',
        'address',
        'phone',
        'email',
        'type_document_identification_id',
        'type_organization_id',
        'type_regime_id',
        'type_liability_id',
        'software_id',
        'software_pin',
        'software_url',
        'certificate',
        'certificate_password',
        'type_environments',
        'ceo_document',
        'last_software_response',
        'last_certificate_response',
        'last_update_enterprise_response',
        'nit',
        'municipality_id'
    ];

    protected $columns = array(
        'business_name',
        'merchant_registration',
        'address',
        'phone',
        'email',
        'type_document_identification_id',
        'type_organization_id',
        'type_regime_id',
        'type_liability_id',
        'software_id',
        'software_pin',
        'software_url',
        'certificate',
        'certificate_password',
        'type_environments',
        'ceo_document',
        'last_software_response',
        'last_certificate_response',
        'last_update_enterprise_response',
        'nit',
        'municipality_id'
    );

    public function getDateFormat()
    {
        return Tools::dateTimeSql();
    }

    // public static function postData($dateIni, $dateFin, $dateFormat){
    //     return
    //     DB::CONVERT ($datetime, 'yourdate') FROM [$enterprises] WHERE ISDATE ('yourdate') = 1;
    // }
    public static function getData(){
        return
        DB::select("select * from enterprises");
    }

    // protected $events = [
    //     'updated' => enterpriseUpdated::class,
    // ];
}

// class Post extends Model
// {
//     use BroadcastsEvents, HasFactory;
//     /**
//      * Get the user that the post belongs to.
//      */
//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }
//     /**
//      * Get the channels that model events should broadcast on.
//      *
//      * @param  string  $event
//      * @return \Illuminate\Broadcasting\Channel|array
//      */
//     public function broadcastOn($event)
//     {
//         return [$this, $this->user];
//     }
// }
