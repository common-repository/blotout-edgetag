jQuery(document).ready(function ($) {
  if (currentAddressPage.page) {
    $(document).on(
      'blur',
      '.woocommerce-address-fields input, #customer_details input',
      function () {
        sendAddressValues()
      }
    )

    $(document).on(
      'change',
      '.woocommerce-address-fields select, #customer_details select',
      function () {
        sendAddressValues()
      }
    )
  }

  function sendAddressValues() {
    const billingFieldData = {
      firstName: {
        field: $('#billing_first_name'),
        required: isFieldRequired($('#billing_first_name')),
      },
      lastName: {
        field: $('#billing_last_name'),
        required: isFieldRequired($('#billing_last_name')),
      },
      phone: {
        field: $('#billing_phone'),
        required: isFieldRequired($('#billing_phone')),
      },
      city: {
        field: $('#billing_city'),
        required: isFieldRequired($('#billing_city')),
      },
      state: {
        field: $('#billing_state'),
        required: isFieldRequired($('#billing_state')),
      },
      country: {
        field: $('#billing_country'),
        required: isFieldRequired($('#billing_country')),
      },
    }

    const shippingFieldData = {
      firstName: {
        field: $('#shipping_first_name'),
        required: isFieldRequired($('#shipping_first_name')),
      },
      lastName: {
        field: $('#shipping_last_name'),
        required: isFieldRequired($('#shipping_last_name')),
      },
      city: {
        field: $('#shipping_city'),
        required: isFieldRequired($('#shipping_city')),
      },
      state: {
        field: $('#shipping_state'),
        required: isFieldRequired($('#shipping_state')),
      },
      country: {
        field: $('#shipping_country'),
        required: isFieldRequired($('#shipping_country')),
      },
    }

    let allBillingFieldsValid = true
    let allShippingFieldsValid = true

    $.each(billingFieldData, function (key, value) {
      if (value.required && value.field.val() === '') {
        allBillingFieldsValid = false
        return false
      }
    })

    $.each(shippingFieldData, function (key, value) {
      if (value.required && value.field.val() === '') {
        allShippingFieldsValid = false
        return false
      }
    })

    if (
      allBillingFieldsValid &&
      (currentAddressPage.page === 'billing' ||
        currentAddressPage.page === 'checkout')
    ) {
      edgetag('data', {
        firstName: billingFieldData.firstName.field.val(),
        lastName: billingFieldData.lastName.field.val(),
        phone: billingFieldData.phone.field.val(),
        city: billingFieldData.city.field.val(),
        state: billingFieldData.state.field.val(),
        country: billingFieldData.country.field.val(),
      })
    }

    if (
      allShippingFieldsValid &&
      (currentAddressPage.page === 'shipping' ||
        currentAddressPage.page === 'checkout')
    ) {
      edgetag('data', {
        firstName: shippingFieldData.firstName.field.val(),
        lastName: shippingFieldData.lastName.field.val(),
        city: shippingFieldData.city.field.val(),
        state: shippingFieldData.state.field.val(),
        country: shippingFieldData.country.field.val(),
      })
      edgetag('tag', 'AddShippingInfo')
    }
  }

  function isFieldRequired(field) {
    return field.closest('p').hasClass('validate-required')
  }
})
