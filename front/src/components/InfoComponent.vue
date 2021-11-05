<template>
  <div class="q-ml-md row items-start">
    <div class="q-gutter-y-md" style="max-width: 600px">
      <div class="q-mx-md col-12"><h5>{{title}}</h5></div>
      <div class="row">
        <div class="col-4" v-for="(itemText, key) in outputText" :key="key + 'text'">
          <p class="parrafoLabel">{{itemText.label}}</p>
          <p>{{ itemText.parrafo }}</p>
        </div>
      </div>
      <div v-if="outputButton.length > 0" class="row">
        <div class="col-5 q-ml-md q-pa-lg" v-for="(itemBut, key) in outputButton" :key="key + 'button'" >
          <div v-if="adminData.confirm[key] === true">
            <q-btn
              :class="itemBut.clase"
              :color="itemBut.color"
              :clicked="itemBut.methodButton"
              @click="$emit('update:botones', $event.target.clicked), methodCompro()"
              :label="itemBut.label"
            />
            <slot :name="'footerBtn' + (key + 1)" ></slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import ButtonComponent from './ButtonComponent.vue'

export default {
  // components: { ButtonComponent },
  name: 'InfoComponent',
  data: function () {
    return {

    }
  },
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
      let lista = [this.classButton.length, this.colorButton.length, this.labelButton.length, Object.keys(this.methodButton).length]
      lista.sort((a, b) => a - b)
      let tamaño = lista.slice(-1)
      for (let i = 0; i < tamaño; i++) {
        let boton = {}
        let indexMethod = Object.keys(this.methodButton)[i]
        boton.methodButton = this.methodButton[indexMethod]
        boton.label = this.labelButton[i]
        boton.color = this.colorButton[i]
        boton.clase = this.classButton[i]
        output.push(boton)
      }
      // console.log(this.methodButton)
      // console.log(output)
      return output
    }
  },
  model: {
    prop: 'clicked',
    event: 'botones'
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
    },
    methodButton: {
      type: Object,
      default: () => {}
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
