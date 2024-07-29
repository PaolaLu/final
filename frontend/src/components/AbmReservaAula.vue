<template>
  <v-form ref="form" v-model="valid" @submit.prevent="submit">
    <v-card>
      <v-card-title>{{ editar ? 'Editar Reserva de Aula' : '' }}</v-card-title>
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

        <v-select
          v-model="reservaData.id_materia"
          :items="materias"
          item-text="nombre"
          item-value="id"
          label="Materia"
          :rules="materiaRules"
          required
        ></v-select>

        <!-- Fecha y Hora de Inicio -->
        <v-row>
          <v-col cols="12" sm="6">
            <v-menu
              v-model="menuDesde"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="fechaDesde"
                  label="Fecha de Inicio"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  :rules="fechaDesdeRules"
                  required
                ></v-text-field>
              </template>
              <v-date-picker v-model="fechaDesde" @input="menuDesde = false"></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" sm="6">
            <v-text-field
              v-model="horaDesde"
              label="Hora de Inicio"
              type="time"
              prepend-icon="mdi-clock-outline"
              :rules="horaRules"
              required
            ></v-text-field>
          </v-col>
        </v-row>

        <!-- Fecha y Hora de Fin -->
        <v-row>
          <v-col cols="12" sm="6">
            <v-menu
              v-model="menuHasta"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="fechaHasta"
                  label="Fecha de Fin"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  :rules="fechaHastaRules"
                  required
                ></v-text-field>
              </template>
              <v-date-picker v-model="fechaHasta" @input="menuHasta = false"></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" sm="6">
            <v-text-field
              v-model="horaHasta"
              label="Hora de Fin"
              type="time"
              prepend-icon="mdi-clock-outline"
              :rules="horaRules"
              required
            ></v-text-field>
          </v-col>
        </v-row>
  
        <!-- Observaciones -->
        <v-text-field
          v-model="reservaData.observacion"
          label="Observaciones"
          :rules="observacionRules"
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
      default: () => ({ id: "", id_aula: "", fh_desde: "", fh_hasta: "", observacion: "", id_materia: "" }),
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
        id: "",
        id_aula: "",
        id_materia: "",
        fh_desde: "",
        fh_hasta: "",
        observacion: "",
      },
      fechaDesde: "",
      horaDesde: "",
      fechaHasta: "",
      horaHasta: "",
      aulas: [],
      materias: [],
      aulaRules: [(v) => !!v || 'El aula es requerida'],
      materiaRules: [(v) => !!v || 'La materia es requerida'],
      fechaDesdeRules: [(v) => !!v || 'La fecha de inicio es requerida'],
      fechaHastaRules: [(v) => !!v || 'La fecha de fin es requerida'],
      horaRules: [(v) => !!v || 'La hora es requerida'],
      observacionRules: [],
      menuDesde: false,
      menuHasta: false,
    };
  },
  watch: {
    reserva: {
      handler(nuevoValor) {
        this.reservaData = { ...nuevoValor };
        if (this.reservaData.fh_desde) {
          const desde = new Date(this.reservaData.fh_desde);
          this.fechaDesde = this.formatDate(desde);
          this.horaDesde = this.formatTime(desde);
        }
        if (this.reservaData.fh_hasta) {
          const hasta = new Date(this.reservaData.fh_hasta);
          this.fechaHasta = this.formatDate(hasta);
          this.horaHasta = this.formatTime(hasta);
        }
      },
      deep: true,
      immediate: true,
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return null;
      const d = new Date(date);
      return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
    },
    formatTime(date) {
      if (!date) return null;
      const d = new Date(date);
      return `${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
    },
    updateDateTime() {
      this.reservaData.fh_desde = `${this.fechaDesde}T${this.horaDesde}:00`;
      this.reservaData.fh_hasta = `${this.fechaHasta}T${this.horaHasta}:00`;
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.updateDateTime();
        this.$emit('guardar', this.reservaData);
      }
    },
    cancelar() {
      this.$emit('cerrar');
      
    },
    async obtenerAulas() {
      try {
        const response = await custom_axios.get('/apiv1/aula');
        this.aulas = response.data;
      } catch (error) {
        console.error('Error obteniendo aulas:', error);
      }
    },
    async obtenerMaterias() {
      try {
        const response = await custom_axios.get('/apiv1/materia');
        this.materias = response.data;
      } catch (error) {
        console.error('Error obteniendo materias:', error);
      }
    },
  },
  created() {
    this.obtenerAulas();
    this.obtenerMaterias();
  },
};
</script>

<style scoped>
.v-card {
  max-width: 600px;
  margin: auto;
}
</style>