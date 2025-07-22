<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .notfound-container {
            text-align: center;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            max-width: 600px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .notfound-icon {
            font-size: 80px;
            color: #ffd700;
            margin-bottom: 20px;
            animation: float 2s ease-in-out infinite;
        }

        .notfound-title {
            font-size: 4rem;
            font-weight: 700;
        }

        .notfound-text {
            font-size: 1.2rem;
            margin: 15px 0 25px;
        }

        .btn-custom {
            background-color: #ffffff;
            color: #333;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            margin: 5px;
            font-weight: 500;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
        }

        .btn-custom:hover {
            background-color: #f1f1f1;
            transform: scale(1.05);
        }

        .footer {
            margin-top: 30px;
            font-size: 0.85rem;
            color: #ddd;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .notfound-title {
                font-size: 3rem;
            }

            .notfound-icon {
                font-size: 60px;
            }

            .notfound-text {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="notfound-container">
        <div class="notfound-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="notfound-title">403</div>
        <p class="notfound-text">Anda tidak memiliki Akses ke halaman ini</p>

        <div>
            <a href="{{ url()->previous() }}" class="btn-custom">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
            <a href="{{ auth('admin')->check() ? route('dashboard') : route('login') }}" class="btn-custom">Go to Home</a>

        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ $profile->nama ?? config('app.name') }}. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
