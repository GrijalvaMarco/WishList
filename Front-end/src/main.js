import Vue from "vue"
import App from "./App.vue"
import router from "./router"
import { BootstrapVue } from 'bootstrap-vue'
import "./sass/main.scss"
import VueSimpleAlert from "vue-simple-alert";

Vue.use(VueSimpleAlert);
require('./plugins/fontawesome');

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
Vue.config.productionTip = false

Vue.prototype.$apiUrl = 'http://127.0.0.1:8000/api'

new Vue({
	router,
	render: h => h(App)
}).$mount("#app")
