require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import Vuex from 'vuex';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import * as GmapVue from 'gmap-vue';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs';

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(Vuex)
Vue.use(ElementUI);
Vue.use(LaravelPermissionToVueJS);

Vue.use(GmapVue, {
    load: {
        key: process.env.MIX_GOOGLE_MAPS_API_KEY,
        libraries: 'places', // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
    },
    installComponents: true
});
Vue.use(VueLodash, { name: 'custom' , lodash: lodash })

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
