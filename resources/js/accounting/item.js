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
    getTotal: function (price, qty) {
        return parseInt(qty) * parseFloat(price);
    },
    getSubtotal: function (total, discount) {
        return parseFloat(total) - parseFloat(discount);
    },
    getTax: function (subtotal, vat, fixed = false) {
        let result = parseFloat(subtotal) * parseFloat(this.convertVatPercentValueIntoFloatValue(vat)) - parseFloat(subtotal);
        if (fixed)
            return this.toFixedWithoutRound(result);

        return result;
    },
    getNet: function (subtotal, tax, fixed = false) {
        let result = parseFloat(parseFloat(subtotal) + parseFloat(tax)).toFixed(2);
        if (fixed)
            return parseFloat(result).toFixed(2);

        return result;
    },
    getVariation(current, old) {
        return parseFloat(current) - parseFloat(old);
    },

    toFixedWithoutRound(value) {
        return value.toString().match(/^-?\d+(?:\.\d{0,3})?/)[0];

    },
};

exports.math = {
    sum: function (first, second) {
        return parseFloat(first) + parseFloat(second);
    },

    sub: function (first, second) {
        return parseFloat(first) - parseFloat(second);
    },

    dev: function (first, second) {
        return parseFloat(first) / parseFloat(second);
    },
    mult: function (first, second) {
        return parseFloat(first) * parseFloat(second);
    }
};

exports.validator = {
    validateQty: function (qty, availableQty = null) {
        let result = true;
        if (isNaN(parseInt(qty)))
            result = false;


        if (availableQty !== null && parseInt(qty) > parseInt(availableQty))
            result = false;


        return result;
    },
    validatePriceValue: function (price) {
        return !isNaN(parseFloat(price));
    },
    validateAmount: function (amount) {
        return this.validatePriceValue(amount) && parseFloat(amount) >= 0;
    }
};


exports.query = {
    sendQueryRequestToFindItems: function (query = null, invoice_type = null) {
        let link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_find_items';
        return axios.post(link, {barcode_or_name_or_serial: query, invoice_type: invoice_type});
    },
    sendQueryRequestToActivateItems: function (id_array = []) {
        let link = metaHelper.getContent("BaseApiUrl") + 'items/helper/activate_items';
        return axios.post(link, {id_array: id_array});
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


