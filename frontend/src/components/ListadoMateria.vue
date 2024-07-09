<template>
  <div class="aula-container">
    <div class="button-container">
      
      <v-btn 
        color="primary"
        @click="navigateToAbmMateria"
        class="custom-button mr-4"
      >
        <v-icon left>mdi-school</v-icon>
        Agregar Materia
      </v-btn>
      
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
      :items="listadoMaterias"
      height="400"
      item-value="name"
    >
      <template v-slot:item.actions="{ item }">
        <v-icon color="green" @click="editarMateria(item)" class="mr-2">mdi-pencil</v-icon>
        <v-icon color="red" @click="confirmarEliminarMateria(item)" class="ml-2">mdi-delete</v-icon>
      </template>
    </v-data-table>

    <v-dialog v-model="mostrarAbmMateria" max-width="500px" persistent>
      <AbmMateria
        :materia="materiaSeleccionada"
        :editar="editar"
        @guardar="guardarAbmMateria"
        @cancelar="cancelarAbmMateria"
      />
    </v-dialog>

    <v-dialog v-model="mostrarConfirmacion" max-width="500px">
      <v-card>
        <v-card-title class="headline">Confirmar Eliminación</v-card-title>
        <v-card-text>¿Está seguro de que desea eliminar esta materia?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="mostrarConfirmacion = false">Cancelar</v-btn>
          <v-btn color="red darken-1" text @click="eliminarMateriaConfirmada">Eliminar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import custom_axios from '../plugins/axios.js';
import AbmMateria from './AbmMateria.vue';

export default {
  components: {
    AbmMateria,
  },
  data() {
    return {
      headers: [
        { text: 'Nombre', value: 'nombre' },
        { text: 'Cantidad de Alumnos', value: 'cant_alumnos' },
        { text: 'Carrera', value: 'carrera' }, 
        { text: 'Profesor', value: 'profesor' }, 
        { text: 'Acciones', value: 'actions', sortable: false },
      ],
      listadoMaterias: [],
      mostrarAbmMateria: false,
      mostrarConfirmacion: false,
      materiaSeleccionada: {},
      materiaAEliminar: null,
      editar: false,
    };
  },
  methods: {
    async todasLasMaterias() {
      try {
        const response = await custom_axios
        .get('/apiv1/materia');
        if (response.status === 200) {
          const materias = response.data;
          console.log('Materias:', materias); 

          // ID de carreras y profesores
          const carreraIds = [...new Set(materias.map(m => m.id_carrera))];
          const profesorIds = [...new Set(materias.map(m => m.id_profesor))];

          // datos de todas las carreras y profesores
          const [carrerasResponse, profesoresResponse] = await Promise.all([
            custom_axios.get('/apiv1/carrera', { params: { ids: carreraIds.join(',') } }),
            custom_axios.get('/apiv1/profesor', { params: { ids: profesorIds.join(',') } }),
          ]);

          const carreras = carrerasResponse.data.reduce((acc, carrera) => {
            acc[carrera.id] = carrera;
            return acc;
          }, {});

          const profesores = profesoresResponse.data.reduce((acc, profesor) => {
            acc[profesor.id] = profesor;
            return acc;
          }, {});

          this.listadoMaterias = materias.map(materia => {
            return {
              ...materia,
              carrera: carreras[materia.id_carrera] ? carreras[materia.id_carrera].nombre : 'Sin carrera',
              profesor: profesores[materia.id_profesor] ? profesores[materia.id_profesor].apellido : 'Sin profesor',
            };
          });

        }
      } catch (error) {
        console.log(error);
      }
    },
    navigateToAbmMateria() {
      this.$router.push('/abmmateria');
    },
    navigateToHome() {
      this.$router.push('/');
    },
    editarMateria(item) {
      this.materiaSeleccionada = { ...item };
      this.editar = true;
      this.mostrarAbmMateria = true;
    },
    confirmarEliminarMateria(item) {
      this.materiaAEliminar = item;
      this.mostrarConfirmacion = true;
    },
    eliminarMateriaConfirmada() {
      custom_axios
        .delete(`/apiv1/materia/${this.materiaAEliminar.id}`)
        .then(response => {
          if (response.status === 204) {
            this.todasLasMaterias();
            this.mostrarConfirmacion = false;
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    guardarAbmMateria() {
      this.mostrarAbmMateria = false;
      this.todasLasMaterias();
    },
    cancelarAbmMateria() {
      this.mostrarAbmMateria = false;
      this.$router.push('/abmmateria');
    },
  },
  mounted() {
    this.todasLasMaterias();
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



<style scoped>
.mr-2 {
  margin-right: 8px;
}
.ml-2 {
  margin-left: 8px;
}
</style>
