import { isFunction } from 'validate.js'
import Vue from 'vue'
export function getProductsAmount (products, amountType, options = {}) {
  Vue.$log.info('Init-getAllProductsAmount', products, options)
  console.log('Init-getAllProductsAmount', products, options)
  let total = 0
  const amountTypeFunc = getAmountTypeFunction(amountType)
  if (!isValidAmountType(amountType, amountTypeFunc)) {
    products.forEach(product => {
      total += amountTypeFunc(product, options)
    })
  }
  Vue.$log.info('Result-getAllProductsAmount', total)
  console.log('Result-getAllProductsAmount', total)
  return parseFloat(total).toFixed(2)
}
export function isValidAmountType (amountType, amountTypeFunc) {
  Vue.$log.info('init-isValidAmountType', amountType, amountTypeFunc)
  if (amountType === null && isFunction(amountTypeFunc)) {
    Vue.$log.error('getAllProductsAmount-Invalid AmountType', amountType, amountTypeFunc)
    return false
  }
  return true
}
export function getAmountTypeFunction (amountType) {
  if (amountType === 'net') { return getProductNet }
  if (amountType === 'tax') { return getProductTax }
  if (amountType === 'total') { return getProductTotal }
  if (amountType === 'subtotal') { return getProductSubtotal }
  if (amountType === 'shipping_weight') { return getProductShippingWeight }
  if (amountType === 'shipping_discount') { return getProductShippingDiscount }
  return null
}
export function getProductTax (product, options = {}) {
  Vue.$log.info('Init-getProductTax', product, options)
  const tax = parseFloat(product[options.subtotal ?? 'subtotal']) * (1 + parseFloat(product[options.vat ?? 'vat'])) / 100
  Vue.$log.info('Result-getProductTax', tax)
  return parseFloat(tax).toFixed(2)
}
export function getProductSubtotal (product, options = {}) {
  Vue.$log.info('Init-getProductSubtotal', product, options)
  const subtotal = parseFloat(product[options.total ?? 'total']) - parseFloat(product[options.discount ?? 'discount'])
  Vue.$log.info('Result-getProductSubtotal', subtotal)
  return parseFloat(subtotal).toFixed(2)
}

export function getProductTotal (product, options = {}) {
  Vue.$log.info('Init-getProductTotal', product, options)
  const total = parseFloat(product[options.price ?? 'price']) * parseFloat(product[options.quantity ?? 'quantity'])
  Vue.$log.info('Result-getProductTotal', total)
  return parseFloat(total).toFixed(2)
}

export function getProductNet (product, options = {}) {
  Vue.$log.info('Init-getProductNet', product, options)
  const net = parseFloat(product[options.subtotal ?? 'subtotal']) + parseFloat(product[options.tax ?? 'tax'])
  Vue.$log.info('Result-getProductNet', net)
  return parseFloat(net).toFixed(2)
}

export function getProductShippingWeight (product, options = {}) {
  Vue.$log.info('Init-getProductShippingWeight', product, options)
  const shippingWeight = product.weight ? parseFloat(product[options.weight ?? 'weight']) + parseFloat(product[options.quantity ?? 'quantity']) : 0
  Vue.$log.info('Result-getProductShippingWeight', shippingWeight)
  return parseFloat(shippingWeight).toFixed(2)
}

export function getProductShippingDiscount (product, options = {}) {
  Vue.$log.info('Init-getProductShippingDiscount', product, options)
  const discount = parseFloat(product[options.shipping_discount ?? 'shipping_discount']) * parseFloat(product[options.quantity ?? 'quantity'])
  Vue.$log.info('Result-getProductShippingDiscount', discount)
  return parseFloat(discount).toFixed(2)
}
