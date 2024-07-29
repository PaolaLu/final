<template>
  <div class="aula-container">
    <!-- Botones y Filtros -->
    <div class="button-container">
      <v-btn 
        color="primary" 
        @click="abrirAbmReservaAula(false)" 
        class="custom-button mr-4"
      >
        <v-icon left>mdi-school</v-icon>
        Agregar Reserva de Aula
      </v-btn>
      <v-btn
        color="info" 
        @click="mostrarCalendario = !mostrarCalendario"
        class="custom-button mr-4"
      >
        <v-icon left>mdi-calendar</v-icon>
        {{ mostrarCalendario ? 'Ocultar Calendario' : 'Mostrar Calendario' }}
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

    <!-- Filtros -->
    <v-row>
      <v-col cols="6">
        <v-select
          v-model="selectedAula"
          :items="aulas"
          item-value="id"
          item-text="descripcion"
          label="Seleccionar Aula"
        ></v-select>
      </v-col>
      <v-col cols="6">
        <v-select
          v-model="selectedMateria"
          :items="materias"
          item-value="id"
          item-text="nombre"
          label="Seleccionar Materia"
        ></v-select>
      </v-col>
      <v-col cols="12">
        <v-btn color="primary" @click="buscarReservas">
          Buscar Reservas
        </v-btn>
      </v-col>
    </v-row>

    <!-- Calendario -->
    <v-row v-if="mostrarCalendario">
      <v-col cols="12">
        <v-sheet height="400">
          <v-calendar
            ref="calendar"
            :events="eventosCalendario"
            :event-color="getEventColor"
            @click:event="mostrarDetallesEvento"
          ></v-calendar>
        </v-sheet>
      </v-col>
    </v-row>

    <!-- Tabla -->
    <v-data-table
      :headers="headers"
      :items="listadoReservaAula"
      height="400"
      item-value="id"
      dense
    >
      <template v-slot:item.fh_desde="{ item }">
        {{ formatDate(item.fh_desde) }}
      </template>
      <template v-slot:item.fh_hasta="{ item }">
        {{ formatTime(item.fh_hasta) }}
      </template>
      <template v-slot:item.actions="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-icon 
              v-bind="attrs"
              v-on="on"
              color="green" 
              @click="abrirAbmReservaAula(true, item)" 
              small
            >
              mdi-pencil
            </v-icon>
          </template>
          <span>Editar</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-icon 
              v-bind="attrs"
              v-on="on"
              color="red" 
              @click="confirmarEliminarReservaAula(item)" 
              small
            >
              mdi-delete
            </v-icon>
          </template>
          <span>Eliminar</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-icon 
              v-bind="attrs"
              v-on="on"
              color="blue" 
              @click="mostrarDetallesReserva(item)" 
              small
            >
              mdi-information
            </v-icon>
          </template>
          <span>Detalles</span>
        </v-tooltip>
      </template>
    </v-data-table>

    <!-- Diálogo de Confirmación -->
    <v-dialog v-model="mostrarConfirmacion" max-width="500px">
      <v-card>
        <v-card-title class="headline">Confirmar Eliminación</v-card-title>
        <v-card-text>¿Está seguro de que desea eliminar la reserva de aula?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="mostrarConfirmacion = false">Cancelar</v-btn>
          <v-btn color="red darken-1" text @click="eliminarReservaAulaConfirmada">Eliminar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Diálogo de Detalles -->
    <v-dialog v-model="mostrarDetalles" max-width="500px">
      <v-card>
        <v-card-title>Detalles de la Reserva</v-card-title>
        <v-card-text>
          <p><strong>Aula:</strong> {{ detallesReserva.aula_descripcion }}</p>
          <p><strong>Materia:</strong> {{ detallesReserva.materia_nombre }}</p>
          <p><strong>Desde:</strong> {{ formatDateTime(detallesReserva.fh_desde) }}</p>
          <p><strong>Hasta:</strong> {{ formatDateTime(detallesReserva.fh_hasta) }}</p>
          <p><strong>Observación:</strong> {{ detallesReserva.observacion }}</p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="mostrarDetalles = false">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="mostrarAbmReservaAula" max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">{{ editar ? 'Editar' : 'Agregar' }} Reserva de Aula</span>
      </v-card-title>
      <v-card-text>
        <abm-reserva-aula
          :editar="editar"
          :reserva="reservaAulaSeleccionada"
          @guardar="guardarAbmReservaAula"
          @cerrar="cerrarDialog"
        >
        </abm-reserva-aula>
      </v-card-text>
    </v-card>
  </v-dialog>
    <!-- Snackbar -->
    <v-snackbar v-model="snackbar" :color="snackbarColor" :timeout="3000">
      {{ snackbarText }}
    </v-snackbar>
  </div>
</template>
<script>
import custom_axios from '../plugins/axios.js';
import AbmReservaAula from './AbmReservaAula.vue';

export default {
  components: {
    AbmReservaAula,
  },
  data() {
    return {
      headers: [
        { text: 'Aula', value: 'aula_descripcion', width: '20%' },
        { text: 'Fecha', value: 'fh_desde', width: '15%' },
        { text: 'Hora', value: 'fh_hasta', width: '10%' },
        { text: 'Materia', value: 'materia_nombre', width: '25%' },
        { text: 'Acciones', value: 'actions', sortable: false, width: '10%', align: 'center' },
      ],
      listadoReservaAula: [],
      mostrarAbmReservaAula: false,
      mostrarConfirmacion: false,
      mostrarDetalles: false,
      mostrarCalendario: false,
      reservaAulaSeleccionada: {},
      reservaAulaAEliminar: null,
      detallesReserva: {},
      editar: false,
      snackbar: false,
      snackbarColor: '',
      snackbarText: '',
      eventosCalendario: [],
      aulas: [],
      materias: [],
      selectedAula: null,
      selectedMateria: null,
    };
  },
  methods: {
    async todasLasReservasAula() {
      try {
        const response = await custom_axios.get('/apiv1/reservaaula');
        if (response.status === 200) {
          const reservas = response.data;

          const aulaIds = [...new Set(reservas.map(r => r.id_aula))];
          const aulasResponse = await custom_axios.get('/apiv1/aula', { params: { ids: aulaIds.join(',') } });
          this.aulas = aulasResponse.data;

          const materiaIds = [...new Set(reservas.map(r => r.id_materia))];
          const materiasResponse = await custom_axios.get('/apiv1/materia', { params: { ids: materiaIds.join(',') } });
          this.materias = materiasResponse.data;

          const aulaMap = this.aulas.reduce((acc, aula) => {
            acc[aula.id] = aula.descripcion;
            return acc;
          }, {});
          
          const materiaMap = this.materias.reduce((acc, materia) => {
            acc[materia.id] = materia.nombre;
            return acc;
          }, {});

          this.listadoReservaAula = reservas.map(reserva => ({
            ...reserva,
            aula_descripcion: aulaMap[reserva.id_aula] || 'Sin aula',
            materia_nombre: materiaMap[reserva.id_materia] || 'Sin materia',
          }));

          this.actualizarEventosCalendario();
        }
      } catch (error) {
        console.error(error);
        this.mostrarSnackbar('Error al cargar las reservas', 'error');
      }
    },
    buscarReservas() {
      this.actualizarEventosCalendario();
    },
    actualizarEventosCalendario() {
      const eventos = this.listadoReservaAula
        .filter(reserva => 
          (!this.selectedAula || reserva.id_aula === this.selectedAula) &&
          (!this.selectedMateria || reserva.id_materia === this.selectedMateria)
        )
        .map(reserva => ({
          name: reserva.materia_nombre,
          start: new Date(reserva.fh_desde),
          end: new Date(reserva.fh_hasta),
          color: this.getRandomColor(),
          timed: true,
        }));

      this.eventosCalendario = eventos;
    },
    getRandomColor() {
      const letters = '0123456789ABCDEF';
      let color = '#';
      for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    },
    getEventColor(event) {
      return event.color;
    },
    abrirAbmReservaAula(editar, reserva = null) {
      this.editar = editar;
      this.reservaAulaSeleccionada = reserva || { id: "", id_aula: "", id_materia: "", fh_desde: "", fh_hasta: "", observacion: "" };
      this.mostrarAbmReservaAula = true;
    },
    async guardarAbmReservaAula(nuevaReserva) {
      try {
        if (await this.verificarDisponibilidad(nuevaReserva)) {
          if (this.editar) {
            await custom_axios.put(`/apiv1/reservaaula/${nuevaReserva.id}`, nuevaReserva);
          } else {
            await custom_axios.post('/apiv1/reservaaula', nuevaReserva);
          }
          this.mostrarSnackbar('Reserva guardada con éxito', 'success');
          this.todasLasReservasAula();
          this.mostrarAbmReservaAula = false;
        } else {
          this.mostrarSnackbar('El aula ya está reservada para ese horario en el mismo día', 'error');
        }
      } catch (error) {
        console.error(error);
        this.mostrarSnackbar('Error al guardar la reserva', 'error');
      }
    },
    async verificarDisponibilidad(nuevaReserva) {
      try {
        const response = await custom_axios.get('/apiv1/reservaaula');
        const reservasExistentes = response.data;

        const conflicto = reservasExistentes.some(reserva => 
          reserva.id_aula === nuevaReserva.id_aula &&
          reserva.id !== nuevaReserva.id &&
          this.hayConflictoHorario(reserva, nuevaReserva)
        );

        return !conflicto;
      } catch (error) {
        console.error(error);
        return false;
      }
    },
    hayConflictoHorario(reserva1, reserva2) {
      const desde1 = new Date(reserva1.fh_desde);
      const hasta1 = new Date(reserva1.fh_hasta);
      const desde2 = new Date(reserva2.fh_desde);
      const hasta2 = new Date(reserva2.fh_hasta);

      const esMismoDia = desde1.toDateString() === desde2.toDateString();

      if (esMismoDia) {
        return (desde1 < hasta2 && desde2 < hasta1);
      }

      return false;
    },
     cerrarDialog() {
    this.mostrarAbmReservaAula = false;
    // Aquí puedes agregar cualquier otra lógica necesaria al cerrar el diálogo
  },
    navigateToHome() {
      this.$router.push('/');
    },
    confirmarEliminarReservaAula(reserva) {
      this.reservaAulaAEliminar = reserva;
      this.mostrarConfirmacion = true;
    },
    async eliminarReservaAulaConfirmada() {
      if (this.reservaAulaAEliminar) {
        try {
          await custom_axios.delete(`/apiv1/reservaaula/${this.reservaAulaAEliminar.id}`);
          this.mostrarSnackbar('Reserva eliminada con éxito', 'success');
          this.todasLasReservasAula();
          this.mostrarConfirmacion = false;
        } catch (error) {
          console.error(error);
          this.mostrarSnackbar('Error al eliminar la reserva', 'error');
        }
      }
    },
    mostrarSnackbar(texto, color) {
      this.snackbarText = texto;
      this.snackbarColor = color;
      this.snackbar = true;
    },
    mostrarDetallesReserva(reserva) {
      this.detallesReserva = reserva;
      this.mostrarDetalles = true;
    },
    mostrarDetallesEvento(event) {
      const reserva = this.listadoReservaAula.find(r => 
        new Date(r.fh_desde).getTime() === event.start.getTime() &&
        new Date(r.fh_hasta).getTime() === event.end.getTime()
      );
      if (reserva) {
        this.mostrarDetallesReserva(reserva);
      }
    },
    formatDateTime(dateTimeString) {
      const date = new Date(dateTimeString);
      return `${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
    },
    formatDate(dateTimeString) {
      const date = new Date(dateTimeString);
      return date.toLocaleDateString();
    },
    formatTime(dateTimeString) {
      const date = new Date(dateTimeString);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
  },
  created() {
    this.todasLasReservasAula();
  },
};
</script>
