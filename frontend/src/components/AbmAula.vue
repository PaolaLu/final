<template>
  <v-form ref="form" v-model="valid" @submit.prevent="submit">
    <v-card>
      <v-card-title>{{ editar ? 'Editar Aula' : 'Agregar Aula' }}</v-card-title>
      <v-card-text>
        <v-text-field
          v-model="aulaData.descripcion"
          label="Descripción"
          :rules="descripcionRules"
          required
        ></v-text-field>
        <v-text-field
          v-model="aulaData.ubicacion"
          label="Ubicación"
          :rules="ubicacionRules"
          required
        ></v-text-field>
        <v-text-field
          @keyup.enter="submit"
          v-model="aulaData.cant_pcs"
          label="Cantantidad de pcs"
          :rules="cant_pcsRules"
          required
        ></v-text-field>
        <v-text-field
          @keyup.enter="submit"
          v-model="aulaData.cant_proyector"
          label="Cantantidad de proyector"
          :rules="cant_proyectorRules"
          required
        ></v-text-field>
        <v-radio-group
          v-model="aulaData.es_climatizada"
          label="¿Es climatizada?"
          :rules="es_climatizadaRules"
          row
        >
          <v-radio label="Sí" :value="true"></v-radio>
          <v-radio label="No" :value="false"></v-radio>
        </v-radio-group>
        <v-text-field
          @keyup.enter="submit"
          v-model="aulaData.aforo"
          label="Aforo"
          :rules="aforoRules"
          required
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="submit" :disabled="!valid">
          Guardar
        </v-btn>
        <v-btn color="secondary" @click="cancelar">
          Cancelar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
import custom_axios from '../plugins/axios.js';

export default {
  props: {
    aula: {
      type: Object,
      default: () => ({
        id: "",
        descripcion: "",
        ubicacion: "",
        cant_pcs: "",
        cant_proyector: "",
        es_climatizada: "",
        aforo: "",
      }),
    },
    editar: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      valid: false,
      aulaData: {
        descripcion: "",
        ubicacion: "",
        cant_pcs: "",
        cant_proyector: "",
        es_climatizada: null, // Inicialmente nulo para no seleccionado
        aforo: "",
      },
      descripcionRules: [
        v => !!v || 'La descripción es obligatoria',
      ],
      ubicacionRules: [
        v => !!v || 'La ubicación es obligatoria',
      ],
      cant_pcsRules: [
        v => !!v || 'La cantidad de PCs es obligatoria',
      ],
      cant_proyectorRules: [
        v => !!v || 'La cantidad de proyectores es obligatoria',
      ],
      es_climatizadaRules: [
        v => v !== null || 'Debes seleccionar si es climatizada o no',
      ],
      aforoRules: [
        v => !!v || 'El aforo es obligatorio',
      ],
    };
  },
  watch: {
    aula: {
      handler(nuevoValor) {
        this.aulaData = { ...nuevoValor };
        if (!this.editar) {
          this.resetValidationForAbm();
        }
      },
      immediate: true,
      deep: true,
    },
  },
  methods: {
    submit() {
      if (this.editar) {
        this.editarAula();
      } else {
        this.guardarAula();
      }
    },
    guardarAula() {
      custom_axios
        .post("/apiv1/aula", this.aulaData)
        .then(response => {
          console.log(response);
          this.resetValidationForAbm();
          this.$emit("guardar");
          this.$router.push('/aulas');
        })
        .catch(error => {
          console.log(error);
        });
    },
    editarAula() {
      custom_axios
        .patch(`/apiv1/aula/${this.aula.id}`, this.aulaData)
        .then(response => {
          console.log(response);
          this.resetValidationForAbm();
          this.$emit("guardar");
        })
        .catch(error => {
          console.log(error);
        });
    },
    cancelar() {
      this.resetValidationForAbm();
      this.$emit("cancelar");
      this.$router.push('/aulas');
      
    },
    resetValidationForAbm() {
      if (this.$refs.form) {
        this.$refs.form.resetValidation();
      }
    },
  },
  created() {
    if (this.aula) {
      this.aulaData = { ...this.aula };
    }
  },
};
</script>

<style scoped>
</style>
