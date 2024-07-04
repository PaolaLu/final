<template>
    <div>
      <div>
        <v-btn color="primary" @click="abrirAbmReservaAula(false)">Agregar Reserva de Aula</v-btn>
      </div>
  
      <v-data-table
        :headers="headers"
        :items="listadoReservaAula"
        height="400"
        item-value="id"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon @click="abrirAbmReservaAula(true, item)" class="mr-2">mdi-pencil</v-icon>
          <v-icon @click="confirmarEliminarReservaAula(item)" class="ml-2">mdi-delete</v-icon>
        </template>
      </v-data-table>
  
      <v-dialog v-model="mostrarAbmReservaAula" max-width="500px" persistent>
        <AbmReservaAula
          :reserva="reservaAulaSeleccionada"
          :editar="editar"
          @guardar="guardarAbmReservaAula"
          @cancelar="cancelarAbmReservaAula"
        />
      </v-dialog>
  
      <v-dialog v-model="mostrarConfirmacion" max-width="500px">
        <v-card>
          <v-card-title class="headline">Confirmar Eliminación</v-card-title>
          <v-card-text>¿Está seguro de que desea eliminar la reserva de aula?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" text @click="mostrarConfirmacion = false">Cancelar</v-btn>
            <v-btn color="red darken-1" text @click="eliminarReservaAulaConfirmada">Eliminar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </template>
  
  <script>
  import custom_axios from '../plugins/axios.js';
  import AbmReservaAula from './AbmReservaAula.vue';
  
  export default {
    components: {
      AbmReservaAula,
    },
    data() {
      return {
        headers: [
          { text: 'ID', value: 'id' },
          { text: 'ID Aula', value: 'id_aula' },
          { text: 'Fecha Hora Desde', value: 'fh_desde' },
          { text: 'Fecha Hora Hasta', value: 'fh_hasta' },
          { text: 'Observación', value: 'observacion' },
          { text: 'Acciones', value: 'actions', sortable: false },
        ],
        listadoReservaAula: [],
        mostrarAbmReservaAula: false,
        mostrarConfirmacion: false,
        reservaAulaSeleccionada: {},
        reservaAulaAEliminar: null,
        editar: false,
      };
    },
    methods: {
      todasLasReservasAula() {
        custom_axios
          .get('/apiv1/reservaaula')
          .then(response => {
            if (response.status === 200) {
              this.listadoReservaAula = response.data;
            }
          })
          .catch(error => {
            console.error(error);
          });
      },
      abrirAbmReservaAula(editar, reserva = null) {
        this.editar = editar;
        this.reservaAulaSeleccionada = reserva || { id: "", id_aula: "", fh_desde: "", fh_hasta: "", observacion: "" };
        this.mostrarAbmReservaAula = true;
      },
      guardarAbmReservaAula() {
        this.todasLasReservasAula();
        this.mostrarAbmReservaAula = false;
      },
      cancelarAbmReservaAula() {
        this.mostrarAbmReservaAula = false;
      },
      confirmarEliminarReservaAula(reserva) {
        this.reservaAulaAEliminar = reserva;
        this.mostrarConfirmacion = true;
      },
      eliminarReservaAulaConfirmada() {
        if (this.reservaAulaAEliminar) {
          custom_axios
            .delete(`/apiv1/reservaaula/${this.reservaAulaAEliminar.id}`)
            .then(response => {
              if (response.status === 204) {
                this.todasLasReservasAula();
                this.mostrarConfirmacion = false;
              }
            })
            .catch(error => {
              console.error(error);
            });
        }
      },
    },
    created() {
      this.todasLasReservasAula();
    },
  };
  </script>
  
  <style scoped>
  .mr-2 {
    margin-right: 8px;
  }
  .ml-2 {
    margin-left: 8px;
  }
  </style>
  