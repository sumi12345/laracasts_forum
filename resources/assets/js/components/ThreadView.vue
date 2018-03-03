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
                endpoint: '/threads/' + this.thread.channel.slug + '/' + this.thread.id,
                editing: false,
                title: this.thread.title,
                body: this.thread.body,
                form: {
                    title: this.thread.title,
                    body: this.thread.body,
                }
            }
        },

        methods: {
            toggleLock() {
                this.locked = ! this.locked;
            },

            update() {
                axios.post(this.endpoint, {
                    title: this.form.title,
                    body: this.form.body,
                    _method: 'PATCH'
                }).then(() => {
                        this.editing = false;
                        this.title = this.form.title;
                        this.body = this.form.body;
                        flash('Your thread has been updated')
                    });
            },

            cancel() {

            }
        },
    }
</script>