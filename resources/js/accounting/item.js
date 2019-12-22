exports.transfer = {
    pushToOpenInvoice: function (item) {
        const instance = new BroadcastChannel('item_barcode_copy_to_invoice');
        instance.postMessage(JSON.stringify(item));
        return true;
    }
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
};

exports.validator = {

    validatePriceValue: function (price) {
        return isNaN(parseFloat(price)) ? false : true;
    }
};