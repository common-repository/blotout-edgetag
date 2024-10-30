jQuery(() => {
  const sendPurchaseSubtotal = siteData.send_purchase_subtotal

  const currency = purchaseData.currency
  const purchaseProductData = purchaseData.productData
  const purchaseUserData = purchaseData.userData
  const purchaseValue =
    sendPurchaseSubtotal === '1' ? purchaseData.subtotal : purchaseData.value
  const total = parseFloat(
    parseFloat(purchaseValue).toFixed(purchaseData.price_decimals)
  )

  const contents = purchaseProductData.map(function (product) {
    const content = {
      id: product.id.toString(),
      quantity: product.quantity,
      item_price: parseFloat(
        parseFloat(product.price).toFixed(purchaseData.price_decimals)
      ),
      title: product.title,
      category: product.categories,
      image: product.featured_image_url,
      url: product.product_url,
    }

    if (product.variation_id !== product.id) {
      content.variantId = product.variation_id?.toString()
    }

    if (product.sku) {
      content.sku = product.sku
    }

    return content
  })

  edgetag('data', {
    email: purchaseUserData.email,
    phone: purchaseUserData.phone,
    firstName: purchaseUserData.first_name,
    lastName: purchaseUserData.last_name,
  })

  edgetag('tag', 'Purchase', {
    currency: currency,
    value: total,
    orderId: purchaseData.orderId,
    eventId: purchaseData.orderId,
    contents: contents,
  })
})
