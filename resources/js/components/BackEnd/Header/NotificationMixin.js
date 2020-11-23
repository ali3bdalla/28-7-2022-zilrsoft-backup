export default {
    data() {
        return {
            notifications: []
        }
    },
    methods: {
        addNotification(payload) {
            this.notifications.unshift(payload);
            this.$emit('addNotification', payload);
        },
        removeNotification(payload) {
            this.notifications.push(payload);

            this.$emit('removeNotification', payload);
        },
        addNotifications(notifications) {
            notifications.forEach((payload) => this.addNotification(payload));
        }
    }
}