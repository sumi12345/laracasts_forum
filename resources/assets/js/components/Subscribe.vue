<template>
    <button :class="classes" @click="subscribe" v-text="btntxt"></button>
</template>

<script>
    export default {
        name: "subscribe",

        props: ['active'],

        data() {
            return {
                isSubscribedTo: this.active
            }
        },

        computed: {
            classes() {
                return ['btn', this.isSubscribedTo ? 'btn-default' : 'btn-primary'];
            },

            btntxt() {
                return this.isSubscribedTo ? '取消订阅' : '订阅';
            }
        },

        methods: {
            subscribe() {
                let method = this.isSubscribedTo ? 'delete' : 'post';
                axios[method](location.pathname + '/subscriptions');
                this.isSubscribedTo = ! this.isSubscribedTo;
            }
        }
    }
</script>

<style scoped>

</style>