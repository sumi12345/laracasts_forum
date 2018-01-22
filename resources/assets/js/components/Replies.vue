<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="removeElement(index)"></reply>
        </div>

        <new-reply @created="add" :endpoint="endpoint"></new-reply>
    </div>
</template>

<script>

    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';

    export default {
        props: ['data'],

        components: {
            reply: Reply,
            newReply: NewReply
        },

        data() {
            return {
                items: this.data,
                endpoint: location.pathname + '/replies'
            }
        },

        methods: {
            // remove 是保留字, remove 方法改为 removeElement 方法
            removeElement(index) {
                this.items.splice(index, 1);

                this.$emit('removed');
            },

            add(reply) {
                this.items.push(reply);

                this.$emit('added');
            }
        }
    }
</script>