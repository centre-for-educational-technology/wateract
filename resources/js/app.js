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
import 'leaflet/dist/leaflet.css';
import { L, Icon } from 'leaflet';
import DataTable from 'laravel-vue-datatable';
import TailablePagination from 'tailable-pagination';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';
import store from "./Modules/store";
import VueGtag from "vue-gtag";

Vue.use(VueInternationalization);
const lang = localStorage.getItem('locale') || 'et';

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(Vuex)
Vue.use(ElementUI);
Vue.use(LaravelPermissionToVueJS);
Vue.use(require('vue-moment'));
Vue.use(DataTable);
Vue.use(TailablePagination);

Vue.use(GmapVue, {
    load: {
        key: process.env.MIX_GOOGLE_MAPS_API_KEY,
        libraries: 'places', // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
    },
    installComponents: true
});
Vue.use(VueLodash, { name: 'custom' , lodash: lodash })

Vue.use(VueGtag, {
    config: { id: process.env.MIX_GOOGLE_ANALYTICS_KEY }
});

const app = document.getElementById('app');

new Vue({
    i18n,
    store,
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
