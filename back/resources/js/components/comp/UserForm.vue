<template>
    <v-card>
      <v-card-title>
        <span class="headline">{{ isEditMode ? 'Editar Usuario' : 'Agregar Usuario' }}</span>
      </v-card-title>
      <v-card-text>
        <!-- Indicador de carga -->
        <div v-if="isLoading">
          Cargando...
        </div>
        <v-form ref="form" v-model="valid">
          <v-text-field v-model="user.name" label="Nombre" :rules="[v => !!v || 'El nombre es requerido']" required></v-text-field>
          <v-text-field v-model="user.email" label="Correo electrónico"
            :rules="[v => !!v || 'El correo es requerido', v => /.+@.+\..+/.test(v) || 'Correo no válido']" required></v-text-field>
          <v-text-field v-model="user.password" label="Contraseña" type="password" 
            :rules="[v => !isEditMode || v, v => !!v || 'La contraseña es requerida']" :required="!isEditMode"></v-text-field>
        </v-form>
        <div v-if="alertMessage">
          <p :class="`alert ${alertType}`">{{ alertMessage }}</p>
        </div>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="closeForm">Cancelar</v-btn>
        <v-btn color="blue darken-1" text @click="saveUser">{{ isEditMode ? 'Actualizar' : 'Guardar' }}</v-btn>
      </v-card-actions>
    </v-card>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import axios from 'axios';
/*   import { useCookie } from '#app'; */
  
  const props = defineProps({
    isEditMode: Boolean,
    userData: Object
  });
  
  const emit = defineEmits(['user-added', 'close-dialog']);
  const valid = ref(false);
  const user = ref({
    name: '',
    email: '',
    password: ''
  });
  const isLoading = ref(false);
  const alertMessage = ref('');
  const alertType = ref('');
  const tokenCookie = useCookie('token');
  watch(() => props.userData, (newVal) => {
    console.log(props.userData, newVal)
    if (newVal && props.isEditMode) {
      user.value = { ...newVal, password: '' };
    }
  }, { immediate: true });
  
  const saveUser = async () => {console.log("EDIT MODE ",valid.value)
      isLoading.value = true;
      alertMessage.value = '';
      try {
        let response;
        const token = tokenCookie.value;
        const config = {
          headers: { Authorization: `Bearer ${token}` }
        };
        console.log("EDIT MODE ",props.isEditMode)
        if (props.isEditMode) {
          // Lógica de actualización
          let updateData = { name: user.value.name, email: user.value.email };
          if (user.value.password) updateData.password = user.value.password;
          response = await axios.put(`/api/users/${user.value.id}`, updateData, config);
          alertMessage.value = 'Usuario actualizado con éxito.';
        } else {
          // Lógica de creación
          response = await axios.post('/api/users', user.value);
          alertMessage.value = 'Usuario registrado con éxito.';
        }
        alertType.value = 'success';
        emit('user-added');
        setTimeout(() => closeForm(), 1000);
      } catch (error) {
        console.error('Error:', error);
        alertType.value = 'error';
        alertMessage.value = 'Error al procesar la solicitud.';
      } finally {
        isLoading.value = false;
      }
  };
  
  const closeForm = () => {
    resetForm();
    emit('close-dialog');
  };
  
  const resetForm = () => {
    user.value = { name: '', email: '', password: '' };
    alertMessage.value = '';
    valid.value = false;
    isLoading.value = false;
  };
  </script>
  
  <style>
  .alert {
    padding: 10px;
    margin: 10px 0;
  }
  .alert.success {
    background-color: lightgreen;
  }
  .alert.error {
    background-color: lightcoral;
  }
  </style>
  