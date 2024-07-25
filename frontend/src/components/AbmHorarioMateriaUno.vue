<template>
  <v-form ref="form" v-model="valid" @submit.prevent="submit">
    <v-card>
      <v-card-title>{{ editar ? 'Editar Horario de Materia' : 'Agregar Horario de Materia' }}</v-card-title>
      <v-card-text>
        <v-select
          v-model="horarioData.id_materia"
          :items="materias"
          item-text="nombre"
          item-value="id"
          label="Materia"
          :rules="materiaRules"
          required
        ></v-select>
        
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
              @click="focusInput('horaDesde')"
            ></v-text-field>
          </v-col>
        </v-row>
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
              @click="focusInput('horaHasta')"
            ></v-text-field>
          </v-col>
        </v-row>
  
        <v-text-field
          v-model="horarioData.observacion"
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
    horario: {
      type: Object,
      default: () => ({ id: "", id_materia: "", fh_desde: "", fh_hasta: "", observacion: "" }),
    },
    editar: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      valid: false,
      horarioData: {
        id_materia: "",
        fh_desde: "",
        fh_hasta: "",
        observacion: "",
      },
      fechaDesde: "",
      horaDesde: "",
      fechaHasta: "",
      horaHasta: "",
      materias: [],
      materiaRules: [(v) => !!v || 'La materia es requerida'],
      fechaDesdeRules: [(v) => !!v || 'La fecha de inicio es requerida'],
      fechaHastaRules: [(v) => !!v || 'La fecha de fin es requerida'],
      horaRules: [(v) => !!v || 'La hora es requerida'],
      observacionRules: [],
      menuDesde: false,
      menuHasta: false,
      materiasLoading: false,
      materiasError: '',
    };
  },
  watch: {
    horario: {
      handler(nuevoValor) {
        this.horarioData = { ...nuevoValor };
        if (this.horarioData.fh_desde) {
          const desde = new Date(this.horarioData.fh_desde);
          this.fechaDesde = this.formatDate(desde);
          this.horaDesde = this.formatTime(desde);
        }
        if (this.horarioData.fh_hasta) {
          const hasta = new Date(this.horarioData.fh_hasta);
          this.fechaHasta = this.formatDate(hasta);
          this.horaHasta = this.formatTime(hasta);
        }
        if (!this.editar) {
          this.resetValidation();
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
      this.horarioData.fh_desde = `${this.fechaDesde}T${this.horaDesde}:00`;
      this.horarioData.fh_hasta = `${this.fechaHasta}T${this.horaHasta}:00`;
    },
    submit() {
      this.$refs.form.validate();
      if (this.valid) {
        this.updateDateTime();
        if (this.editar) {
          this.editarHorario();
        } else {
          this.guardarHorario();
        }
      }
    },
    guardarHorario() {
      custom_axios
        .post('/apiv1/horariomateria', this.horarioData)
        .then((response) => {
          this.$emit('guardar');
          this.$emit('cerrar');
        })
        .catch((error) => {
          console.error('Error guardando horario:', error);
        });
    },
    editarHorario() {
      custom_axios
        .patch(`/apiv1/horariomateria/${this.horarioData.id}`, this.horarioData)
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
    obtenerMaterias() {
      this.materiasLoading = true;
      this.materiasError = '';
      custom_axios
        .get('/apiv1/materia')
        .then((response) => {
          this.materias = response.data;
        })
        .catch((error) => {
          this.materiasError = 'Error obteniendo materias';
          console.error('Error obteniendo materias:', error);
        })
        .finally(() => {
          this.materiasLoading = false;
        });
    },
    focusInput(ref) {
      this.$nextTick(() => {
        if (this.$refs[ref]) {
          this.$refs[ref].focus();
        }
      });
    }
  },
  created() {
    this.obtenerMaterias();
    if (this.horario) {
      this.horarioData = { ...this.horario };
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
