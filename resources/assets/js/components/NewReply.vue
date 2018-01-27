<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea v-model="body" name="body" id="body" rows="3" class="form-control" placeholder="说点什么?"></textarea>
            </div>
            <button @click="addReply" type="submit" class="btn btn-primary">发布</button>
        </div>

        <div v-else><p>要发表评论请先 <a href="/auth/login">登录</a></p></div>
    </div>
</template>
<script>
    import 'jquery.caret'; // solve this.$inputor.caret is not a function
    import 'at.js';  // solve TypeError: $(...).atwho is not a function

    export default {
        props: ['endpoint'],

        data() {
            return {
                body: '',
            }
        },

        methods: {
            // {data} 是为了把 data 转换为 json
            addReply() {
                axios.post(this.endpoint, { body: this.body })
                    .then(({data}) => {
                       this.body = '';
                       flash('回复发表成功!');
                       this.$emit('created', data);
                    })
                    .catch(error => {
                        flash(error.response.data);
                    });
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        mounted() {
            $('#body').atwho({
                at: '@',
                delay: 750,
                insertTpl: "<a href='/profiles/${name}'>@${name}</a>",
                callbacks: {
                    // https://github.com/ichord/At.js/wiki/How-to-use-remoteFilter
                    remoteFilter(query, callback) {
                        $.getJSON("/api/users", {name: query}, function(data) {
                            callback(data);
                        });
                    }
                }
            })
        }
    }
</script>