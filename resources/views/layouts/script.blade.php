<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

<script>
async function refreshToken() {
    const refreshToken = localStorage.getItem('refresh_token');  // Assuming you store the refresh token in localStorage

    const response = await fetch('/api/refresh', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            refresh_token: refreshToken
        }),
    });

    const data = await response.json();

    if (response.ok) {
        // Save new access token
        localStorage.setItem('access_token', data.access_token);
        return data.access_token;
    } else {
        console.log('Refresh token is invalid or expired');
    }
}
</script>
@stack('script')