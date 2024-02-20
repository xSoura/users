<!-- resources/js/components/LoginForm.vue -->

<template>
    <v-app>
        <v-main>
            <v-container class="fill-height" style="display: flex; align-items: center; justify-content: center;">
                <v-row justify="center">
                    <v-col cols="12" sm="10" md="6">
                        <v-card>
                            <v-card-title class="headline">Inicio de Sesión</v-card-title>
                            <v-card-text>
                                <v-form @submit.prevent="login">
                                    <v-text-field v-model="email" label="Correo electrónico" required></v-text-field>
                                    <v-text-field v-model="password" label="Contraseña" type="password"
                                        required></v-text-field>
                                    <v-alert v-if="errorMessage" type="error" class="mb-4">
                                        {{ errorMessage }}
                                    </v-alert>
                                    <v-btn @click="login" color="primary" block>Iniciar Sesión</v-btn>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Cookies from 'js-cookie';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const login = async () => {
    try {
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value,
        });

        if (response.data.token) {
            //redireccionar a la ruta dashboard si el login es correcto
            //window.location.href = '/dashboard';
            const token = response.data.token;
            // Guardar el token en una cookie con una duración de 1 día (por ejemplo)
            /* Cookies.set('tokeniz', response.data.token, { expires: 1/24 }); // 1/24 es aproximadamente 1 hora
            document.cookie = `token=${response.data.token}; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/`; */
            // Redireccionar al usuario a la ruta del dashboard
            //window.location.href = '/dashboard';
            console.log(token)
            checktoken(token)
        }
    } catch (error) {
        if (error.response && error.response.status === 401) {
            // Error de credenciales no válidas
            errorMessage.value = 'Las credenciales no son válidas.';
        } else {
            // Otro tipo de error
            errorMessage.value = 'Ocurrió un error al intentar iniciar sesión.' + error;
        }
    }
};


const checktoken = async (token)=>{
    try {
        const response = await fetch('/api/validate-token', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error('Error:', error);
    }
    
}
</script>
