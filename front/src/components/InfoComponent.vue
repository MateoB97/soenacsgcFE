<template>
  <div class="q-pa-md row items-start q-gutter-md">
    <div class="row q-col-gutter-md">
      <h5>{{title}}</h5>
    </div>
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
      <p class="parrafoLabel">Municipalidad</p>
      <p>{{ adminData.municipality_id}}</p>
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
    <div class="row q-col-gutter-md">
      <div v-if="adminData.sendConfirm" class="col-2">
        <q-btn
          color="q-ml-xs btn-limon"
          @click="creacionEmpresa_dian(adminData.id)"
          label="Crear empresa"
        />
        <p>¿Crear la empresa seleccionada en el sistema DIAN?</p>
      </div>
      <div v-if="adminData.confirmUpdate" class="col-2">
        <q-btn
          class="q-ml-xs"
          color="orange-5"
          @click="enterpriseUpdating(adminData.id)"
        >Actualizar DIAN</q-btn>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'InfoComponent',
  data: () => ({
    title: 'Datos empresa',
    inputText: [
      { label: 'dato1' }
    ],
    inputNumber: [
      { label: 'dato1' }
    ],
    inputSelect: [
      { label: 'dato1' }
    ],
    outputText: [
      { label: 'dato1', parrafo: 'dato2' }
    ],
    button: [
      { label: 'dato1' }
    ]
  })
}
</script>

<style scoped>

</style>
