require('./bootstrap');

import Vue from 'vue';
import VueToastr2 from 'vue-toastr-2'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'
require('vue2-animate/dist/vue2-animate.min.css')
import swal from 'sweetalert2'

window.swal = swal;

window.toastr = require('toastr')

Vue.use(VueToastr2)

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('categoria', require('./components/Categoria').default);
Vue.component('articulo', require('./components/Articulo').default);


const app = new Vue({
    el: '#app',
    data:{
    	menu: 0
    }
});
