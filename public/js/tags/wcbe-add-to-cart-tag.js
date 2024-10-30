!(function () {
  let price = addedProduct.item_price
  let variantId
  const quantity = parseInt(addedProduct.quantity) || 1

  if (addedProduct.variation) {
    // Get selected variation ID
    variantId = addedProduct.variantId
    price = parseFloat(addedProduct.variation_prices[variantId])
  }

  const content = {
    id: addedProduct.id.toString(),
    quantity,
    item_price: parseFloat(
      parseFloat(price).toFixed(addedProduct.siteData.price_decimal)
    ),
    title: addedProduct.title,
    category: addedProduct.category,
    image: addedProduct.image,
    url: addedProduct.url,
  }

  if (variantId) {
    content.variantId = variantId
  }

  if (addedProduct.sku) {
    content.sku = addedProduct.sku
  }

  let total = quantity * content.item_price
  if (Number.isNaN(total)) {
    total = parseFloat(price) || 0
  }
  const decimals = addedProduct.siteData.price_decimal
  total = Math.round(total * 10 ** decimals) / 10 ** decimals

  edgetag('tag', 'AddToCart', {
    currency: addedProduct.siteData.currency,
    value: total,
    contents: [content],
  })
})()
