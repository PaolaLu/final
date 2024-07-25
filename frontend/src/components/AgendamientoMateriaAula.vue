<template>
    <v-card>
      <v-card-title>
        Agendamiento de Materia y Aula
      </v-card-title>
      <v-card-text>
        <v-form ref="form" v-model="valid">
          <v-select
            v-model="selectedMateria"
            :items="materias"
            item-text="nombre"
            item-value="id"
            label="Materia"
            required
          ></v-select>
          <v-select
            v-model="selectedAula"
            :items="aulas"
            item-text="descripcion"
            item-value="id"
            label="Aula"
            required
          ></v-select>
          <v-text-field
            v-model="fechaHoraDesde"
            label="Fecha Hora Desde"
            type="datetime-local"
            required
          ></v-text-field>
          <v-text-field
            v-model="fechaHoraHasta"
            label="Fecha Hora Hasta"
            type="datetime-local"
            required
          ></v-text-field>
          <v-text-field
            v-model="observacion"
            label="Observación"
            required
          ></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-btn color="blue darken-1" text @click="cancelar">Cancelar</v-btn>
        <v-btn color="green darken-1" text @click="guardar">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </template>
  
  <script>
  import custom_axios from '../plugins/axios.js';
  
  export default {
    data() {
      return {
        valid: false,
        materias: [],
        aulas: [],
        selectedMateria: null,
        selectedAula: null,
        fechaHoraDesde: '',
        fechaHoraHasta: '',
        observacion: '',
      };
    },
    methods: {
      async obtenerMaterias() {
        try {
          const response = await custom_axios.get('/apiv1/materia');
          if (response.status === 200) {
            this.materias = response.data;
          }
        } catch (error) {
          console.error('Error obteniendo materias:', error);
        }
      },
      async obtenerAulas() {
        try {
          const response = await custom_axios.get('/apiv1/aula');
          if (response.status === 200) {
            this.aulas = response.data;
          }
        } catch (error) {
          console.error('Error obteniendo aulas:', error);
        }
      },
      cancelar() {
        this.$emit('cancelar');
      },
      async guardar() {
        if (this.$refs.form.validate()) {
          try {
            const nuevaReserva = {
              id_aula: this.selectedAula,
              fh_desde: this.fechaHoraDesde,
              fh_hasta: this.fechaHoraHasta,
              observacion: this.observacion,
            };
            const response = await custom_axios.post('/apiv1/reservaaula', nuevaReserva);
            if (response.status === 201) {
              this.$emit('guardar');
            }
          } catch (error) {
            console.error('Error guardando reserva:', error);
          }
        }
      },
    },
    created() {
      this.obtenerMaterias();
      this.obtenerAulas();
    },
  };
  </script>
