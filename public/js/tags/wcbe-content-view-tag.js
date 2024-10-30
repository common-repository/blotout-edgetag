jQuery(($) => {
  const product = productData
  let productPrice = parseFloat(
    parseFloat(product.item_price).toFixed(product.siteData.price_decimal)
  )
  const quantity = $(product.siteData.quantity_selector).val() || 1

  let variantId = ''
  if (product.variation) {
    // Get selected variation ID
    variantId =
      product.default_variation_id || Object.keys(product.variation_prices)?.[0]
    productPrice = parseFloat(product.variation_prices[variantId])
  }

  const total = parseFloat(
    parseFloat(quantity * productPrice).toFixed(product.siteData.price_decimal)
  )

  const content = {
    id: product.id.toString(),
    quantity: parseInt(quantity),
    item_price: productPrice,
    title: product.title,
    category: product.category,
    image: product.image,
    url: product.url,
  }

  if (variantId) {
    content.variantId = variantId
  }

  if (product.sku) {
    content.sku = product.sku
  }

  edgetag('tag', 'ViewContent', {
    contents: [content],
    currency: product.siteData.currency,
    value: total,
  })
})
