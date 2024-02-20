<template>
  <v-card>
    <v-card-title>
      <span class="headline">{{ isEditMode ? 'Edit User' : 'Add User' }}</span>
    </v-card-title>
    <v-card-text>
      <div v-if="isLoading">
        Loading...
      </div>
      <v-form ref="form" v-model="valid">
        <v-text-field v-model="user.name" label="Name" :rules="[v => !!v || 'Name is required']" required></v-text-field>
        <v-text-field v-model="user.email" label="Email"
          :rules="[v => !!v || 'Email is required', v => /.+@.+\..+/.test(v) || 'Invalid email']" required></v-text-field>
        <v-text-field v-model="user.password" label="Password" type="password" 
          :rules="[v => !isEditMode || v, v => !!v || 'Password is required']" :required="!isEditMode"></v-text-field>
      </v-form>
      <div v-if="alertMessage">
        <p :class="`alert ${alertType}`">{{ alertMessage }}</p>
      </div>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="blue darken-1" text @click="closeForm">Cancel</v-btn>
      <v-btn color="blue darken-1" text @click="saveUser">{{ isEditMode ? 'Update' : 'Save' }}</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import { useCookie } from '#app';
const runtimeConfig = useRuntimeConfig();
const apiUrl = runtimeConfig.public.API_URL;
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
  if (newVal && props.isEditMode) {
    user.value = { ...newVal, password: '' };
  }
}, { immediate: true });

const saveUser = async () => {
    isLoading.value = true;
    alertMessage.value = '';
    try {
      let response;
      const token = tokenCookie.value;
      const config = {
        headers: { Authorization: `Bearer ${token}` }
      };
      if (props.isEditMode) {
        let updateData = { name: user.value.name, email: user.value.email };
        if (user.value.password) updateData.password = user.value.password;
        response = await axios.put(`${apiUrl}/api/users/${user.value.id}`, updateData, config);
        alertMessage.value = 'User updated successfully.';
      } else {
        response = await axios.post(`${apiUrl}/api/users`, user.value);
        alertMessage.value = 'User registered successfully.';
      }
      alertType.value = 'success';
      emit('user-added');
      setTimeout(() => closeForm(), 1000);
    } catch (error) {
      alertType.value = 'error';
      alertMessage.value = error.response.data.message;
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
