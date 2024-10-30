jQuery(() => {
  const currency = checkoutData.currency
  const value = checkoutData.value
  const products = checkoutData.checkoutProductData

  const contents = products.map(function (product) {
    const content = {
      id: product.id.toString(),
      quantity: product.quantity,
      item_price: parseFloat(
        parseFloat(product.price).toFixed(checkoutData.price_decimals)
      ),
      title: product.title,
      category: product.category,
      image: product.image,
      url: product.url,
    }

    if (product.variantId) {
      content.variantId = product.variantId.toString()
    }

    if (product.sku) {
      content.sku = product.sku
    }

    return content
  })

  edgetag('tag', 'InitiateCheckout', {
    currency: currency,
    value: parseFloat(parseFloat(value).toFixed(checkoutData.price_decimals)),
    contents: contents,
    checkoutUrl: checkoutData.checkout_url,
  })
})
