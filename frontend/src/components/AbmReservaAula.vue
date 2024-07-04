<template>
    <v-form ref="form" v-model="valid" @submit.prevent="submit">
      <v-card>
        <v-card-title>{{ editar ? 'Editar Reserva de Aula' : 'Agregar Reserva de Aula' }}</v-card-title>
        <v-card-text>
          <v-select
            v-model="reservaData.id_aula"
            :items="aulas"
            item-text="descripcion"
            item-value="id"
            label="Aula"
            :rules="aulaRules"
            required
          ></v-select>
          <v-row>
            <v-col cols="12">
              <v-menu
                ref="desdeMenu"
                v-model="menuDesde"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="reservaData.fh_desde"
                    label="Fecha de Inicio"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    :rules="fechaDesdeRules"
                    required
                  ></v-text-field>
                </template>
                <v-date-picker v-model="reservaData.fh_desde" @input="menuDesde = false"></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
  
          <!-- Fecha de Fin -->
          <v-row>
            <v-col cols="12">
              <v-menu
                ref="hastaMenu"
                v-model="menuHasta"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="reservaData.fh_hasta"
                    label="Fecha de Fin"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    :rules="fechaHastaRules"
                    required
                  ></v-text-field>
                </template>
                <v-date-picker v-model="reservaData.fh_hasta" @input="menuHasta = false"></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
  
          <!-- Observaciones -->
          <v-text-field
            v-model="reservaData.observacion"
            label="Observaciones"
            :rules="observacionRules"
            required
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submit" :disabled="!valid">Guardar</v-btn>
          <v-btn color="secondary" @click="cancelar">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </template>
  
  <script>
  import custom_axios from '../plugins/axios.js';
  
  export default {
    props: {
      reserva: {
        type: Object,
        default: () => ({ id: "", id_aula: "", fh_desde: "", fh_hasta: "", observacion: "" }),
      },
      editar: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        valid: false,
        reservaData: {
          id_aula: "",
          fh_desde: "",
          fh_hasta: "",
          observacion: "",
        },
        aulas: [],
        aulaRules: [(v) => !!v || 'El aula es requerida'],
        fechaDesdeRules: [(v) => !!v || 'La fecha de inicio es requerida'],
        fechaHastaRules: [(v) => !!v || 'La fecha de fin es requerida'],
        observacionRules: [(v) => !!v || 'Las observaciones son requeridas'],
        menuDesde: false,
        menuHasta: false,
        aulasLoading: false,
        aulasError: '',
      };
    },
    watch: {
      reserva: {
        handler(nuevoValor) {
          this.reservaData = { ...nuevoValor };
          if (!this.editar) {
            this.resetValidation();
          }
        },
        deep: true,
      },
    },
    methods: {
      submit() {
        // Validar el formulario antes de enviar
        this.$refs.form.validate();
        if (this.valid) {
          if (this.editar) {
            this.editarReserva();
          } else {
            this.guardarReserva();
          }
        }
      },
      guardarReserva() {
        custom_axios
          .post('/apiv1/reservaaula', this.reservaData)
          .then((response) => {
            this.$emit('guardar');
            this.$emit('cerrar');
          })
          .catch((error) => {
            console.error('Error guardando reserva:', error);
          });
      },
      editarReserva() {
        custom_axios
          .patch(`/apiv1/reservaaula/${this.reservaData.id}`, this.reservaData)
          .then((response) => {
            this.$emit('guardar');
            this.$emit('cerrar');
            this.$emit('actualizar');
          })
          .catch((error) => {
            console.error(error);
          });
      },
      cancelar() {
        this.$emit('cancelar');
      },
      resetValidation() {
        if (this.$refs.form) {
          this.$refs.form.resetValidation();
        }
      },
      obtenerAulas() {
        this.aulasLoading = true;
        this.aulasError = '';
        custom_axios
          .get('/apiv1/aula')
          .then((response) => {
            this.aulas = response.data;
          })
          .catch((error) => {
            this.aulasError = 'Error obteniendo aulas';
            console.error('Error obteniendo aulas:', error);
          })
          .finally(() => {
            this.aulasLoading = false;
          });
      },
    },
    created() {
      this.obtenerAulas();
      if (this.reserva) {
        this.reservaData = { ...this.reserva };
      }
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
  