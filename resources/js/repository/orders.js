import { getProductsAmount } from './products'
import { getShippingSubtotal } from './shipping'
export function getOrderNet (shippingMethod, products, options) {
  return parseFloat(
    parseFloat(getOrderTotal(products, options)) +
    parseFloat(getShippingSubtotal(shippingMethod, products, options))
  ).toFixed(2)
}
export function getOrderTotal (products, options) {
  return getProductsAmount(products, 'total', options)
}
