<script>
    import Replies from './Replies.vue';
    import Subscribe from './Subscribe.vue';

    export default {
        props: ['initialRepliesCount'],

        components: {
            subscribe: Subscribe,
            replies: Replies
        },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        }
    }
</script>