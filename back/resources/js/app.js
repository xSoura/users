/* import './bootstrap';
import { createApp } from "vue";
import app from "./layouts/app.vue";
import vuetify from "./vuetify";
createApp(app).use(vuetify).mount("#app"); */
import './bootstrap';
import { createApp } from "vue/dist/vue.esm-bundler.js";
import vuetify from "./vuetify";
import LoginComponent from "./components/login.vue";
import DashboardComponent from "./components/dashboard.vue";

createApp({
  components: {
    LoginComponent,
    DashboardComponent
  }
}).use(vuetify)
.mount("#app");