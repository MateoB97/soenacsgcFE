<template>
  <div>
    <q-page class="q-gutter-md" padding>
      <h4>Administrar empresas</h4>
      <div class="row q-col-gutter-md">
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
      <div v-if="adminData !== 'null'">
        <div class="row q-col-gutter-md">
          <div class="col-2">
            <q-btn class="q-ml-xs" color="teal-5" @click="resolutions()">Ver resoluciones</q-btn>
          </div>
        </div>
        <div class="row q-col-gutter-md">
          <div class="col-2">
            <q-btn class="q-ml-xs btn-limon" @click="productionNumbers()">Ver numeros de produccion</q-btn>
          </div>
          <div class="col-2">
            <q-input type="text" label="Prefijo"></q-input>
          </div>
          <div class="col-2">
            <q-input type="text" label="Fechas"></q-input>
          </div>
          <div class="col-2">
            <q-input type="text" label="Clave tecnica"></q-input>
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
          <div v-if="this.adminData.sendConfirm == false " class="row q-col-gutter-md">
            <p>¿Crear la empresa seleccionada en el sistema DIAN?</p>
            <div class="col-2">
              <q-btn
                color="q-ml-xs btn-limon"
                @click="creacionEmpresa_dian(adminData.id)"
                label="Crear empresa"
              />
            </div>
          </div>
          <div v-if="this.adminData.sendConfirm == false " class="row q-col-gutter-md">
            <div class="col-2">
              <q-btn
                class="q-ml-xs"
                color="orange-5"
                @click="enterpriseUpdate(adminData.id)"
              >Actualizar DIAN</q-btn>
            </div>
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
    </q-page>
  </div>
</template>

<script>
import { globalFunctions } from 'boot/mixins.js'
// import { mapState } from "vuex"

const axios = require('axios')

export default {
  name: 'adminEnterprises',
  created: function () {
    this.globalGetForSelect('api/enterprises/admin/adminEnterprises', 'options')
    // this.storeData = JSON.parse(localStorage.getItem('dataEnterprise'))
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
  watch: function () {
    // this.globalGetItems()
    // console.log(this.tableData)
  },
  data: function () {
    return {
      urlAPI: 'api/enterprises',
      selectData: {
      },
      storeItems: {
      },
      resolutionData: 'null',
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
        console.log(this.adminData)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async softInfo (id) {
      this.$q.loading.show()
      try {
        let data = await axios.post(this.$store.state.jhsoft.url + 'api/enterprises/soenac/softInfo' + '/' + id)
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
        let data = await axios.post(this.$store.state.jhsoft.url + 'api/enterprises/soenac/resolutions')
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    },
    async enterpriseUpdate (id) {
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
        let data = await axios.post(this.$store.state.jhsoft.url + 'api/enterprises/soenac/productionNumbers' + '/' + id)
        console.log(data)
      } catch (error) {
        console.log(error)
      } finally {
        this.$q.loading.hide()
      }
    }
  },
  computed: {
    // ...mapState('enterprise')
  }
}
</script>

<style scoped>
.parrafoLabel {
  font-size: 18px;
}
</style>
