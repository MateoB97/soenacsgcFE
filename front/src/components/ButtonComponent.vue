<template>
      <button-component
        :classButton="classButton"
        :colorButton="colorButton"
        :labelButton="labelButton"
        :methodButton="methodButton"
        :adminData="adminData"
        @botones="$emit('botones' )"
      />
</template>

<script>
export default {
  name: 'ButtonComponent',
  data: function () {
    return {

    }
  },
  computed: {
    // estructura de botones
    outputButton () {
      let output = []
      let lista = [this.classButton.length, this.colorButton.length, this.labelButton.length, this.methodButton.length]
      lista.sort((a, b) => a - b)
      let tamaño = lista.slice(-1)
      for (let i = 0; i < tamaño; i++) {
        let boton = {}
        boton.methodButton = this.methodButton[i]
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
  props: {
    // información de api
    adminData: {
      type: Object,
      default: () => {}
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
      type: Array,
      default: () => []
    }
  }
}
</script>
<style scoped>

</style>
