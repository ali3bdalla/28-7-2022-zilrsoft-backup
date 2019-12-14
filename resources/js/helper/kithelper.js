const kithelper = {
    update_kit_data_depend_on_qty: function (kit) {
        kit.total = kit.total * kit.qty;
        kit.subtotal = kit.subtotal * kit.qty;
        kit.net = kit.net * kit.qty;
        kit.total = kit.total * kit.qty;
        kit.tax = kit.tax * kit.qty;
        console.log('update_kit_data_depend_on_qty');
        return kit;
    }
};

exports.kithelper;