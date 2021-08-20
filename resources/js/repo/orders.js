import { getProductsAmount } from './products'
import { getShippingSubtotal } from './shipping'
export function getOrderNet (shippingMethod, products, options) {
  const productsNet = getProductsAmount(products, options)
  const shippingSubtotal = getShippingSubtotal(shippingMethod, products, options)
  return parseFloat(productsNet + shippingSubtotal).toFixed(2)
}
