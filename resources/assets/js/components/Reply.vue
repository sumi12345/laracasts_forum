<template>
    <div class="media" :id="reply_id">
        <div class="media-left">
            <a href="#">
                <img class="media-object" :src="reply.owner.avatar" :alt="reply.owner.name" style="width:32px">
            </a>
        </div>
        <div class="media-body">
            <div class="media-heading level">
                <h4 class="flex">
                    <a :href="profile_url" v-text="reply.owner.name"></a>
                    <small>said {{ reply.created_at }}</small>
                </h4>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>

                <button v-if="authorize('updateThread', reply.thread)"
                        class="btn ml-a" :class="isBest ? 'btn-success' : 'btn-default'"
                        :disabled="isBest"
                        @click="markBestReply">最佳
                </button>

            </div>

            <div v-if="editing">
                <div class="form-group">
                    <textarea v-model="body" rows="3" class="form-control"></textarea>
                </div>

                <button class="btn btn-success btn-xs" @click="update">提交</button>
                <button class="btn btn-default btn-xs" @click="editing = false">取消</button>
            </div>
            <div v-else v-html="body"></div>

            <div v-if="authorize('updateReply', reply)" class="mt-1">
                <button class="btn btn-default" @click="editing = true">编辑</button>
                <button class="btn btn-danger" @click="destroy">删除</button>
            </div>
        </div>

        <hr>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';

    export default {
        props: ['reply'],

        components: {
            favorite: Favorite
        },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            }
        },

        computed: {
            reply_id() {
                return 'reply-' + this.id;
            },

            profile_url() {
                return '/profiles/' + this.reply.owner.name;
            },
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {body: this.body} )
                    .then(() => {
                        this.editing = false;
                        flash('Updated');
                    })
                    .catch(error => { flash(error.response.data) });
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted');
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best');

                window.events.$emit('best-reply-selected', this.id);
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        }
    }
</script>