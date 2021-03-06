require('./bootstrap');

import Vue from 'vue';
import VueToastr2 from 'vue-toastr-2'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'
require('vue2-animate/dist/vue2-animate.min.css')
import swal from 'sweetalert2'

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
window.$ = window.jQuery = require('jquery');
Vue.component('v-select', vSelect)

window.swal = swal;

window.toastr = require('toastr')

Vue.use(VueToastr2)

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('categoria', require('./components/Categoria').default);
Vue.component('articulo', require('./components/Articulo').default);
Vue.component('cliente', require('./components/Cliente').default);
Vue.component('proveedor', require('./components/Proveedor').default);
Vue.component('rol', require('./components/Role').default);
Vue.component('user', require('./components/User').default);
Vue.component('ingreso', require('./components/Ingreso').default);
Vue.component('venta', require('./components/Venta').default);
Vue.component('dashboard', require('./components/Dashboard').default);
Vue.component('consulta-ingreso', require('./components/ConsultaIngreso').default);
Vue.component('consulta-venta', require('./components/ConsultaVenta').default);
Vue.component('notification', require('./components/Notification').default);


const app = new Vue({
    el: '#app',
    data:{
    	menu: 0,
        notifications: []
    },
    created() {
        let me = this;
        axios.get('/notification/get').then(res => {
            me.notifications = res.data;
        }).catch(err => {
            console.log(err);
        });
        var userId = $('meta[name="userId"]').attr('content');

        Echo.private('App.User.'+userId).notification((notification) => {
            console.log(notification);
            me.notifications.unshift(notification)
        });
    }
});
