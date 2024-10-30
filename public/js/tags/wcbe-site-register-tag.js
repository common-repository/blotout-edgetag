jQuery(($) => {
    $(document).on('click', '.woocommerce-form-register__submit', function() {
        const email = $('#reg_email').val();

        if (!email) {
            return
        }

        edgetag('data', {
            email,
        });
    });
});