<template>
    <money :id="name" :maxlength="maxLength" :name="name" class="form-control"
           v-bind="money"
           v-model="value">
    </money>
</template>

<script>
    import {Money} from 'v-money';

    export default {
        name: "FloatMaskComponent",
        components: {Money},
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
                value: 0,
                money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '',
                    suffix: '',
                    precision: 2,
                    masked: false
                },
            }
        },
        mounted() {

            this.value = this.initialValue;
        },
    }
</script>

<style scoped>

</style>
