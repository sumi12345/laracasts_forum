<template>
    <div class="media" :id="reply_id">
        <div class="media-left">
            <a href="#">
                <img class="media-object" src="" alt="">
            </a>
        </div>
        <div class="media-body">
            <div class="media-heading level">
                <h4 class="flex">
                    <a :href="profile_url" v-text="data.owner.name"></a>
                    <small>said {{ data.created_at }}</small>
                </h4>

                <!-- favorite component -->

            </div>

            <div v-if="editing">
                <div class="form-group">
                    <textarea v-model="body" rows="3" class="form-control"></textarea>
                </div>

                <button class="btn btn-success btn-xs" @click="update">提交</button>
                <button class="btn btn-default btn-xs" @click="editing = false">取消</button>
            </div>
            <div v-else v-text="body"></div>

            <p></p>
            <button type="button" class="btn btn-default" @click="editing = true">编辑</button>
            <button type="submit" class="btn btn-danger" @click="destroy">删除</button>
        </div>

        <p><hr></p>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';

    export default {
        props: ['data'],

        components: {
            favorite: Favorite
        },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            }
        },

        computed: {
            reply_id() {
                return 'reply-' + this.data.id;
            },

            profile_url() {
                return '/profiles/' + this.data.owner.name;
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Updated');
            },

            destroy() {
                // axios.delete('/replies/' + this.data.id);

                this.$emit('deleted');
            }
        }
    }
</script>