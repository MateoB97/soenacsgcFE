<template>
  <div class="q-ml-md row items-start">
    <div class="q-gutter-y-md" style="max-width: 600px">
      <q-card>
        <q-tabs>
          <q-tab name="" label="hola" />
        </q-tabs>
        <q-separator />
        <q-tab-panels>
          <q-tab-panel >
            <div class="q-mx-md col-12"><h5>{{title}}</h5></div>
            <div class="row">
              <div class="col-4" v-for="(item, key) in outputText" :key="key">
                <p class="parrafoLabel">{{item.label}}</p>
                <p>{{ item.parrafo }}</p>
              </div>
            </div>
            <div v-if="outputButton.length > 0" class="row">
              <div class="col-5 q-ml-md q-pa-lg" v-for="(item, key) in outputButton" :key="key" >
                <q-btn
                  :class="item.clase"
                  :color="item.color"
                  @click="$emit('botones' + (key + 1))"
                  :label="item.label"
                />
                <slot :name="'footerBtn' + (key + 1)" ></slot>
              </div>
            </div>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InfoComponent',
  data: () => ({

  }),
  methods: {

  },
  computed: {
    // estructura de parrafos
    outputText () {
      let output = []
      const keyParrafo = Object.keys(this.adminData)
      let keyField = []
      for (let i = 0; i < this.field.length; i++) {
        const found = keyParrafo.find(e => e === this.field[i])
        keyField.push(found)
      }
      for (let i = 0; i < this.label.length; i++) {
        let agregar = {}
        agregar.label = this.label[i]
        agregar.parrafo = this.adminData[keyField[i]]
        output.push(agregar)
      }
      return output
    },
    // estructura de botones
    outputButton () {
      let output = []
      let lista = [this.classButton.length, this.colorButton.length, this.labelButton.length]
      lista.sort((a, b) => a - b)
      let tamaño = lista.slice(-1)
      for (let i = 0; i < tamaño; i++) {
        let boton = {}
        boton.label = this.labelButton[i]
        boton.color = this.colorButton[i]
        boton.clase = this.classButton[i]
        output.push(boton)
      }
      return output
    }
  },
  props: {
    // información de api
    adminData: {
      type: Object,
      default: () => {}
    },
    // props parrafo y cuerpo
    title: {
      type: String,
      default: ''
    },
    field: {
      type: Array,
      default: () => []
    },
    label: {
      type: Array,
      default: () => []
    },
    // props botones y pie
    classButton: {
      type: Array,
      default: () => []
    },
    colorButton: {
      type: Array,
      default: () => []
    },
    labelButton: {
      type: Array,
      default: () => []
    }
  }
}
</script>

<style scoped>
.parrafoLabel {
  font-size: 15px;
  font-weight: bold;
}
</style>
