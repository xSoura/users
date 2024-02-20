<template>
  <v-app>
    <v-container>
      <v-card>
        <v-card-title>
          Users
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="openCreateDialog">Add User</v-btn>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        </v-card-title>

        <v-data-table :headers="headers" :items="users" :search="search" class="elevation-1">
          <template v-slot:item.actions="{ item }">
            <v-btn icon color="yellow" @click="openEditDialog(item)">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <v-btn icon color="red" @click="confirmDeletion(item)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
        </v-data-table>
      </v-card>
    </v-container>

    <v-dialog v-model="dialog" persistent max-width="600px">
      <UserForm :userData="selectedUser" :isEditMode="isEditMode" @close-dialog="closeDialog" @user-added="fetchUsers" />
    </v-dialog>

    <ConfirmDialog
      :title="'Are you sure?'"
      :message="'Do you want to delete this user?'"
      :showDialog="confirmDialog"
      @update:showDialog="confirmDialog = $event"
      @confirmed="deleteUser"
    />
  </v-app>
</template>

<script setup>
definePageMeta({
  middleware: 'auth'
});
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useCookie } from '#app';
import UserForm from '../components/UserForm.vue';
import ConfirmDialog from '../components/ConfirmDialog.vue';
const runtimeConfig = useRuntimeConfig();
const apiUrl = runtimeConfig.public.API_URL;
const search = ref('');
const headers = ref([
  { text: 'ID', value: 'id' },
  { text: 'Name', value: 'name' },
  { text: 'Email', value: 'email' },
  { text: 'Actions', value: 'actions', sortable: false }
]);
const users = ref([]);
const dialog = ref(false);
const confirmDialog = ref(false);
const selectedUser = ref({});
const isEditMode = ref(false);
const userToDelete = ref(null);
const tokenCookie = useCookie('token');

const fetchUsers = async () => {
  try {
    const token = tokenCookie.value;
    const response = await axios.get(`${apiUrl}/api/users`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

onMounted(fetchUsers);

const openCreateDialog = () => {
  selectedUser.value = {};
  isEditMode.value = false;
  dialog.value = true;
};

const openEditDialog = (user) => {
  selectedUser.value = user;
  isEditMode.value = true;
  dialog.value = true;
};

const confirmDeletion = (user) => {
  userToDelete.value = user;
  confirmDialog.value = true;
};

const deleteUser = async () => {
  try {
    await axios.delete(`${apiUrl}/api/users/${userToDelete.value.id}`, {
      headers: {
        Authorization: `Bearer ${tokenCookie.value}`
      }
    });
    fetchUsers();
  } catch (error) {
    console.error('Error deleting user:', error);
  }
};

const closeDialog = () => {
  dialog.value = false;
  selectedUser.value = {};
  isEditMode.value = false;
};
</script>

<style>
.v-container {
  margin-top: 20px;
}

.v-card-title {
  padding: 16px;
}
</style>
