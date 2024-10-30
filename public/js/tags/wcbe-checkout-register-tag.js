jQuery(() => {
  if (!checkoutRegisterData) {
    return
  }

  edgetag('data', {
    email: checkoutRegisterData.email,
    firstName: checkoutRegisterData.firstName,
    lastName: checkoutRegisterData.lastName,
    phone: checkoutRegisterData.phone,
  })
})
