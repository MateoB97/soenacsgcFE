<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Terceros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terceros', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('business_name');
            $table->string('merchant_registration');
            $table->string('address');
            $table->integer('phone');
            $table->string('email');
            $table->integer('type_document_identification_id');
            $table->integer('type_organization_id');
            $table->integer('type_regime_id');
            $table->integer('type_liability_id');
            $table->string('software_id');
            $table->integer('software_pin');
            $table->string('software_url');
            $table->text('certificate');
            $table->string('certificate_password');
            $table->integer('type_environments');
            $table->integer('ceo_document');
            $table->text('last_software_response')->nullable();
            $table->text('last_certificate_response')->nullable();
            $table->text('last_update_enterprise_response')->nullable();
            $table->integer('nit')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enterprises');
    }
}
