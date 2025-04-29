<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card text-center shadow p-4" style="max-width: 400px;">
        <div class="card-body">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="green"
                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08 0l4.992-4.992a.75.75 0 0 0-1.08-1.04L7.5 9.44 5.53 7.47a.75.75 0 1 0-1.06 1.06l2.5 2.5z" />
                </svg>
            </div>
            <h3 class="card-title">Payment Successful</h3>
            <p class="card-text">Thank you! Your payment has been processed successfully.</p>
            <a href="{{ url('/') }}" class="btn btn-success mt-3">Go to Homepage</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
