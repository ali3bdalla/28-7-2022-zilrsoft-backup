exports.transfer = {
    pushToOpenInvoice: function (item) {
        const instance = new BroadcastChannel('item_barcode_copy_to_invoice');
        instance.postMessage(JSON.stringify(item));
        return true;
    },


};


exports.accounting = {
    convertVatPercentValueIntoFloatValue: function (vat) {
        if (validate.isEmpty(vat)) {
            return 0;
        }
        if (validate.isNumber(parseFloat(vat))) {
            return 1 + vat / 100;
        }
        return 0;
    },
    getSalesPriceWithTaxFromSalesPriceAndVat: function (price, vat) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(price * this.convertVatPercentValueIntoFloatValue(vat));
    },
    getSalesPriceFromSalesPriceWithTaxAndVat: function (price, vat) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(price / this.convertVatPercentValueIntoFloatValue(vat));
    },
    getTotal:function (qty, price) {
        return parseInt(qty) * parseFloat(price);
    },
    getSubtotal:function (total,discount) {
        return parseFloat(total) - parseFloat(discount);
    },
    getTax:function (subtotal,vat) {
        return parseFloat(subtotal) * parseFloat(this.convertVatPercentValueIntoFloatValue(vat));
    },
    getNet:function (subtotal,tax) {
        return parseFloat(subtotal) + parseFloat(tax);
    },
    getVariation(current, old)
    {
        return parseFloat(current) - parseFloat(old);
    }
};

exports.validator = {

    validatePriceValue: function (price) {
        return isNaN(parseFloat(price)) ? false : true;
    }
};


exports.query = {
    sendQueryRequestToFindItems: function (query = null) {
        let link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_find_items';
        return axios.post(link, {barcode_or_name_or_serial:query});
    },
    sendQueryRequestToActivateItems: function (id_array = []) {
        let link = metaHelper.getContent("BaseApiUrl") + 'items/helper/activate_items';
        return axios.post(link, {id_array:id_array});
    },
    sendValidatePurchaseSerialRequest: function (query = {}) {
        var link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_purchase_serial';
        return axios.post(link, query);
    },
    sendValidateSaleSerialRequest: function (query = {}) {
        var link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_sale_serial';
        return axios.post(link, query);
    },
    sendValidateReturnPurchaseSerialRequest: function (query = {}) {
        var link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_return_purchase_serial';
        return axios.post(link, query);
    },
    sendValidateReturnSaleSerialRequest: function (query = {}) {
        var link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_return_sale_serial';
        return axios.post(link, query);
    }
};


