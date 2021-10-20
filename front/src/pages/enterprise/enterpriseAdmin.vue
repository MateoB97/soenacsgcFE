<template>
  <div>
    <q-page class="q-gutter-md" padding>
      <h4 style="display:flex; justify-content: center; margin: auto; padding: 25px">Administrar empresas</h4>
      <div class="row q-col-gutter-md justify-center">
        <div class="col-3">
          <q-select
            v-model="selectData"
            use-input
            hide-selected
            fill-input
            option-label="business_name"
            :options="options"
            label="Selecciona empresa"
            option-disable="inactive"
            option-value="id"
            map-options
            emit-value
            input-debounce="0"
            @input="selectDataEnterprise()"
          />
        </div>
      </div>
      <div class="row" v-if="adminData !== 'null'">

        <div class="col">

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
              <p>{{ adminData.last_software_response = 'Aun sin respuesta'}}</p>
            </div>
            <div class="col-2">
              <q-btn class="q-ml-xs btn-limon" @click="softInfo(adminData.id)">Subir información</q-btn>
            </div>
            <p>Verifique que la información es correcta antes de subirla</p>
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
            <div class="col-2">
              <q-btn
                class="q-ml-xs"
                color="cyan-5"
                @click="certificateUp(adminData.id)"
              >Subir certificado</q-btn>
            </div>
          </div>
        </div>
        <div class="column q-col-gutter-md items-center">
          <div class="col-md-auto q-pa-md overflow-auto" style="height: 500px">
            <q-btn class="q-ml-xs btn-limon" @click="productionNumbers(adminData.id)">Ver numeros de produccion</q-btn>
            <q-select v-if="Object.keys(checkedResolution[0]).length > 1"
            :options = "resolutionDocOption"
            label="Selecciona tipo Doc"
            v-model = "selectDocRes"
            option-disable="inactive"
            option-label="docTypeSoenac"
            option-value="id"
            use-input
            hide-selected
            fill-input
            map-options
            emit-value
            input-debounce="0"
            @input="selectDocResolution()"
            />
            <div class="q-pa-md" style="max-width: 300px">
              <q-item-label header>Numeros de producción</q-item-label>
              <q-list dense bordered padding class="rounded-borders" v-for = "(item, key) in resolutionData" :key="item.id">
                <q-item clickable tag="label" v-ripple @click="checkResolution(key)">
                    <q-item-section>
                      <div class="column " style="max-width: 250px">
                        <div class="col-md-auto " style="max-width: 250px" v-for = "(items, claves) in item" :key="items.id">
                          {{claves}} : {{items}}
                        </div>
                      </div>
                    </q-item-section>
                </q-item>
              </q-list>
            </div>
          </div>
          <div class="col-md-auto q-pa-md overflow-auto" style="height: 500px">
            <q-btn class="q-ml-xs" color="teal-5" @click="resolutions()">Ver resoluciones</q-btn>
            <div class="q-pa-md" style="max-width: 300px">
              <q-item-label header>Resultados de resolucion</q-item-label>
              <q-list dense bordered padding class="rounded-borders">
                <q-item v-ripple >
                    <q-item-section>
                      <div class="column " style="max-width: 250px">
                        <div class="col-md-auto " style="max-width: 250px" v-for = "(items, claves) in resolutionResponse" :key="items.id">
                          {{claves}} : {{items}}
                        </div>
                      </div>
                    </q-item-section>
                </q-item>
              </q-list>
            </div>
          </div>
        </div>
      </div>
    </q-page>
  </div>
</template>

<script>
import { globalFunctions } from 'boot/mixins.js'
// import { ref } from 'vue'
// import { mapState } from "vuex"

const axios = require('axios')

export default {
  name: 'adminEnterprises',
  created: function () {
    this.globalGetForSelect('api/enterprises/admin/adminEnterprises', 'options')
  },
  mounted: function () {
  },
  beforeUpdate: function () {
    this.verifyCreate()
    this.verifyUpdate()
  },
  updated: function () {
  },
  data: function () {
    return {
      urlAPI: 'api/enterprises',
      selectData: {
      },
      selectDocRes: {
      },
      storeItems: {
      },
      resolutionData: [{ mensaje: 'No se han requerido numeros de producción' }],
      checkedResolution: [{ mensaje: 'Sin resultados de resolucion' }],
      resolutionResponse: [],
      adminData: 'null',
      options: null,
      resolutionDocOption: ['Factura', 'NC', 'ND']
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
    checkResolution (id) {
      this.checkedResolution[0] = this.resolutionData[id]
      console.log(Object.keys(this.checkedResolution[0]).length)
    },
    verifyUpdate () {
      if (this.adminData.last_software_response === null || this.adminData.last_software_response < this.adminData.updated_at) {
        this.adminData.confirmUpdate = true
      } else {
        this.adminData.confirmUpdate = false
      }
    },
    verifyCreate () {
      if (this.adminData.token !== null) {
        this.adminData.sendConfirm = false
      } else {
        this.adminData.sendConfirm = true
      }
    },
    async creacionEmpresa_dian (id) {
      console.log(id)
      this.$q.loading.show()
      try {
        let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/admin/confirmEnterpriseDian/' + id)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async selectDataEnterprise () {
      try {
        this.$q.loading.show()
        this.selectData = this.selectData.split('-')
        let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/admin/showAdmin' + '/' + this.selectData[1])
        this.adminData = data.data
        // console.log(this.adminData)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async softInfo (id) {
      this.$q.loading.show()
      try {
        let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/soenac/softInfo' + '/' + id)
        if (data.mensaje === 'Respuesta de software positiva') {
          this.adminData.last_software_response = data.response
        } else if (data.mensaje === 'Respuesta de software negativa') {
          this.adminData.last_software_response = 'Aun sin respuesta'
        }
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async certificateUp (id) {
      this.$q.loading.show()
      try {
        let data = await axios.put(this.$store.state.jhsoft.url + 'api/enterprises/certificateUp' + '/' + id)
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
        let request = this.checkedResolution
        console.log(request)
        let data = axios.post(this.$store.state.jhsoft.url + 'api/enterprises/soenac/resolutions/' + request)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async enterpriseUpdating (id) {
      this.$q.loading.show()
      try {
        let data = await axios.put(this.$store.state.jhsoft.url + 'api/enterprises/enterpriseUpdate' + '/' + id)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async productionNumbers (id) {
      this.$q.loading.show()
      try {
        let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/soenac/productionNumbers' + '/' + id)
        let response = data.data.responseDian.Envelope.Body.GetNumberingRangeResponse.GetNumberingRangeResult.ResponseList.NumberRangeResponse
        if (typeof response === 'undefined') {
          this.resolutionData[0] = { mensaje: 'No se han encontrado numeros de producción' }
        } else if (typeof response === 'object') {
          this.resolutionData = response
        }
        // console.log(typeof resolutionData)
        // console.log(this.resolutionData)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    selectDocResolution () {
      switch (this.selectDocRes) {
        case 'Factura':

          this.checkedResolution[0].type_document_id = 1
          break
        case 'NC':
          this.checkedResolution[0].type_document_id = 5
          let newPrefix1
          let oldPrefix1 = this.checkedResolution[0].Prefix
          newPrefix1 = oldPrefix1.slice(0, -2) + 'NC'
          this.checkedResolution[0].Prefix = newPrefix1
          break
        case 'ND':
          this.checkedResolution[0].type_document_id = 6
          let newPrefix2
          let oldPrefix2 = this.checkedResolution[0].Prefix
          newPrefix2 = oldPrefix2.slice(0, -2) + 'ND'
          this.checkedResolution[0].Prefix = newPrefix2
          break
      }
      // console.log(this.checkedResolution)
    }
  },
  computed: {
    // selectDocResolution (id){
    //   return
    // }
  }
}
</script>

<style scoped>
.parrafoLabel {
  font-size: 15px;
  font-weight: bold;
}
</style>
