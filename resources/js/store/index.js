import Vue from 'vue';
import Vuex from 'vuex';

import locale from './modules/locale';


Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        locale,
    },
    strict: debug,
});