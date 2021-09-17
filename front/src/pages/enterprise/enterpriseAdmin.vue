<template>
    <div>
      <q-page class="q-gutter-md" padding>
          <h4>Administrar empresas</h4>
          <div class="row q-col-gutter-md">
              <div class="col-3">
                  <q-select v-model="selectData" use-input hide-selected fill-input option-label="business_name" :options="options" label="Selecciona empresa" option-disable="inactive" option-value="id" map-options emit-value input-debounce="0" @input="sendToParent()"/>
              </div>
          </div>
          <div v-if="adminData !== 'null'">
            <div class="row q-col-gutter-md">
          <div class="col-2">
            <q-btn class="q-ml-xs btn-limon" @click="softInfo()">Subir información de software</q-btn>
          </div>
          <div class="col-2">
            <q-btn class="q-ml-xs" color="cyan-5" @click="certificateUp()">Subir certificado</q-btn>
          </div>
          <div class="col-2">
            <q-btn class="q-ml-xs" color="orange-5" @click="enterpriseUpdate()">Actualizar empresa</q-btn>
          </div>
          <div class="col-2">
            <q-btn class="q-ml-xs" color="teal-5" @click="resolutions()">Ver resoluciones</q-btn>
          </div>
          <div class="col-2">
            <q-btn class="q-ml-xs btn-limon" @click="productionNumbers()">Ver numeros de produccion</q-btn>
          </div>
        </div>
            <h5>Generales</h5>
                <div class="row q-col-gutter-md">
                  <div class="col-3">
                      <p class="parrafoLabel">Nombre de la empresa</p>
                      <p>{{ adminData.business_name}}</p>
                    </div>
                    <div class="col-3">
                      <p class="parrafoLabel">Tipo de documento</p>
                      <p>{{ adminData.type_document_identification_id}}</p>
                    </div>
                    <div class="col-3">
                      <p class="parrafoLabel">NIT</p>
                      <p>{{ adminData.nit}}</p>
                    </div>
                    <div class="col-3">
                        <p class="parrafoLabel">Dirección</p>
                        <p>{{ adminData.address}}</p>
                    </div>
                    <div class="col-3">
                      <p class="parrafoLabel">Telefono</p>
                      <p>{{ adminData.phone}}</p>
                    </div>
                    <div class="col-3">
                      <p class="parrafoLabel">Correo electronico</p>
                      <p>{{ adminData.email}}</p>
                    </div>
                    <div class="col-3">
                      <p class="parrafoLabel">Documento del representante</p>
                      <p>{{ adminData.ceo_document}}</p>
                    </div>
                </div>
                <h5>Software</h5>
                <div class="row q-col-gutter-md">
                   <div class="col-3">
                     <p class="parrafoLabel">Software ID</p>
                      <p>{{ adminData.software_id}}</p>
                    </div>
                   <div class="col-3">
                     <p class="parrafoLabel">Software PIN</p>
                      <p>{{ adminData.software_pin}}</p>
                   </div>
                    <div class="col-3">
                      <p class="parrafoLabel">Software URL</p>
                      <p>{{ adminData.software_url}}</p>
                    </div>
                    <div class="col-3">
                      <p class="parrafoLabel">Respuesta del software</p>
                      <p>{{ adminData.last_software_response}}</p>
                    </div>
                </div>
                <h5>Certificado</h5>
                <div class="row q-col-gutter-md">
                  <div class="col-3">
                    <p class="parrafoLabel">Certificado</p>
                    <p>{{ adminData.certificate}}</p>
                  </div>
                  <div class="col-3">
                    <p class="parrafoLabel">Contraseña del certificado</p>
                    <p>{{ adminData.certificate_password}}</p>
                  </div>
                  <div class="col-3">
                    <p class="parrafoLabel">Respuesta del certificado</p>
                    <p>{{ adminData.last_certificate_response}}</p>
                  </div>
                </div>
            </div>
      </q-page>
    </div>
</template>

<script>
import { globalFunctions } from 'boot/mixins.js'
const axios = require('axios')

export default {
  name: 'adminEnterprises',
  created: function () {
    this.globalGetForSelect('api/enterprises/admin/adminEnterprises', 'options')
  },
  mounted: function () {
    // this.globalGetItems()
    // console.log(this.tableData)
  },
  beforeUpdate: function () {
    // this.globalGetItems()
    // console.log(this.tableData)
  },
  updated: function () {
    // this.globalGetItems()
    // console.log(this.tableData)
  },
  data: function () {
    return {
      urlAPI: 'api/enterprises',
      selectData: {
      },
      adminData: 'null',
      options: null
    }
  },
  mixins: [globalFunctions],
  methods: {
    postSave () {
    },
    preSave () {
    },
    postEdit () {
    },
    async sendToParent () {
      // this.$q.loading.show()
      this.selectData = this.selectData.split('-')
      let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/admin/showAdmin' + '/' + this.selectData[1])
      this.adminData = data.data
      console.log(this.adminData)
    },
    async sendToParent2 () {
      console.log('hola')
    },
    async softInfo () {
      this.$q.loading.show()
      let enterprise = {}
      enterprise.id = this.adminData.id
      enterprise.software_id = this.adminData.software_id
      enterprise.software_pin = this.adminData.software_pin
      enterprise.software_url = this.adminData.software_url
      console.log(enterprise)
      try {
        let data = await axios.post(this.$store.state.jhsoft.url + 'api/enterprises/soenac/softInfo', enterprise)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async certificateUp () {
      this.$q.loading.show()
      let enterprise = {}
      enterprise.id = this.adminData.id
      enterprise.certificate = this.adminData.certificate
      enterprise.certificate_password = this.adminData.certificate_password
      try {
        let data = await axios.put(this.$store.state.jhsoft.url + 'api/enterprises/certificateUp' + '/' + enterprise.id, enterprise)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async enterpriseUpdate () {
      this.$q.loading.show()
      let enterprise = {}
      enterprise.id = this.adminData.id
      enterprise.type_document_identification_id = this.adminData.type_document_identification_id
      enterprise.type_environment_id = this.adminData.type_environment_id
      enterprise.type_organization_id = this.adminData.type_organization_id
      enterprise.type_regime_id = this.adminData.type_regime_id
      enterprise.type_liability_id = this.adminData.type_liability_id
      enterprise.business_name = this.adminData.business_name
      enterprise.merchant_registration = this.adminData.merchant_registration
      enterprise.municipality_id = this.adminData.municipality_id
      enterprise.address = this.adminData.address
      enterprise.phone = this.adminData.phone
      enterprise.email = this.adminData.email
      try {
        let data = await axios.put(this.$store.state.jhsoft.url + 'api/enterprises/enterpriseUpdate' + '/' + enterprise.id, enterprise)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async resolutions () {
      this.$q.loading.show()
      try {
        let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/soenac/resolutions')
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async productionNumbers () {
      this.$q.loading.show()
      let enterprise = {}
      enterprise.nit = this.adminData.nit
      enterprise.software_id = this.adminData.software_id
      try {
        let data = await axios.post(this.$store.state.jhsoft.url + 'api/enterprises/soenac/productionNumbers', enterprise)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    }
  },
  computed: {
  }
}
</script>

<style scoped>
.parrafoLabel {
font-size: 18px;
}
</style>
