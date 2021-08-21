import { getProductsAmount } from './products'
export function getShippingSubtotal (shippingMethod, products, options = {}) {
  return parseFloat(Math.floor(getShippingTotal(shippingMethod, products, options) - getShippingDiscount(products, options))).toFixed(2)
}
export function getShippingTotal (shippingMethod, products, options = {}) {
  const shippingWeight = getProductsWeight(products, options)
  if (shippingWeight === 0) return 0
  const shippingMethodBaseWeightPrice = parseFloat(shippingMethod.max_base_weight_price)
  const shippingAmountAfterBaseWeight = getWeightAfterBaseShippingAmount(shippingMethod, shippingWeight)
  return parseFloat(shippingMethodBaseWeightPrice + shippingAmountAfterBaseWeight).toFixed(2)
}
export function getWeightAfterBaseShippingAmount (shippingMethod, shippingWeight) {
  const shippingMethodKGPrice = parseFloat(shippingMethod.kg_rate_after_max_price)
  const shippingMethodBaseWeight = parseFloat(shippingMethod.max_base_weight)
  let shippingAmountAfterBaseWeight = 0
  if (shippingWeight > shippingMethodBaseWeight) {
    const weightAfterBase = shippingWeight - shippingMethodBaseWeight
    shippingAmountAfterBaseWeight = shippingMethodKGPrice * weightAfterBase
  }
  return shippingAmountAfterBaseWeight
}
export function getProductsWeight (products, options) {
  return parseFloat(getProductsAmount(products, 'shipping_weight', options)).toFixed(2)
}
export function getShippingDiscount (products, options) {
  return parseFloat(getProductsAmount(products, 'shipping_discount', options)).toFixed(2)
}
