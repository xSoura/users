<template>
    <v-dialog v-model="dialog" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">{{ title }}</v-card-title>
        <v-card-text>{{ message }}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="cancel">No</v-btn>
          <v-btn color="red darken-1" text @click="confirm">Sí</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { ref, watch, defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    title: String,
    message: String,
    showDialog: Boolean
  });
  
  const emit = defineEmits(['update:showDialog', 'confirmed']);
  
  const dialog = ref(props.showDialog);
  
  watch(() => props.showDialog, (newVal) => {
    dialog.value = newVal;
  });
  
  const cancel = () => {
    dialog.value = false;
    emit('update:showDialog', false);
  };
  
  const confirm = () => {
    dialog.value = false;
    emit('update:showDialog', false);
    emit('confirmed');
  };
  </script>
  