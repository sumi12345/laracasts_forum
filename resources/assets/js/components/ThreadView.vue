<script>
    import Replies from './Replies.vue';
    import Subscribe from './Subscribe.vue';

    export default {
        props: ['thread'],

        components: {
            subscribe: Subscribe,
            replies: Replies
        },

        data() {
            return {
                locked: this.thread.locked,
                repliesCount: this.thread.replies_count,
                editing: false,
            }
        },

        methods: {
            toggleLock() {
                this.locked = ! this.locked;
            }
        },
    }
</script>