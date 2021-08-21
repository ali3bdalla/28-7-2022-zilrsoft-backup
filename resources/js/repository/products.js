import { isFunction } from 'validate.js'
import Vue from 'vue'
export function getProductsAmount (products, amountType, options = {}) {
  let total = 0
  const amountTypeFunc = getAmountTypeFunction(amountType)
  try {
    isValidAmountType(amountType, amountTypeFunc)
    total = products.reduce((accumulator, currentProduct) => {
      const currenctProductAmount = amountTypeFunc(currentProduct, options)
      return parseFloat(accumulator) + parseFloat(currenctProductAmount)
    }, 0)
  } catch (error) {
    Vue.$log.error(amountType, amountTypeFunc)
  }
  return parseFloat(total).toFixed(2)
}

export function isValidAmountType (amountType, amountTypeFunc) {
  if (amountType === null && isFunction(amountTypeFunc)) {
    throw new Error('invalid amount type')
  }
}
export function getAmountTypeFunction (amountType) {
  let func = null
  if (amountType === 'net') { func = getProductNet }
  if (amountType === 'tax') { func = getProductTax }
  if (amountType === 'total') { func = getProductTotal }
  if (amountType === 'subtotal') { func = getProductSubtotal }
  if (amountType === 'shipping_weight') { func = getProductShippingWeight }
  if (amountType === 'shipping_discount') { func = getProductShippingDiscount }
  return func
}
export function getProductTax (product, options = {}) {
  const tax = parseFloat(product[options.subtotal ?? 'subtotal']) * (1 + parseFloat(product[options.vat ?? 'vat'])) / 100
  return parseFloat(tax).toFixed(2)
}
export function getProductSubtotal (product, options = {}) {
  const subtotal = parseFloat(product[options.total ?? 'total']) - parseFloat(product[options.discount ?? 'discount'])
  return parseFloat(subtotal).toFixed(2)
}

export function getProductTotal (product, options = {}) {
  const total = parseFloat(product[options.price ?? 'price']) * parseFloat(product[options.quantity ?? 'quantity'])
  return parseFloat(total).toFixed(2)
}

export function getProductNet (product, options = {}) {
  const net = parseFloat(product[options.subtotal ?? 'subtotal']) + parseFloat(product[options.tax ?? 'tax'])
  return parseFloat(net).toFixed(2)
}

export function getProductShippingWeight (product, options = {}) {
  const shippingWeight = product.weight ? parseFloat(product[options.weight ?? 'weight']) + parseFloat(product[options.quantity ?? 'quantity']) : 0
  return parseFloat(shippingWeight).toFixed(2)
}

export function getProductShippingDiscount (product, options = {}) {
  const discount = parseFloat(product[options.shipping_discount ?? 'shipping_discount']) * parseFloat(product[options.quantity ?? 'quantity'])
  return parseFloat(discount).toFixed(2)
}
