<template lang="es">
    <div>
        <q-page padding>
            <h4>Token maestro</h4>
            <div class="row q-col-gutter-md">
              <div class="col-3">
                  <q-input type="text" v-model="storeItems.masterToken" label="Token Maestro"/>
              </div>
          </div>
          <div class="row q-mt-md">
            <div class="col-2">
                <q-btn v-if="!showForUpdate" color="primary" v-on:click="globalValidate('guardar')" label="Guardar" />
            </div>
        </div>
        </q-page>
    </div>
</template>
<script>
import { globalFunctions } from 'boot/mixins.js'
// const axios = require('axios')

export default {
  name: 'generals',
  data: function () {
    return {
      urlAPI: 'api/generals',
      storeItems: {}
    }
  },
  mixins: [globalFunctions],
  methods: {
    postSave () {
    },
    preSave () {
    },
    postEdit () {
    }
  },
  computed: {
  }
}
</script>
