<script>
    import Replies from './Replies.vue';
    import Subscribe from './Subscribe.vue';

    export default {
        props: ['dataRepliesCount', 'dataLocked'],

        components: {
            subscribe: Subscribe,
            replies: Replies
        },

        data() {
            return {
                // 如何将 locked 状态传给 ThreadView->Replies->NewReply:
                // 1. pass down property; 2. event; 3. shared state; 4. vuex; 5. read $parent.locked;
                locked: this.dataLocked,
                repliesCount: this.dataRepliesCount,
            }
        }
    }
</script>