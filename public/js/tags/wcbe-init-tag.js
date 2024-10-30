!(function () {
  const edgeTagUrl = siteData.url
  if (!edgeTagUrl) {
    return
  }

  window.edgetag =
    window.edgetag ||
    function () {
      ;(edgetag.stubs = edgetag.stubs || []).push(arguments)
    }

  edgetag('init', {
    edgeURL: edgeTagUrl,
    disableConsentCheck: true,
  })

  edgetag('tag', 'PageView')
})()
