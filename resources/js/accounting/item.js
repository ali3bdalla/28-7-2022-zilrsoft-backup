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
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseInt(qty) * parseFloat(price));
    },
    getSubtotal: function (total, discount) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(total) - parseFloat(discount));
    },
    getTax: function (subtotal, vat, fixed = false) {
        let result = parseFloat(subtotal) * parseFloat(this.convertVatPercentValueIntoFloatValue(vat)) - parseFloat(subtotal);
        if (fixed)
            return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(this.toFixedWithoutRound(result));

        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(result);
    },
    getNet: function (subtotal, tax, fixed = false) {
        let result = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(parseFloat(subtotal) + parseFloat(tax)).toFixed(2));
        if (fixed)
            return parseFloat(result).toFixed(2);

        return result;
    },
    getVariation(current, old) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(current) - parseFloat(old));
    },

    toFixedWithoutRound(value) {
        return value.toString().match(/^-?\d+(?:\.\d{0,4})?/)[0];

    },

    getKitInformation: function (item) {
        item.available_qty = 100;
        item.is_fixed_price = true;
        item.price = item.data.total;
        item.total = item.data.total;
        item.subtotal = item.data.subtotal;
        item.discount = item.data.discount;
        item.price_with_tax = this.getSalesPriceWithTaxFromSalesPriceAndVat(item.price, item.vts);
        item.net = item.data.net;
        item.tax = item.data.tax;
        item.is_fixed_price = true;
        return item;
    }
};

exports.math = {
    sum: function (first, second) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(first) + parseFloat(second));
    },

    sub: function (first, second) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(first) - parseFloat(second));
    },

    dev: function (first, second) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(first) / parseFloat(second));
    },
    mult: function (first, second) {
        return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(first) * parseFloat(second));
    },

    isBiggerThan: function (first, second) {
        return parseFloat(first) > parseFloat(second);
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
    sendValidatePurchaseSerialRequest: function (serials = []) {
        var link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_purchase_serial';
        return axios.post(link, {
            serials: serials
        });
    },

    sendValidateSaleSerialRequest: function (item_id = 0, serials = []) {
        var link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_sale_serial';
        return axios.post(link, {
            item_id: item_id,
            serials: serials
        });
    },
    sendValidateReturnPurchaseSerialRequest: function (query = {}) {
        let link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_return_purchase_serial';
        return axios.post(link, query);
    },
    sendValidateReturnSaleSerialRequest: function (query = {}) {
        var link = metaHelper.getContent("BaseApiUrl") + 'items/helper/query_validate_return_sale_serial';
        return axios.post(link, query);
    }
};


