<template>
  <div class="q-ml-md row items-start">
    <div class="q-gutter-y-md" style="max-width: 600px">
      <div class="q-mx-md col-12"><h5>{{title}}</h5></div>
      <div class="row">
        <div class="col-6" v-for="(itemText, key) in outputText" :key="key + 'text'">
          <p class="parrafoLabel">{{itemText.label}}</p>
          <p>{{ itemText.parrafo }}</p>
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
