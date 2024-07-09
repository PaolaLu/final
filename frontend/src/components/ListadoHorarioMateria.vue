<template>
   <div class="aula-container">
    <div class="button-container">
      
      <v-btn color="primary" 
      @click="abrirAbmHorarioMateria(false)"
       class="custom-button mr-4"
      >
      <v-icon left>mdi-school</v-icon>
      Agregar Horario de Materia</v-btn>
      <v-btn
        color="blue" 

        @click="navigateToHome"
        class="custom-button"
      >
        <v-icon left>mdi-home</v-icon>
        Volver a Home
      </v-btn>
    </div>

      <v-data-table
        :headers="headers"
        :items="listadoHorarios"
        height="400"
        item-value="id"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon color="green" @click="abrirAbmHorarioMateria(true, item)" class="mr-2">mdi-pencil</v-icon>
          <v-icon color="red" @click="confirmarEliminarHorario(item)" class="ml-2">mdi-delete</v-icon>
        </template>
      </v-data-table>
  
      <v-dialog v-model="mostrarAbmHorarioMateria" max-width="500px" persistent>
        <AbmHorarioMateria
          :horario="horarioSeleccionado"
          :editar="editar"
          @guardar="guardarAbmHorarioMateria"
          @cancelar="cancelarAbmHorarioMateria"
        />
      </v-dialog>
  
      <v-dialog v-model="mostrarConfirmacion" max-width="500px">
        <v-card>
          <v-card-title class="headline">Confirmar Eliminación</v-card-title>
          <v-card-text>¿Está seguro de que desea eliminar el horario de materia?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" text @click="mostrarConfirmacion = false">Cancelar</v-btn>
            <v-btn color="red darken-1" text @click="eliminarHorarioConfirmado">Eliminar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </template>
  
  <script>
  import custom_axios from '../plugins/axios.js';
  import AbmHorarioMateria from './AbmHorarioMateria.vue';
  
  export default {
    components: {
      AbmHorarioMateria,
    },
    data() {
      return {
        headers: [
          { text: 'Materia', value: 'materia' },
          { text: 'Desde', value: 'fh_desde' },
          { text: 'Hasta', value: 'fh_hasta' },
          { text: 'Acciones', value: 'actions', sortable: false },
        ],
        listadoHorarios: [],
        mostrarAbmHorarioMateria: false,
        mostrarConfirmacion: false,
        horarioSeleccionado: {},
        horarioAEliminar: null,
        editar: false,
      };
    },
    methods: {
      todosLosHorarios() {
        custom_axios
          .get('/apiv1/horariomateria')
          .then(response => {
            if (response.status === 200) {
              const horarios = response.data;
              const promises = horarios.map(horario => {
                return custom_axios.get(`/apiv1/materia/${horario.id_materia}`)
                  .then(result => ({
                    ...horario,
                    materia: result.data.nombre,
                    fh_desde: this.formatDateTime(horario.fh_desde),
                    fh_hasta: this.formatDateTime(horario.fh_hasta),
                  }));
              });
  
              Promise.all(promises)
                .then(updatedHorarios => {
                  this.listadoHorarios = updatedHorarios;
                })
                .catch(error => {
                  console.error('Error obteniendo nombres de materias:', error);
                });
            }
          })
          .catch(error => {
            console.error('Error obteniendo horarios:', error);
          });
      },
      formatDateTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return `${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
      },
      abrirAbmHorarioMateria(editar, horario = null) {
        this.editar = editar;
        this.horarioSeleccionado = horario ? { ...horario } : {};
        this.mostrarAbmHorarioMateria = true;
      },
      cancelarAbmHorarioMateria() {
        this.mostrarAbmHorarioMateria = false;
      },
      navigateToHome() {
      this.$router.push('/');
    },
      guardarAbmHorarioMateria() {
        this.mostrarAbmHorarioMateria = false;
        this.todosLosHorarios();
      },
      confirmarEliminarHorario(horario) {
        this.horarioAEliminar = horario;
        this.mostrarConfirmacion = true;
      },
      eliminarHorarioConfirmado() {
        if (this.horarioAEliminar) {
          custom_axios
            .delete(`/apiv1/horariomateria/${this.horarioAEliminar.id}`)
            .then(response => {
              if (response.status === 204) {
                this.todosLosHorarios();
                this.mostrarConfirmacion = false;
              }
            })
            .catch(error => {
              console.error('Error eliminando horario:', error);
            });
        }
      },
    },
    created() {
      this.todosLosHorarios();
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