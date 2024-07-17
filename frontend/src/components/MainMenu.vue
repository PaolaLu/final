<template>
	<v-container>
	  <v-navigation-drawer app permanent>
		<v-list-item>
		  <v-img
			alt="Aulas Logo1"
			class="shrink mr-2"
			contain
			:src="require('../assets/logo1.png')"
			transition="scale-transition"
			width="40"
		  />
		  <v-list-item-content>
			<v-list-item-title class="text-h6">Aulas Curza</v-list-item-title>
			<v-list-item-subtitle>Final 2024</v-list-item-subtitle>
		  </v-list-item-content>
		</v-list-item>
		<v-divider></v-divider>
		<v-list dense>
		  <v-list-item
			v-for="item in items"
			:key="item.title"
			:to="item.to"
			link
		  >
			<v-list-item-icon>
			  <v-icon>{{ item.icon }}</v-icon>
			</v-list-item-icon>
			<v-list-item-content>
			  <v-list-item-title>{{ item.title }}</v-list-item-title>
			</v-list-item-content>
		  </v-list-item>
		</v-list>
	  </v-navigation-drawer>
	</v-container>
  </template>
  
  <script>
  export default {
	name: "MainMenu",
	data: () => ({
	  items: [
		{ title: "Home", icon: "mdi-school", to: "/" },
		{ title: "Aula", icon: "mdi-school", to: "/aulas" },
		{ title: "Carrera", icon: "mdi-school", to: "/carrera" },
		{ title: "Profesor", icon: "mdi-school", to: "/profesor" },
		{ title: "Materia", icon: "mdi-school", to: "/materia" },
		{ title: "Reserva Aula", icon: "mdi-school", to: "/reservaaula" },
		{ title: "Horario Materia", icon: "mdi-school", to: "/horariomateria" },
	  ],
	}),
	mounted() {},
	computed: {},
  };
  </script>
  
  <style scoped>
  .shrink {
	margin-right: 8px;
  }
  </style>