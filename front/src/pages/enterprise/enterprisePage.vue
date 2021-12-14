<template>
  <div>
    <q-page padding>
        <h4>Crear empresas</h4>

        <div class="row q-col-gutter-md">
            <div class="col-3">
                <q-select v-model="storeItems.type_document_identification_id" use-input hide-selected fill-input option-label="name" :options="options.type_document_identifications" label="Tipo de documento de ID" option-disable="inactive" option-value="id" map-options emit-value input-debounce="0" @input="sendToParent()" />
            </div>
            <div class="col-3">
              <q-input type="number" v-model="storeItems.nit" label="NIT"/>
            </div>
            <div class="col-3">
              <q-select v-model="storeItems.type_organization_id" use-input hide-selected fill-input option-label="name" :options="options.type_organizations" label="Tipo de organización" option-disable="inactive" option-value="id" map-options emit-value input-debounce="0" @input="sendToParent()"/>
            </div>
            <div class="col-3">
              <q-select v-model="storeItems.type_regime_id" use-input hide-selected fill-input option-label="name" :options="options.type_regimes" label="Regimen" option-disable="inactive" option-value="id" map-options emit-value input-debounce="0" @input="sendToParent()"/>
            </div>
            <div class="col-3">
              <q-select v-model="storeItems.type_liability_id" use-input hide-selected fill-input option-label="name" :options="options.type_liabilities" label="Responsabilidad" option-disable="inactive" option-value="id" map-options emit-value input-debounce="0" @input="sendToParent()"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.business_name" label="Nombre del negocio"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.merchant_registration" label="Registro mercantil"/>
            </div>
            <div class="col-3">
              <q-select v-model="storeItems.municipality_id" use-input hide-selected fill-input option-label="name" :options="options.municipalities" label="Municipalidad" option-disable="inactive" option-value="id" map-options emit-value input-debounce="0" @input="sendToParent()"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.address" label="Dirección"/>
            </div>
            <div class="col-3">
              <q-input type="number" v-model="storeItems.phone" label="Telefono"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.email" label="Email"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.software_id" label="Software_ID"/>
            </div>
            <div class="col-3">
              <q-input type="number" v-model="storeItems.software_pin" label="Software_PIN"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.software_url" label="Software_URL"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.certificate" label="Certificado"/>
            </div>
            <div class="col-3">
              <q-input type="password" v-model="storeItems.certificate_password" label="Certificado_Pass"/>
            </div>
            <div class="col-3">
              <q-select v-model="storeItems.type_environments" use-input hide-selected fill-input option-label="name" :options="options.type_environments" label="Tipo_ambiente" option-disable="inactive" option-value="id" map-options emit-value input-debounce="0" @input="sendToParent()"/>
            </div>
            <div class="col-3">
              <q-input type="number" v-model="storeItems.ceo_document" label="Documento_Rep/legal"/>
            </div>
            <div class="col-3">
              <q-input type="text" v-model="storeItems.token" label="Token"/>
            </div>
        </div>
        <div class="row q-mt-md">
            <div class="col-2">
                <q-btn v-if="!showForUpdate" color="primary" v-on:click="globalValidate('guardar')" label="Guardar" />
                <q-btn v-if="showForUpdate" color="primary" v-on:click="globalValidate('guardar-edicion', storeItems.id)" label="Guardar Edición" />
            </div>
        </div>
        <div class="row q-mt-xl">
            <q-table
                title= "Listado de empresas"
                :data="tableData"
                :columns="columns"
                :filter="filter"
                :visible-columns="visibleColumns"
                :separator="separator"
                row-key="id"
                color="secondary"
                table-style="width:100%"
            >
                <template slot="top-right" slot-scope="props">
                    <q-input
                        hide-underline
                        color="secondary"
                        v-model="filter"
                        class="col-6"
                        debounce="500"
                    >
                      <template v-slot:append>
                        <q-icon name="search" />
                      </template>
                    </q-input>
                    <q-btn
                        flat round dense
                        :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                        @click="props.toggleFullscreen"
                    />
                </template>

                <q-td slot="body-cell-actions" slot-scope="props" :props="props">
                    <q-btn class="q-ml-xs btn-coral" icon-right="delete" @click="eliminarEmpresa(props.value)">Eliminar</q-btn>
                    <q-btn class="q-ml-xs btn-naranja" icon-right="edit" @click="editarEmpresa(props.value)">Editar</q-btn>
                </q-td>
            </q-table>
        </div>
    </q-page>
  </div>
</template>

<script>
import { globalFunctions } from 'boot/mixins.js'
// const axios = require('axios')

export default {
  name: 'enterprisePage',
  created: function () {
    this.globalGetForSelect('api/enterprises/soenac/soenacCampos', 'options') // Request, soenac select
    this.globalGetItems() // Mixins
  },
  data: function () {
    return {
      urlAPI: 'api/enterprises',
      storeItems: { // Formulario
      },
      tableData: [], // Respuesta, tabla
      options: [], // Respuesta, listados selct
      showForUpdate: false, // Boton grardar--guardar edición
      // component table
      separator: 'horizontal',
      filter: '',
      columns: [
        { name: 'id', required: true, label: 'id', align: 'left', field: 'id', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'nit', required: true, label: 'NIT', align: 'left', field: 'nit', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'business_name', required: true, label: 'Nombre', align: 'left', field: 'business_name', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'merchant_registration', required: true, label: 'Registro mercantil', align: 'left', field: 'merchant_registration', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'address', required: true, label: 'Dirección', align: 'left', field: 'address', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'phone', required: true, label: 'Telefono', align: 'left', field: 'phone', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'email', required: true, label: 'E-mail', align: 'left', field: 'email', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'type_document_identification_id', required: true, label: 'Tipo de documento', align: 'left', field: 'type_document_identification_id', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'type_organization_id', required: true, label: 'Tipo de organización', align: 'left', field: 'type_organization_id', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'type_regime_id', required: true, label: 'Regimen', align: 'left', field: 'type_regime_id', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'type_liability_id', required: true, label: 'Responsabilidad', align: 'left', field: 'type_liability_id', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'software_id', required: true, label: 'Id software', align: 'left', field: 'software_id', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'software_pin', required: true, label: 'Pin software', align: 'left', field: 'software_pin', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'software_url', required: true, label: 'Url software', align: 'left', field: 'software_url', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'token', required: true, label: 'Token', align: 'left', field: 'token', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'certificate_password', required: true, label: 'Pass Certificado', align: 'left', field: 'certificate_password', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'type_environments', required: true, label: 'ambiente', align: 'left', field: 'type_environments', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'last_software_response', required: true, label: 'respuesta de software', align: 'left', field: 'last_software_response', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'last_certificate_response', required: true, label: 'respuesta de certificado', align: 'left', field: 'last_certificate_response', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'last_update_enterprise_response', required: true, label: 'respuesta de actualización', align: 'left', field: 'last_update_enterprise_response', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'ceo_document', required: true, label: 'Doc rep legal', align: 'left', field: 'ceo_document', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'municipality_id', required: true, label: 'Municipalidad', align: 'left', field: 'municipality_id', sortable: true, classes: 'my-class', style: 'width: 200px' },
        { name: 'actions', required: true, label: 'Acciones', align: 'left', field: 'id', sortable: true, classes: 'my-class', style: 'width: 200px' }
      ],
      visibleColumns: ['id', 'nombre', 'actions']
    }
  },
  mixins: [globalFunctions],
  methods: {
    postSave () {
    },
    // Convirtiendo data a formato permitido para evitar null
    preSave () {
      this.storeItems.last_software_response = this.storeItems.last_software_response ? this.storeItems.last_software_response : ''
      this.storeItems.last_certificate_response = this.storeItems.last_certificate_response ? this.storeItems.last_certificate_response : ''
      this.storeItems.last_update_enterprise_response = this.storeItems.last_update_enterprise_response ? this.storeItems.last_update_enterprise_response : ''
      this.storeItems.municipality_id = this.storeItems.municipality_id ? this.storeItems.municipality_id : ''
      this.storeItems.token = this.storeItems.token ? this.storeItems.token : ''
    },
    postEdit () {
    },
    sendToParent () { // mixins
    },
    eliminarEmpresa (id) {
      this.$q.dialog({
        message: '¿ Quieres eliminar esta fila ?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.globalValidate('eliminar', id)
      }).onCancel(() => {
        this.$q.notify('Cancelado...')
      }).onDismiss(() => {
      })
    },
    editarEmpresa (id) {
      this.globalValidate('editar', id)
    }
  },
  computed: {
  }
}
</script>

<style>
    .q-table__container{
        width: 100%;
    }
</style>
