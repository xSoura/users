<template>
    <v-app>
        <v-main>
            <v-container class="fill-height" style="display: flex; align-items: center; justify-content: center;">
                <v-row justify="center">
                    <v-col cols="12" sm="10" md="6">
                        <v-card>
                            <v-card-title class="headline">Log in</v-card-title>
                            <v-card-text>
                                <v-form @submit.prevent="login">
                                    <v-text-field v-model="email" label="Email Address" required></v-text-field>
                                    <v-text-field v-model="password" label="Password" type="password"
                                        required></v-text-field>
                                    <v-alert v-if="errorMessage" type="error" class="mb-4">
                                        {{ errorMessage }}
                                    </v-alert>
                                    <v-btn @click="login" color="primary" block>Log in</v-btn>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
  
<style>
</style>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useCookie } from '#app';
const runtimeConfig = useRuntimeConfig();
const apiUrl = runtimeConfig.public.API_URL;
const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();
const tokenCookie = useCookie('token');
const login = async () => {
    try {
        const response = await axios.post(`${apiUrl}/api/login`, {
            email: email.value,
            password: password.value,
        });

        if (response.data.token) {
            tokenCookie.value = response.data.token; 
            router.push('/users');
        }
    } catch (error) {
        if (error.response && error.response.status === 401) {
            errorMessage.value = 'Invalid credentials.';
        } else {
            errorMessage.value = 'An error occurred while trying to log in.' + error;
        }
    }
};
</script>
