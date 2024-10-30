jQuery(document).ready(function ($) {
  const subscribeSelectors = subscribeData.subscribeSelectors
  if (!subscribeSelectors) {
    return
  }

  $.each(subscribeSelectors, function (index, selector) {
    $(document).on('submit', selector, function () {
      const email = $(this).find('input[type="email"]').val()
      if (email) {
        processEmail(email)
      }
    })
  })
})

function processEmail(email) {
  edgetag('user', 'email', email)
  const customNewsletterEventName =
    subscribeData.customNewsletterEventName || 'Lead'
  if (customNewsletterEventName) {
    edgetag('tag', customNewsletterEventName)
  }
}

window.addEventListener('klaviyoForms', function (e) {
  if (e.detail.type !== 'submit') {
    return
  }

  const email = e.detail.metaData.$email
  const phone = e.detail.metaData.$phone_number

  if (phone) {
    edgetag('user', 'phone', phone)
  }

  if (email) {
    processEmail(email)
  }
})

window.addEventListener('load', function () {
  let wisepopsCheckAttempts = 0
  const captureWisepops = () => {
    wisepopsCheckAttempts++
    if (typeof window.wisepops === 'function') {
      wisepops('listen', 'after-form-submit', function (event) {
        const eveEle = event.target.elements
        const email = eveEle.email?.value
        const phone =
          (eveEle['phone-dialcode'] ? eveEle['phone-dialcode'].value : '') +
          (eveEle['phone-number'] ? eveEle['phone-number'].value : '')
        const data = {}

        if (email) {
          data.email = email
        }

        if (phone) {
          data.phone = phone
        }

        if (data.email || data.phone) {
          edgetag('data', data)
        }
        if (email) {
          processEmail(email)
        }
      })
    } else if (wisepopsCheckAttempts <= 10) {
      setTimeout(captureWisepops, 1000)
    }
  }
  captureWisepops()
})
