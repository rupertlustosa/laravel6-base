import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({

    state: {
        selectedNozzle: null,
        selectedSale: null,
        apiUrl: null,
    },
    mutations: {
        setSelectedNozzle(state, nozzle) {
            state.selectedNozzle = nozzle;
            console.log('# Store # setSelectedNozzle = ', state.selectedNozzle.name);
        },
        clearSelectedNozzle(state) {
            state.selectedNozzle = null;
            console.log('# Store # clearSelectedNozzle = ', state.selectedNozzle);
        },
        setSelectedSale(state, sale) {
            state.selectedSale = sale;
            console.log('# Store # setSelectedSale = ', state.selectedSale.id);
        },
        clearSelectedSale(state) {
            state.selectedSale = null;
            console.log('# Store # clearSelectedSale = ', state.selectedSale);
        },
        setApiUrl(state, apiUrl) {
            state.apiUrl = apiUrl;
            console.log('# Store # setApiUrl = ', state.apiUrl);
        },
    },
    actions: {}
});
