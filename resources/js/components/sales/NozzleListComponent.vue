<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-12">

                    <a class="col-sm-12 col-md-4 col-lg-1 btn btn-default btn-lg" href="javascript:"
                       role="button"
                       v-for="(nozzle, index) in nozzles">
                        <span class="fa-stack fa-3x">
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
        </div>
    </div>
</template>

<script>

    export default {
        name: "NozzleListComponent",
        data() {
            return {
                nozzles: [],
            }
        },
        methods: {
            getData() {

                axios.get("/api/sales-to-pointing")
                    .then((response) => {

                        let data = response.data.data;
                        this.nozzles = [...data];

                        //console.log(response);
                        /*let data = response.data.data;

                        let salesGroupedByFuelPump = groupBy(data, function (n) {
                            return n.fuel_pump;
                        });

                        this.nozzles = salesGroupedByFuelPump || [];*/
                    })
                    .catch(error => {

                        console.log(error);
                        //let message = '[' + error.response.status + '] Não foi possível realizar essa operação!';
                        //this.$awn.alert(message);
                    })
                    .then(() => {

                        //this.$loading(false);
                        console.log(this.nozzles);
                    });
            }
        },
        mounted() {

            this.getData(1);
        }
    }
</script>

<style scoped>

</style>
