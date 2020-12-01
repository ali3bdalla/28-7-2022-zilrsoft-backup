export default {
	props: {
		activePage: {
			type: String,
			required: true
		}
	},

	methods: {
		getOrderTotalAmount(orderItems = null ) {
			let amount = 0;
			let items = orderItems ? orderItems : this.grabOrderItems();
			for (let index = 0; index < items.length; index++) {
				amount += parseFloat(this.getProductTotal(items[index]));
			}
			return amount;
        },
        getProductTotal(item) {
            let total = parseFloat(item.price) * parseInt(item.quantity);
            return total.toFixed(2);
        },
	}
};
