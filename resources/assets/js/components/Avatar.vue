<template>
    <div>
        <form v-if="canUpdate">
            <input type="file" name="avatar" accept="image/jpeg" @change="onChange">
        </form>

        <img :src="avatar_src" width="100">
    </div>
</template>

<script>
    export default {
        name: "avatar",

        props: ['user'],

        data() {
            return {
                avatar_src: this.user.avatar,
            }
        },

        methods: {
            onChange(e) {
                if (0 === e.target.files.length) return;

                let file = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = e => {
                    this.avatar_src = e.target.result;
                }

                this.saveAvatar(file);
            },

            saveAvatar(file) {
                let data = new FormData();

                data.append('avatar', file);

                axios.post('/api/users/' + this.user.name + '/avatar', data)
                    .then(() => flash('上传成功'));
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id == this.user.id);
            }
        },
    }
</script>

<style scoped>

</style>