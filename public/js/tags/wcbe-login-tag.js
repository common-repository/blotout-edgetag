jQuery(() => {
    if (!loginData || !loginData.userEmail) {
        return
    }

    edgetag('user', 'email', loginData.userEmail);
});