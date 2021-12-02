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
      <div class="row items-center justify-center content-center" v-if="adminData !== 'null'">
        <div class="col-shrink no-wrap q-mx-md q-my-xl q-col-gutter-md items-center justify-center">
          <div class="q-gutter-y-md justify-center" style="max-width: 900px">
            <q-card style="width: 800px">
              <q-tabs
                v-model="tab"
                dense
                class="text-grey"
                active-color="primary"
                indicator-color="primary"
                align="justify"
                narrow-indicator
              >
                <q-tab v-for="(itemTitle, keyTitle) in title" :key="keyTitle + 'title'" :name="itemTitle" :label="itemTitle" />
              </q-tabs>
              <q-separator />
              <q-tab-panels v-model="tab" animated>
                <q-tab-panel name="Datos empresa" style="max-width: 600px" >
                  <info-component
                  :title="title[0]"
                  :adminData="adminData"
                  :field="field[0]"
                  :label="label[0]"
                  >
                  </info-component>
                  <div class="row" v-if="adminData.confirm === true">
                    <div class="col-5 q-ml-md q-pa-lg" >
                      <q-btn
                        class="q-ml-xs"
                        color="light-green-5"
                        @click="creacionEmpresa_dian(this.adminData.id)"
                        label="Crear empresa"
                      />
                      <p>¿Crear la empresa seleccionada en el sistema DIAN?</p>
                    </div>
                    <div class="col-5 q-ml-md q-pa-lg" >
                      <q-btn
                        class="q-ml-xs"
                        color="orange-5"
                        @click="enterpriseUpdating(this.adminData.id)"
                        label="Actualizar DIAN"
                      />
                      <p>¿Actualizar empresa en el sistema DIAN?</p>
                    </div>
                  </div>
                </q-tab-panel>
                <q-tab-panel name="Software" style="max-width: 600px" >
                  <info-component
                  :title="title[1]"
                  :adminData="adminData"
                  :field="field[1]"
                  :label="label[1]"
                  >
                  </info-component>
                  <div class="row" v-if="adminData.confirm === true">
                    <div class="col-5 q-ml-md q-pa-lg" >
                      <q-btn
                        class="q-ml-xs"
                        color="orange-5"
                        @click="softInfo(this.adminData.id)"
                        label="Subir información"
                      />
                      <p>¿Confirmar datos del software?</p>
                    </div>
                  </div>
                </q-tab-panel>
                <q-tab-panel name="Certificado" style="max-width: 600px" >
                  <info-component
                  :title="title[2]"
                  :adminData="adminData"
                  :field="field[2]"
                  :label="label[2]"
                  >
                  </info-component>
                  <div class="row" v-if="adminData.confirm === true">
                    <div class="col-5 q-ml-md q-pa-lg" >
                      <q-btn
                        class="q-ml-xs"
                        color="orange-5"
                        @click="certificateUp(this.adminData.id)"
                        label="Subir certificado"
                      />
                      <p>¿Crear certificado?</p>
                    </div>
                  </div>
                </q-tab-panel>
              </q-tab-panels>
            </q-card>
          </div>
        </div>
        <div class="col-shrink no-wrap q-mx-md q-my-md q-col-gutter-md items-center justify-center content-center">
          <div class="col-12" style="max-width: 800px"><q-btn class="q-mx-xl btn-limon" style="width: 700px" @click="productionNumbers(adminData.id), Diag1 = true">Ver numeros de produccion</q-btn></div>
          <div class="col-12" style="max-width: 800px" v-if="adminData.token !== null"><q-btn class="q-mx-xl btn-limon" style="width: 700px" @click="verEmpresa(adminData.id), Diag3 = true">Ver empresa soenac</q-btn></div>
          <q-dialog v-model="Diag1">
            <q-card class="col-md-auto q-pa-md overflow-auto justify-center content-center" style="max-height: 600px; max-width: 800px">
              <q-card-section class="justify-center q-pa-md">
                <div class="text-h5 absolute-center q-mx-md">Prefijos</div>
              </q-card-section>
              <q-card-section class="relative-position q-mx-md q-pa-md" style="width: 700px">
                <q-list dense bordered padding class="q-mx-sm rounded-borders" style="width: 650px; height: 50px" v-for = "(item, key) in resolutionData" :key="item.id">
                  <q-item clickable tag="label" v-ripple @click="checkResolution(key)" v-if="Object.keys(resolutionData).length > 1 ">
                    <q-item-section class="col-12" style="max-width: 650px">
                      {{item.Prefix}} : {{item.ResolutionNumber}}
                    </q-item-section>
                  </q-item>
                  <q-item clickable tag="label" v-ripple @click="checkResolution(key)" v-if="Object.keys(resolutionData).length <= 1">
                    <q-item-section class="col-12" style="max-width: 650px">
                      {{item.mensaje}}
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-card-section>
              <q-card-actions class="justify-center">
                <q-btn @click="selectDocResolution('FC')" class="q-mx-xl btn-limon" v-if="resolutionDocOption">FC</q-btn>
                <q-btn @click="selectDocResolution('NC')" class="q-mx-xl btn-limon" v-if="resolutionDocOption">NC</q-btn>
                <q-btn @click="selectDocResolution('ND')" class="q-mx-xl btn-limon" v-if="resolutionDocOption">ND</q-btn>
              </q-card-actions>
            </q-card>
          </q-dialog>
          <q-dialog v-model="Diag2">
            <q-card class="col-md-auto q-pa-md overflow-auto justify-center content-center" style="max-height: 600px; max-width: 800px">
              <q-card-section class="row items-center q-pb-none">
                <q-space />
                <q-btn icon="close" flat round dense v-close-popup />
              </q-card-section>
              <q-card-section class="justify-center q-pa-md">
                <div class="text-h5 absolute-center q-mx-md">Info de la resolución</div>
              </q-card-section>
              <q-card-section class="relative-position q-mx-md q-pa-md" style="width: 600px">
                {{resolutionResponse}}
              </q-card-section>
              <q-card-actions class="relative-position justify-center q-pa-md">
                <q-btn @click="downloadTxt()" class="q-mx-xl absolute-center btn-limon">Descargar</q-btn>
              </q-card-actions>
            </q-card>
          </q-dialog>
          <q-dialog v-model="Diag3">
            <q-card class="col-md-auto q-pa-md overflow-auto justify-center content-center" style="max-height: 600px; max-width: 800px">
              <q-card-section class="row items-center q-pb-none">
                <q-space />
                <q-btn icon="close" flat round dense v-close-popup />
              </q-card-section>
              <q-card-section class="justify-center q-ma-lg q-pa-lg">
                <div class="text-h5 absolute-center q-ma-sm">Empresa en soenac</div>
              </q-card-section>
              <q-card-section class="q-ma-md q-pa-md" style="width: 600px">
                <q-list dense bordered padding class="q-mx-sm rounded-borders" style="width: 550px; height: auto" v-if="Object.keys(responseEnterpriseData).length > 1 " >
                  <q-item tag="label" v-for = "(item, key) in responseEnterpriseData" :key="key">
                    <q-item-section class="col-12" style="max-width: 550px">
                      <b>{{key}}</b> {{item}}
                    </q-item-section>
                  </q-item>
                </q-list>
                <q-list dense bordered padding class="q-mx-sm rounded-borders" style="width: 550px; height: auto" v-if="Object.keys(responseEnterpriseData).length <= 1 " >
                  <q-item tag="label" v-for = "(item, key) in responseEnterpriseData" :key="key">
                    <q-item-section class="col-12" style="max-width: 550px">
                      {{key}} : {{item}}
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-card-section>
            </q-card>
          </q-dialog>
        </div>
      </div>
    </q-page>
  </div>
</template>

<script>
import { globalFunctions } from 'boot/mixins.js'
import InfoComponent from 'src/components/InfoComponent.vue'
// import { ref } from 'vue'
// import { mapState } from "vuex"

const axios = require('axios')

export default {
  name: 'adminEnterprises',
  components: {
    InfoComponent
  },
  created: function () {
    this.globalGetForSelect('api/enterprises/admin/adminEnterprises', 'options')
  },
  mounted: function () {
  },
  beforeUpdate: function () {
    // console.log(this.adminData)
  },
  updated: function () {
  },
  data: function () {
    return {
      urlAPI: 'api/enterprises',
      // select principal
      selectData: {
      },
      // datos principales
      adminData: 'null',
      selectDocRes: {
      },
      storeItems: {
      },
      options: null,
      // Cuadros de dialogo
      Diag1: false,
      Diag2: false,
      Diag3: false,
      // datos de la resoulucion
      resolutionData: { mensaje: 'No se han requerido numeros de producción' },
      checkedResolution: {},
      resolutionDocOption: false,
      resolutionResponse: {},
      oldPrefix: '',
      // respuesta datos soenac-empresa
      responseEnterpriseData: { mensaje: '' },
      // -- tabs -- //
      tab: 'info',
      // -- Componente cuadro de información --- //
      // ----- titulos y cabecera ----- //
      title: ['Datos empresa', 'Software', 'Certificado'],
      // ----- parrafo y cuerpo ----- //
      label: [
        ['Nombre de la empresa', 'Tipo de documento', 'NIT', 'Dirección', 'Municipalidad', 'Telefono', 'Correo electronico', 'Documento del representante', 'Tipo de ambiente'],
        ['Software ID', 'Software PIN', 'Software URL', 'Respuesta del software'],
        ['Certificado', 'Contraseña del certificado', 'Respuesta del certificado']
      ],
      field: [
        ['business_name', 'type_document_identification_id', 'nit', 'address', 'municipality_id', 'phone', 'email', 'ceo_document', 'type_environments'],
        ['software_id', 'software_pin', 'software_url', 'last_software_response'],
        ['certificate', 'certificate_password', 'last_certificate_response']
      ]
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
    // Select input datos de empresa
    async selectDataEnterprise () {
      try {
        this.$q.loading.show()
        this.selectData = this.selectData.split('-')
        let dataBd = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/admin/showAdmin' + '/' + this.selectData[0]) // datos BD
        let dataSoenac = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/soenac/soenacCampos') // datos Soenac API
        let result = [] // resultados del filtro
        let matchInput = [] // resultados del match entre API --> BD
        let entriesBd = Object.entries(dataBd.data) // convierto objeto --> array
        let entriesSoe = Object.entries(dataSoenac.data)
        entriesSoe.forEach(([keySo, valueSo]) => {
          let reg = new RegExp('^' + keySo.slice(0, 10)) // expresion regular de soenac para BD
          // filtro los match
          let filBd = entriesBd.filter(([keyBd, valueBd]) => {
            let match = reg.exec(keyBd)
            if (match) {
              matchInput.push(match.input)
              return keyBd === match.input
            }
          })
          // asigno campo id quemado para convertir en objeto con dicha clave
          filBd.forEach(element => {
            element[0] = 'id'
          })
          let bd = Object.fromEntries(filBd)
          for (let i = 0; i < valueSo.length; i++) {
            if (valueSo[i].id === parseInt(bd.id)) {
              result.push(valueSo[i])
            }
          }
        })
        // llenado / correcion para la vista
        for (let i = 0; i < result.length; i++) {
          dataBd.data[matchInput[i]] = result[i].name
        }
        if (dataBd.data.token !== null) {
          dataBd.data.confirm = true
        } else {
          dataBd.data.confirm = false
        }
        this.adminData = dataBd.data // ingreso datos a la vista
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // Boton creacion empresa
    creacionEmpresa_dian (id) {
      // console.log('hola create')
      this.$q.loading.show()
      try {
        let data = axios.get(this.$store.state.jhsoft.url + 'api/enterprises/admin/confirmEnterpriseDian/' + id)
        console.log(data.data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // Boton actualizar empresa
    enterpriseUpdating (id) {
      // console.log('hola update')
      this.$q.loading.show()
      try {
        let data = axios.put(this.$store.state.jhsoft.url + 'api/enterprises/enterpriseUpdate' + '/' + id)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // Boton soft id
    softInfo (id) {
      this.$q.loading.show()
      try {
        let data = axios.get(this.$store.state.jhsoft.url + 'api/enterprises/soenac/softInfo' + '/' + id)
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
    // Boton certificado up
    certificateUp (id) {
      this.$q.loading.show()
      try {
        let data = axios.put(this.$store.state.jhsoft.url + 'api/enterprises/certificateUp' + '/' + id)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // Boton ver empresa soenac
    async verEmpresa (id) {
      this.$q.loading.show()
      try {
        let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/soenac/verEmpresa' + '/' + id)
        console.log(data)
        this.responseEnterpriseData = data.data
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // Boton numeros de producción
    async productionNumbers (id) {
      this.$q.loading.show()
      try {
        let data = await axios.get(this.$store.state.jhsoft.url + 'api/enterprises/soenac/productionNumbers' + '/' + id)
        console.log(data)
        if (data.data.message === 'Server Error') {
          this.$q.notify({
            message: 'Error del servidor',
            color: 'red'
          })
          this.resolutionData[0].mensaje = 'Error del servidor'
        } else {
          let response = data.data.responseDian.Envelope.Body.GetNumberingRangeResponse.GetNumberingRangeResult.ResponseList.NumberRangeResponse
          console.log(response)
          if (typeof response === 'undefined') {
            this.resolutionData[0].mensaje = 'No se han encontrado numeros de producción'
          } else if (typeof response === 'object') {
            this.resolutionData = response
          }
        }
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // Escoge prefijo en resolución
    checkResolution (id) {
      this.checkedResolution = this.resolutionData[id]
      this.oldPrefix = this.checkedResolution.Prefix
      // console.log(this.oldPrefix)
      this.resolutionDocOption = true
    },
    // Response (info resoluciones)
    async resolutions () {
      this.$q.loading.show()
      try {
        console.log(this.checkedResolution)
        let request = this.checkedResolution
        request.id = this.adminData.id
        let data = await axios.post(this.$store.state.jhsoft.url + 'api/enterprises/soenac/resolutions/' + request)
        console.log(data)
        // verificar el data
        // if (data) {
        //   this.$q.notify({
        //     message: 'Error',
        //     color: 'red'
        //   })
        // } else if (data) {
        //   this.$q.notify({
        //     message: 'Exito',
        //     color: 'green'
        //   })
        // }
        this.resolutionResponse = data
        // if (data) { // falta saber como se recibe la data
        //   this.Diag2 = true
        // }
        this.Diag2 = true
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // Tipo doc resolución
    selectDocResolution (doc) {
      // console.log(this.oldPrefix)
      switch (doc) {
        case 'FC':
          this.checkedResolution.type_document_id = 1
          console.log(this.checkedResolution)
          this.checkedResolution.Prefix = this.oldPrefix
          this.resolutions()
          break
        case 'NC':
          this.checkedResolution.type_document_id = 5
          let newPrefix2
          let oldPrefix2 = this.checkedResolution.Prefix
          this.oldPrefix = oldPrefix2
          newPrefix2 = oldPrefix2.slice(0, -2) + 'NC'
          this.checkedResolution.Prefix = newPrefix2
          this.resolutions()
          break
        case 'ND':
          this.checkedResolution.type_document_id = 6
          let newPrefix3
          let oldPrefix3 = this.checkedResolution.Prefix
          this.oldPrefix = oldPrefix3
          newPrefix3 = oldPrefix3.slice(0, -2) + 'ND'
          this.checkedResolution.Prefix = newPrefix3
          this.resolutions()
          break
      }
    },
    // Boton descarga info resoluciones
    async downloadTxt () {
      this.$q.loading.show()
      try {
        let request
        let resolution = Object.entries(this.resolutionResponse)
        let admin = Object.entries(this.adminData)
        let concat = resolution.concat(admin)
        request = Object.fromEntries(concat)
        let data = await axios.post(this.$store.state.jhsoft.url + 'api/enterprises/admin/downloadTxt/' + request)
        this.downloadFile(data, 'hola', 'txt')
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    }
  },
  computed: {
  },
  watcher: {
  }
}
</script>

<style scoped>
</style>
