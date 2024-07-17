<template>
  <div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <div class="container">
   
      <h1>Sistema de Administración de Aulas Universitarias</h1>
      <br>
      <div class="card-container">
        <div class="card">
          <h2><i class="fas fa-chalkboard"></i> Gestión de Aulas</h2>
          <p>Administre las aulas, sus capacidades y equipamientos.</p>
          <router-link to="/aulas" class="btn">Ir a Gestión de Aulas</router-link>
        </div>
        
        <div class="card">
          <h2><i class="fas fa-calendar-alt"></i> Reservas</h2>
          <p>Gestione las reservas de aulas para clases y eventos.</p>
          <router-link to="/reservaaula" class="btn">Ver Reservas</router-link>
        </div>
  
        <div class="card">
          <h2><i class="fas fa-clock"></i> Horarios</h2>
          <p>Administre los horarios de las clases y eventos.</p>
          <router-link to="/horariomateria" class="btn">Ver Horarios</router-link>
        </div>
  
        <div class="card">
          <h2><i class="fas fa-book"></i> Materias</h2>
          <p>Administre las materias disponibles.</p>
          <router-link to="/materia" class="btn">Ver Materias</router-link>
        </div>
  
        <div class="card">
          <h2><i class="fas fa-graduation-cap"></i> Carreras</h2>
          <p>Administre las carreras académicas.</p>
          <router-link to="/carrera" class="btn">Ver Carreras</router-link>
        </div>
  
        <div class="card">
          <h2><i class="fas fa-user-tie"></i> Profesores</h2>
          <p>Administre la información de los profesores.</p>
          <router-link to="/profesore" class="btn">Ver Profesores</router-link>
        </div>
        
      </div>
    </div>
  </div>
</template>

<script>
import logo from '@/assets/logo.png';

export default {
  name: 'Home',
  data() {
    return {
      logo
    };
  }
}
</script>

<style scoped>
body {
  font-family: Arial, sans-serif;
  line-height: 1.6;
  margin: 0;
  padding: 20px;
  background-color: #f4f4f4;
}
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}
.logo {
  width: 150px;
  margin-bottom: 20px;
}
h1 {
  color: #1a3b72; /* Primary color from logo */
}
.card-container {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 20px;
}
.card {
  background: #fff;
  border-radius: 5px;
  padding: 20px;
  width: calc(33.333% - 20px); /* Three cards per row */
  box-sizing: border-box;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.3s, box-shadow 0.3s, border 0.3s;
  border: 3px solid #ccc; /* Wider border */
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 250px; /* Fixed height for all cards */
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  border-color: #333;
}
.card:active {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  border-color: #000;
}
.card h2 {
  color: #1a3b72; /* Primary color from logo */
  margin-top: 0;
}
.card p {
  color: #666;
  flex-grow: 1;
}
.btn {
  display: inline-block;
  background: #007bff;
  color: #fff;
  padding: 10px 20px;
  text-decoration: none;
  border-radius: 5px;
  transition: background 0.3s;
}
.btn:hover {
  background: #0056b3;
}

@media (max-width: 768px) {
  .card {
    width: calc(50% - 20px); /* Two cards per row on smaller screens */
  }
}

@media (max-width: 480px) {
  .card {
    width: 100%; /* One card per row on very small screens */
  }
}
</style>