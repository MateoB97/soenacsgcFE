<template>
    <div class="background-menu menu-lateral">
      <q-list>
        <q-expansion-item
          class="expansion-block"
          expand-separator
          icon="business_center"
          label="Empresas"
          expand-icon-class="text-white"
          :content-inset-level="0.5"
        >
          <q-card>
            <q-card-section>
              <router-link to="/enterprises" class="menuItem">Crear Empresa</router-link>
              <router-link to="/enterprises/adminEnterprise" class="menuItem">Administrar Empresas</router-link>
            </q-card-section>
          </q-card>
        </q-expansion-item>
        <q-expansion-item
          class="expansion-block"
          expand-separator
          icon="local_shipping"
          label="Generales"
          expand-icon-class="text-white"
          :content-inset-level="0.5"
        >
          <q-card>
            <q-card-section>
              <router-link to="/generals" class="menuItem">Generales</router-link>
            </q-card-section>
          </q-card>
        </q-expansion-item>
        <div class="q-mt-md row box-buttons-menu">
          <div v-if="$auth.check()" class="text-center col-12">
              Usuario: {{ $auth.user().name }}
          </div>
        </div>
        <div class="row q-col-gutter-md" style="padding:10px">
          <div v-if="$auth.check()" class="text-center col-6">
            <a href="./" @click.prevent="$auth.logout(), $router.push({ name: 'login' })"><q-btn class="btn-naranja">Salir</q-btn></a>
          </div>
        </div>
        <!-- <div class="row q-mt-lg">
          <img id="logo" alt="Quasar logo" style="width:300px" src="~assets/logo-supercarnes.png">
        </div> -->
      </q-list>
    </div>
</template>

<script>
import { globalFunctions } from 'boot/mixins.js'

export default {
  name: 'MenuPrincipal',
  data: function () {
    return {
    }
  },
  mixins: [globalFunctions],
  methods: {
  },
  created: function () {
  }
}
</script>

<style>
    .menuItem{
        display: block;
        text-decoration: none;
        color: #ffffff;
    }
    .menuItem:hover{
        color:#FF846E;
    }
    .expansion-block{
      background: #2E4DD4;
      color: #ffffff;
      font-family: poppins;
      font-weight: 400;
    }
    .sub-expansion-block{
      background: #2E4DD4;
      color: #ffffff;
    }
    .card
    .expansion-items{
      background-color: #ffffff;
    }
    .title-facturacion {
      color: black;
      text-align:center;
      margin-bottom: 0px;
      font-weight: 700
    }
    .v-money-menu{
      padding: 17px;
      border: none;
      border-bottom: 1px solid rgba(0,0,0,0.24);
      width: 100%;
    }
    .v-money-menu:focus{
      outline: none;
      border-bottom: 1px solid #027be3;
    }
    .v-money-label-menu{
      color: rgba(0,0,0,0.6);
      font-size: 16px;
      line-height: 20px;
      font-weight: 400;
      letter-spacing: 0.00937em;
      position: absolute;
      top: 16px;
    }
    .q-drawer__content.fit.scroll{
      background: #2E4DD4;
      color: #FFFFFF;
    }
    .menu-lateral .q-card{
      background: #2E4DD4;
    }
    .box-buttons-menu{
      border-top: 2px dashed #ffffff;
      padding-top: 15px;
    }
    .expansion-block .q-expansion-item__content,
    .expansion-block .q-expansion-item__content .q-expansion-item,
    .expansion-block .q-expansion-item__content .q-card,
    .expansion-block .q-expansion-item__content .q-expansion-item .q-card
    {
      background: #5A5AEA;
    }
    .sub-expansion-block .q-item__label{
      border-left: 5px solid #FFBC6E;
      padding-left: 5px;
    }
    .expansion-block .q-item{
      font-size: 17px;
    }
</style>
