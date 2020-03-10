import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({

    state: {
        selectedNozzle: null,
        selectedSale: null,
    },
    mutations: {
        setSelectedNozzle(state, nozzle) {
            state.selectedNozzle = nozzle;
            console.log('setSelectedNozzle = ', state.selectedNozzle.name);
        },
        clearSelectedNozzle(state) {
            state.selectedNozzle = null;
            console.log('setSelectedNozzle = ', state.selectedNozzle);
        },
        setSelectedSale(state, sale) {
            console.log(sale);
            state.selectedSale = sale;
            console.log('setSelectedSale = ', state.selectedSale.id);
        },
        clearSelectedSale(state) {
            state.selectedSale = null;
            console.log('clearSelectedSale = ', state.selectedSale);
        },
    },
    actions: {}
});
