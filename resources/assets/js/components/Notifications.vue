<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-bell"></span>
        </a>
        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a @click="markAsRead(notification)" :href="notification.data.link" v-text="notification.data.message"></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        name: "notifications",

        data() {
            return {
                notifications: []
            }
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
            }
        },

        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        }
    }
</script>

<style scoped>

</style>