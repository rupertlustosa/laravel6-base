<template>
    <div>
        <v-select :filterable="false"
                  :id="`div_`+name"
                  :name="`div_`+name"
                  :options="cityOptions"
                  :reduce="option => option.id"
                  @search="onSearch"
                  class="style-chooser"
                  label="name"
                  placeholder="Pesquise a cidade"
                  v-model="city_id">
            <template slot="no-options">
                Digite o nome da cidade para começar a pesquisa...
            </template>
        </v-select>

        <input :id="name" :name="name" type="text" v-model="city_id">
    </div>
</template>

<script>
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    import _ from 'lodash';

    export default {
        name: "SelectCityComponent",
        components: {vSelect},
        props: {
            initialValue: {
                type: Number,
                required: true
            },
            name: {
                type: String,
                required: true
            },
            maxLength: {
                type: Number,
                required: false,
                default: 6
            },
        },
        data() {
            return {
                city_id: '',
                cityOptions: [],
            }
        },
        methods: {

            getData() {

                if (this.city_id != '') {

                    this.$loading(true);
                    console.log(this.city_id);
                    axios.request('/api/cities?id=' + this.city_id, {
                        method: 'GET',
                        params: this.form,
                    })
                        .then((response) => {

                            /*let data = response.data[0];
                            console.log(data);

                            let cityOptions = {
                                id: data.ibge,
                                city: data.localidade,
                                state: data.uf,
                                initials: data.uf,
                                name: data.localidade + " (" + data.uf + ")",
                            };*/

                            this.cityOptions = response.data;
                        })
                        .catch(error => {

                            let message = '';
                            if (_.has(error, 'response.data.friendly_message')) {

                                message = error.response.status + ' - ' + error.response.data.friendly_message;

                            } else if (_.has(error, 'response.status')) {

                                message = 'Requisição falhou com código de status ' + error.response.status;

                            } else {

                                message = 'Aplicação falhou com erro: ' + error.message;
                                console.log(error);
                            }

                            showMessage('w', message);

                        })
                        .then(() => {

                            this.$loading(false);
                        });
                }
            },
            onSearch(search, loading) {
                loading(true);
                if (search.length) {

                    this.search(loading, search, this);
                } else {

                    loading(false);
                }
            },
            search: _.debounce((loading, search, vm) => {
                fetch(
                    `/api/cities?search=${escape(search)}`
                ).then(res => {
                    res.json().then(json => (vm.cityOptions = json));
                    loading(false);
                });
            }, 350)
        },
        mounted() {

            if (this.initialValue != '') {

                this.city_id = this.initialValue;
                this.getData();
            }
        },
    }
</script>

<style scoped>

</style>
