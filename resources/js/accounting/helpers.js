exports.metaHelper =
    {
      getContent: function (name) {
        const target = document.head.querySelector('meta[name="' + name + '"]')
        if (target) { return target.content }

        return {}
      }
    }

exports.trans = function (file) {
  const lang = metaHelper.getContent('lang-' + file)
  return JSON.parse(lang)
}

exports.route = function (route = '') {
  const lang = metaHelper.getContent('routes')
  return JSON.parse(lang)
}

exports.inputHelper = {
  validateQty: function (qty = 0, el, max = null, min = null) {
    el.classList.remove('inputErrorClass')
    if (!TextValidator.isDecimal(qty + '')) {
      this.showErrorOnInput(el, 'الكمية يجب ان تكون قيمة صحيحة')
      el.value = 0
      return false
    }

    if (max != null) {
      if (parseFloat(qty) > max) {
        this.showErrorOnInput(el, 'الكمية ' + qty + ' يجب ان تكون اقل من او تساوي ' + max)
        el.value = 0
        return false
      }
    }

    if (min != null) {
      if (parseFloat(qty) < min) {
        this.showErrorOnInput(el, 'الكمية يجب ان تكون اكبر  من او تساوي ' + min)
        el.value = 0
        return false
      }
    }

    return true
  },

  validatePrice: function (price = 0, el) {
    el.classList.remove('inputErrorClass')
    if (!TextValidator.isDecimal(price + '')) {
      this.showErrorOnInput(el, 'السعر غير صحيح')
      el.value = 0
      return false
    }
    return true
  },

  validateDiscount: function (discount = 0, el) {
    el.classList.remove('inputErrorClass')
    if (!TextValidator.isDecimal(discount + '')) {
      this.showErrorOnInput(el, 'الخصم غير صحيح')
      el.value = 0
      return false
    }
    return true
  },

  showErrorOnInput: function (el, message = '') {
    console.log(message)
    el.classList.add('inputErrorClass')
  }

}
