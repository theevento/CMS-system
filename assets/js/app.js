import Vue from 'vue';
import BootstrapVue from "bootstrap-vue";
import icons from 'material-icons';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import axios from 'axios';
import VueCkeditor from 'vue-ckeditor2';
import homepage from  './components/homepage';
import post from './components/post';
import login from './components/login';
import adminnav from './components/adminnav';
import articlevue from './components/articlevue';
import articlelist from './components/articlelist';
import pagevue from './components/pagevue';
import pagelist from './components/pagelist';
import menuvue from './components/menuvue';
import menulist from './components/menulist';
import tagsearch from './components/tagsearch';

require('../scss/main.scss');
window.axios = axios;

Vue.use(BootstrapVue);
Vue.use(VueCkeditor);
Vue.use(require('vue-moment'));

const app = new Vue({
    el: '.app',
    components: {
        homepage,
        login,
        post,
        articlevue,
        adminnav,
        articlelist,
        pagevue,
        pagelist,
        menuvue,
        menulist,
        tagsearch
    }
});
