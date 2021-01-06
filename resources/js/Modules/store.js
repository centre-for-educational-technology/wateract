import Vue from 'vue'
import Vuex from 'vuex'
import locale from "./locale";

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        locale
    },
});
