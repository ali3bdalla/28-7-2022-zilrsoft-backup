exports.metaHelper =
    {
        getContent: function (name) {
            var target = document.head.querySelector('meta[name="' + name + '"]');
            return target.content;
        }
    };


exports.trans = function (file) {
    var lang = metaHelper.getContent('lang-' + file);
    return JSON.parse(lang);
};


exports.route = function (route = "") {
    var lang = metaHelper.getContent('routes');
    return JSON.parse(lang);
};


exports.inputHelper = {
    validateQty: function (qty = 0, el, max = null, min = null) {
        el.classList.remove("inputErrorClass");
        if (!TextValidator.isInt(qty + "")) {
            this.showErrorOnInput(el, 'الكمية يجب ان تكون قيمة صحيحة');
            el.value = 0;
            return false;
        }

        if (max != null) {
            if (qty > max) {
                this.showErrorOnInput(el, 'الكمية يجب ان تكون اقل من او تساوي ' + max);
                el.value = 0;
                return false;
            }
        }


        if (min != null) {
            if (qty < min) {
                this.showErrorOnInput(el, 'الكمية يجب ان تكون اكبر  من او تساوي ' + min);
                el.value = 0;
                return false;
            }
        }


        return true;
    },


    validatePrice: function (price = 0, el) {
        el.classList.remove("inputErrorClass");
        if (!TextValidator.isDecimal(price + "")) {
            this.showErrorOnInput(el, 'السعر غير صحيح');
            el.value = 0;
            return false;
        }
        return true;
    },

    validateDiscount: function (discount = 0, el) {
        el.classList.remove("inputErrorClass");
        if (!TextValidator.isDecimal(discount + "")) {
            this.showErrorOnInput(el, 'الخصم غير صحيح');
            el.value = 0;
            return false;
        }
        return true;
    },

    showErrorOnInput: function (el, message = "") {
        el.classList.add("inputErrorClass");

        // el.focus();
        // el.select();

    }

};

