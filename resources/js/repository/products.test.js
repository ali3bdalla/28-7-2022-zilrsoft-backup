import { getProductTotal } from './products'
test('calc product total {12,10}', function () {
  const product = {
    price: 12,
    quantity: 10
  }
  const result = getProductTotal(product)
  expect(result).toBe(120)
})
