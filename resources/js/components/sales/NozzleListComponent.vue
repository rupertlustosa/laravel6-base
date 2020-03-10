<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row" v-if="!this.$store.state.selectedNozzle">
                <div class="col-12">

                    <a @click="selectNozzle(nozzle)"
                       class="col-sm-12 col-md-6 col-lg-2 btn btn-default btn-lg ml-3 mr-3 mb-3 mt-3"
                       href="javascript:"
                       role="button" v-for="(nozzle, index) in nozzles">
                        <span class="fa-stack fa-5x">
                            <!-- The icon that will wrap the number -->
                            <span class="fa fa-square-o fa-stack-2x"></span>
                            <!-- a strong element with the custom content, in this case a number -->
                            <strong class="fa-stack-1x">
                                {{ nozzle.name }}
                            </strong>
                        </span>
                    </a>
                </div>
            </div>
            <template v-if="this.$store.state.selectedNozzle">
                <div class="row">
                    <div class="col-12 mb-2">
                        <button @click="unselectNozzle" class="btn btn-primary" title="Exibe os bicos disponíveis"
                                type="button">
                            <i class="fa fa-list-ol"></i> Listar Bicos
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>Abastecimento</th>
                                    <th>Atendente</th>
                                    <th style="width: 20%">Data</th>
                                    <th style="width: 15%">Valor</th>
                                    <th style="width: 15%">Quantidade</th>
                                    <th style="width: 15%">V. Unitário</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in this.$store.state.selectedNozzle.sales">
                                    <td>
                                        <a @click="selectSale(item)" class="btn btn-primary btn-rounded"
                                           data-target="#searchDocumentNumber" data-toggle="modal"
                                           href="javascript:">
                                            Pontuar essa venda
                                            <!--{{ item.sale }}-->
                                        </a>
                                    </td>
                                    <td>{{ item.attendant }}</td>
                                    <td>{{ item.date }}</td>
                                    <td>{{ item.value }}</td>
                                    <td>{{ item.item_quantity }}</td>
                                    <td>{{ item.item_unit_price }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </template>

            <pointing-document-number-component v-if=""></pointing-document-number-component>

        </div>
    </div>
</template>

<script>

    import PointingDocumentNumberComponent from "./PointingDocumentNumberComponent";

    export default {
        name: "NozzleListComponent",
        components: {PointingDocumentNumberComponent},
        data() {
            return {
                nozzles: [],
                clickToPointingWithDocumentNumber: false,
            }
        },
        methods: {
            getData() {

                axios.get("/api/sales-to-pointing")
                    .then((response) => {

                        let data = response.data.data;
                        this.nozzles = [...data];
                    })
                    .catch(error => {

                        console.log(error);
                    })
                    .then(() => {

                        console.log(this.nozzles);
                    });
            },
            selectNozzle(nozzle) {

                console.log('>>>>>>>', nozzle);
                this.$store.commit('setSelectedNozzle', nozzle);
            },
            selectSale(sale) {

                console.log('>>>>>>>', sale);
                this.$store.commit('setSelectedSale', sale);
            },
            unselectNozzle() {

                this.$store.commit('clearSelectedNozzle');
            }
        },
        mounted() {

            this.getData(1);
        },
        created: function () {

            this.getData(1);
            /*var self = this;
            setInterval(function () {
                self.getData(1)
            }, 1500);*/
        }
    }
</script>

<style scoped>

</style>
