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
                <q-tab-panel v-for="(itemTitle, keyTitle) in title" :key="keyTitle + 'title'" :name="itemTitle" style="max-width: 600px" >
                  <info-component
                  :title="title[keyTitle]"
                  :adminData="adminData"
                  :field="field[keyTitle]"
                  :label="label[keyTitle]"
                  :labelButton="labelButton[keyTitle]"
                  :classButton="classButton[keyTitle]"
                  :colorButton="colorButton[keyTitle]"
                  :methodButton="methodButton[keyTitle]"
                  :botones.sync="botones"
                  >
                  <template slot="footerBtn1">
                    <p>¿Crear la empresa seleccionada en el sistema DIAN?</p>
                  </template>
                  <template slot="footerBtn2">
                    <p>¿Actualizar empresa en el sistema DIAN?</p>
                  </template>
                  </info-component>
                </q-tab-panel>
              </q-tab-panels>
            </q-card>
          </div>
        </div>
        <div class="col-shrink no-wrap q-mx-md q-my-md q-col-gutter-md items-center justify-center content-center">
          <div class="col-12" style="max-width: 800px"><q-btn class="q-mx-xl btn-limon" style="width: 700px" @click="productionNumbers(adminData.id), alertDiag = true">Ver numeros de produccion</q-btn></div>
          <q-dialog v-model="alertDiag">
            <q-card class="col-md-auto q-px-md overflow-auto justify-center" style="max-height: 600px; max-width: 800px">
              <q-card-section>
                <div class="text-h5">Numeros de producción</div>
              </q-card-section>
              <q-card-section class="justify-center content-center q-pa-md" style="width: 800px">
                <q-list dense bordered padding class="q-mx-xl rounded-borders" style="width: 700px; height: 550px" v-for = "(item, key) in resolutionData" :key="item.id">
                  <q-item clickable tag="label" v-ripple @click="checkResolution(key)">
                    <q-item-section class="col-12" style="max-width: 650px">
                      <div class="col-md-auto " style="width: 550px" v-for = "(items, claves) in item" :key="items.id">
                        {{claves}} : {{items}}
                      </div>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-card-section>
              <q-card-actions>
                <q-btn class="q-mx-xl btn-limon">FC</q-btn>
                <q-btn class="q-mx-xl btn-limon">NC</q-btn>
                <q-btn class="q-mx-xl btn-limon">ND</q-btn>
              </q-card-actions>
            </q-card>
          </q-dialog>

        <div class="col-12" style="max-width: 800px"><q-btn class="q-mx-xl" color="teal-5" style="width: 700px" @click="resolutions()">Ver resoluciones</q-btn></div>
          <div class="col-md-auto q-px-md overflow-auto justify-center" style="max-height: 600px; max-width: 800px">
            <div class="justify-center content-center q-pa-md" style="width: 600px">
              <q-item-label class="q-mx-xl " header>Resultados de resolucion</q-item-label>
              <q-list dense bordered padding class="q-mx-xl rounded-borders"  style="width: 700px; height: 550px">
                <q-item tag="label" v-ripple>
                    <q-item-section class="col-12" style="max-width: 650px">
                      <div class="col-md-auto " style="width: 550px" v-for = "(items, claves) in resolutionResponse" :key="items.id">
                        {{claves}} : {{items}}
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
    console.log('hola')
  },
  mounted: function () {
  },
  beforeUpdate: function () {
    console.log(this.methodButton)
    // console.log(this.adminData)
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
      options: null,
      alertDiag: false,
      resolutionDocOption: ['Factura', 'NC', 'ND'],
      adminData: 'null',
      // -- tabs -- //
      tab: 'info',
      // componente cuadro de información
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
      ],
      // ----- botones y pie de pagina ----- //
      colorButton: [
        ['light-green-5', 'orange-5'],
        ['orange-5'],
        ['orange-5']
      ],
      classButton: [
        ['q-ml-xs', 'q-ml-xs'],
        ['q-ml-xs'],
        ['q-ml-xs']
      ],
      labelButton: [
        ['Crear empresa', 'Actualizar DIAN'],
        ['Subir información'],
        ['Subir certificado']
      ],
      methodButton: [
        {
          creacionEmpresa_dian: (function (id) { // boton creacion empresa
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
          }(this.adminData.id)),
          enterpriseUpdating: (function (id) { // boton actualizar empresa
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
          }(this.adminData.id))
        },
        {
          softInfo: (function (id) { // boton soft id
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
          }(this.adminData.id))
        },
        {
          certificateUp: (function (id) { // boton certificado
            this.$q.loading.show()
            try {
              let data = axios.put(this.$store.state.jhsoft.url + 'api/enterprises/certificateUp' + '/' + id)
              console.log(data)
            } catch (error) {
              console.log(error)
            } finally {
              this.$q.loading.hide()
            }
          }(this.adminData.id))
        }
      ],
      botones: 'hola'
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
    // select input datos de empresa
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
        dataBd.data.confirm = []
        for (let i = 0; i < this.labelButton.length; i++) {
          dataBd.data.confirm[i] = []
          if (dataBd.data.token !== null) {
            dataBd.data.confirm[i] = true
          } else {
            dataBd.data.confirm[i] = false
          }
        }
        this.adminData = dataBd.data // ingreso datos a la vista
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    // boton resoluciones
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

  },
  watcher: {
  }
}
</script>

<style scoped>
</style>
