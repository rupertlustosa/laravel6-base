<template>
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" data-backdrop="static"
         id="searchDocumentNumber" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form v-on:submit.prevent>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Insira o CPF</h5>
                        <!--<button aria-label="Fechar" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>-->
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <the-mask :mask="maskDocumentNumber" :masked="masked" @keyup.native="search"
                                          class="form-control" placeholder="Entre com o CPF"
                                          id="document_number"
                                          type="tel" v-model="document_number"></the-mask>
                                <form-error-component :errors="errors" v-if="errors.document_number">
                                    {{ errors.document_number[0] }}
                                </form-error-component>
                            </div>

                            <div class="form-group col-md-12">
                                <!--<label>Nome do Cliente</label>-->
                                <input class="form-control" placeholder="Nome do Cliente"
                                       type="text"
                                       v-model="name">
                                <form-error-component :errors="errors" v-if="errors.name">
                                    {{ errors.name[0] }}
                                </form-error-component>
                            </div>

                            <div class="form-group col-md-12">
                                <!--<label>Data de Nascimento</label>-->
                                <the-mask :mask="maskBirth" :masked="masked" class="form-control"
                                          placeholder="Data de Nascimento"
                                          type="tel" v-model="birth"></the-mask>
                                <form-error-component :errors="errors" v-if="errors.birth">
                                    {{ errors.birth[0] }}
                                </form-error-component>
                            </div>

                            <div class="form-group col-md-12">
                                <!--<label>Telefone</label>-->
                                <the-mask :mask="maskPhone" :masked="masked" class="form-control"
                                          placeholder="Telefone"
                                          type="tel" v-model="phone"></the-mask>
                                <form-error-component :errors="errors" v-if="errors.phone">
                                    {{ errors.phone[0] }}
                                </form-error-component>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="document_number=''" class="btn btn-secondary" data-dismiss="modal"
                                type="button">
                            Cancelar
                        </button>
                        <button @click="save" class="btn btn-primary" type="button" v-if="showButtonCreate">Pontuar!
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {TheMask} from 'vue-the-mask';

    export default {
        name: "PointingDocumentNumberComponent",
        components: {TheMask},
        data() {
            return {
                document_number: '',
                name: '',
                birth: '',
                phone: '',
                errors: {},
                masked: true,
                maskPhone: '(##)#####-####',
                maskDocumentNumber: '###.###.###-##',
                maskBirth: '##/##/####',
                showButtonCreate: false,
            }
        },
        methods: {
            save() {

                this.$loading(true);

                /*axios.request('/api/save/sale/' + this.$store.state.selectedSale.id, {
                    method: 'POST',
                    params: {document_number: this.document_number},
                })*/
                axios.post('/api/save/sale/' + this.$store.state.selectedSale.id, {
                    document_number: this.document_number,
                    _method: 'PUT'
                })
                    .then(response => {

                        jQuery('#searchDocumentNumber').modal('hide');
                        this.$store.commit('clearSelectedNozzle');

                        let message = 'Pontuado com sucesso!';
                        this.$awn.success(message);

                        this.document_number = '';
                        this.showButtonCreate = false;
                    })
                    .catch(error => {

                        if (_.has(error, 'response.data.errors')) {

                            this.$awn.warning('Ocorreu um erro ao salvar');
                            this.errors = error.response.data.errors;
                        } else {

                            let message = '[' + error.response.status + '] ' + 'Error.....';
                            this.$awn.alert(message);
                        }

                        //console.log(error);
                    })
                    .then(() => {

                        this.$loading(false);
                    });

            },
            search() {

                let length = this.document_number.length;

                this.showButtonCreate = false;
                this.errors = {};

                if (length == 14) {

                    if (this.validateDocumentNumber()) {

                        this.showButtonCreate = true;

                        if (confirm('Confirma pontuar para esse CPF')) {

                            this.save();
                        }

                    } else {
                        alert('O CPF informado é inválido');
                        this.document_number = '';
                    }
                }

            },
            validateDocumentNumber() {

                var cpf = this.document_number;

                cpf = cpf.replace(/[^\d]+/g, '');
                if (cpf == '') return false;
                // Elimina CPFs invalidos conhecidos
                if (cpf.length != 11 ||
                    cpf == "00000000000" ||
                    cpf == "11111111111" ||
                    cpf == "22222222222" ||
                    cpf == "33333333333" ||
                    cpf == "44444444444" ||
                    cpf == "55555555555" ||
                    cpf == "66666666666" ||
                    cpf == "77777777777" ||
                    cpf == "88888888888" ||
                    cpf == "99999999999")
                    return false;
                // Valida 1o digito
                let add = 0;
                for (let i = 0; i < 9; i++) {

                    add += parseInt(cpf.charAt(i)) * (10 - i);
                }

                let rev = 11 - (add % 11);
                if (rev == 10 || rev == 11) {

                    rev = 0;
                }

                if (rev != parseInt(cpf.charAt(9))) {

                    return false;
                }
                // Valida 2o digito
                add = 0;
                for (let i = 0; i < 10; i++) {

                    add += parseInt(cpf.charAt(i)) * (11 - i);
                }

                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11) {

                    rev = 0;
                }

                if (rev != parseInt(cpf.charAt(10))) {

                    return false;
                }

                return true;
            },
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>
