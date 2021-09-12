import moneyFormatter from '../Money/moneyFormatter'

export default {
  components: { moneyFormatter },
  data () {
    return {
      notifications: []
    }
  },
  created () {
    this.getNotifications()
  },
  methods: {
    addNotification (payload) {
      this.notifications.unshift(payload)
      this.$emit('addNotification', payload)
    },
    removeNotification (payload) {
      this.notifications.push(payload)

      this.$emit('removeNotification', payload)
    },
    addNotifications (notifications) {
      notifications.forEach((payload) => this.addNotification(payload))
    },
    getNotifications () {
      axios.get(this.url).then(res => {
        this.addNotifications(res.data)
      })
    }
  }
}
