import * as productRepo from './repository/products'

export default {
  state: {
    invoiceItems: [],
  },
  mutations: {
    addInvoiceItem (state, item) {
      const existsIndex = state.invoiceItems.findIndex(p => p.id = item.id)
      if (existsIndex !== -1) {
        const exists = state.invoiceItems[existsIndex]
        item.quantity = parseFloat(item.quantity) + parseFloat(exists.quantity)
        item.serials = Array.from(item.serials).concat(exists.serials)
      }
      item.total = productRepo.getProductTotal(item)
      item.discount = 0
      item.subtotal = productRepo.getProductSubtotal(item)
      item.subtotal = productRepo.getProductSubtotal(item)
      item.tax = productRepo.getProductTax(item, { vat: 'vts' })
      item.net = productRepo.getProductNet(item)
      if (existsIndex !== -1) {
        state.invoiceItems = state.invoiceItems.splice(existsIndex, 1, item)
      } else {
        state.invoiceItems.push(item)
      }

    }
  }
}
