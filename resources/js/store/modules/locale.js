const state = {
    locale: 'en'
};

const mutations = {
    SET_LOCALE(state, locale) {
        state.locale = locale;
    }
};

const actions = {
    set_locale({commit,state}, locale) {
        commit('SET_LOCALE', locale);
    }
};


const getters = {
    getLocale: state => state.locale,
};

export default {
    state,
    getters,
    actions,
    mutations
};


