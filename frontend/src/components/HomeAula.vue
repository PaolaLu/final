<template>
  <v-container>
    <v-row class="d-flex justify-center">
      <v-col cols="12" md="4" v-for="item in items" :key="item.title">
        <v-card outlined class="pa-4">
          <v-card-title class="headline"><v-icon left>{{ item.icon }}</v-icon> {{ item.title }}</v-card-title>
          <v-card-text>{{ item.text }}</v-card-text>
          <v-card-actions>
            <router-link :to="item.link" class="btn primary-btn">{{ item.btnText }}</router-link>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'Home',
  data() {
    return {
      items: [
        { title: 'Reservas Aulas', icon: 'fas fa-calendar-alt', text: 'Gestione las reservas de aulas.', link: '/reservaaula', btnText: 'Ver Reservas' },
        { title: 'Aulas', icon: 'fas fa-chalkboard', text: 'Administre las aulas.', link: '/aulas', btnText: 'Ir a Gestión de Aulas' },
        { title: 'Horarios', icon: 'fas fa-clock', text: 'Administre los horarios.', link: '/horariomateria', btnText: 'Ver Horarios' },
        { title: 'Materias', icon: 'fas fa-book', text: 'Administre las materias.', link: '/materia', btnText: 'Ver Materias' },
        { title: 'Carreras', icon: 'fas fa-graduation-cap', text: 'Administre las carreras.', link: '/carrera', btnText: 'Ver Carreras' },
        { title: 'Profesores', icon: 'fas fa-user-tie', text: 'Administre los profesores.', link: '/profesor', btnText: 'Ver Profesores' },
      ]
    };
  }
}
</script>

<style scoped>
.v-container {
  margin-top: 20px;
}

.v-card {
  transition: transform 0.3s, box-shadow 0.3s, border 0.3s;
  border: 3px solid #ccc;
}

.v-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  border-color: #333;
}

.v-card:active {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  border-color: #000;
}

.primary-btn {
  display: inline-block;
  background: #007bff;
  color: #fff;
  padding: 10px 20px;
  text-decoration: none;
  border-radius: 5px;
  transition: background 0.3s;
}

.primary-btn:hover {
  background: #0056b3;
}

@media (max-width: 768px) {
  .v-col {
    width: calc(50% - 20px);
  }
}

@media (max-width: 480px) {
  .v-col {
    width: 100%;
  }
}
</style>
