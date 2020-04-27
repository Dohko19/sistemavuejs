require('./bootstrap');

import Vue from 'vue';
require('vue2-animate/dist/vue2-animate.min.css')
import swal from 'sweetalert2'
window.swal = swal;


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('categoria', require('./components/Categoria').default);


const app = new Vue({
    el: '#app',
    data:{
    	menu: 0
    }
});
