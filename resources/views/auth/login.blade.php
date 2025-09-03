<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    {{-- <link href="{{ asset('uploads/' . $profile->favicon) }}" rel="icon"> --}}
    <link href="{{ asset('') }}admin/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Fullscreen Centering */
        body {
            background: linear-gradient(135deg, #ece9e6, #ffffff);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
        }

        /* White Glassy Card */
        .login-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        .login-card h4 {
            color: #333;
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Logo Styling */
        .auth-logo img {
            display: block;
            margin: 0 auto;
            max-height: 50px;
        }

        /* Inputs with Subtle Border */
        .form-control {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            color: #333;
            box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
        }

        .craft {
            text-align: center;
            margin-top: 25px;
            color: #112233;
            font-size: 0.9rem;
        }

        .craft a {
            color: #7c3aed;
            text-decoration: none;
        }

        /* Purple Neon Button */
        .btn-purple {
            background: #8b5cf6;
            border: none;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            width: 100%;
            border-radius: 30px;
            transition: 0.3s;
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.5);
        }

        .btn-purple:hover {
            background: #7c3aed;
            box-shadow: 0 0 25px rgba(139, 92, 246, 0.7);
        }

        /* Placeholder Color */
        ::placeholder {
            color: rgba(0, 0, 0, 0.4);
        }

        /* Remember Me Checkbox */
        .form-check-label {
            color: #333;
        }

        /* Links and Footer */
        .text-muted a {
            color: #8b5cf6;
            text-decoration: none;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        .is-invalid {
            border-color: #e74c3c;
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
            animation: shake 0.3s;
        }

        .error-text {
            color: #e74c3c;
            font-size: 0.875em;
            margin-top: 5px;
        }

        /* Red Border and Shake Animation */
        .error-shake {
            border: 2px solid #ff4d4f;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center">
            <a href="/" class="auth-logo mb-3">
                @if ($profile->logo ?? '')
                    <img src="{{ asset('uploads/' . $profile->logo) }}" alt="{{ $profile->nama }}"
                        style="max-height: 80px; ">
                @else
                    <h3 class="text-muted">{{ $profile->nama ?? 'App Name' }}</h3>
                @endif
            </a>
        </div>

        <h4 class="mt-3 text-muted text-uppercase">🔐 Sign In</h4>

        <form action="{{ route('login') }}" method="post" id="login-form">
            @csrf
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="✉️ Username" autocomplete="off"
                    required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="🔑 Password" autocomplete="off"
                    required>
            </div>

            {{-- <div class="mb-3 form-check form-switch">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div> --}}

            <button type="submit" class="btn btn-purple">🔓 Login</button>
        </form>

        {{-- <p class="text-center text-muted mt-3">Kembali ke <a href="/">Homepage</a></p> --}}
        <div class="craft">
            💻 Crafted by <a href="https://bucumedia.com" target="_blank"> Mr.
                Nobody</a> 
        </div>
    </div>
    <script src="{{ asset('') }}libs/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Get form data
                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'), // The form's action URL
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // If login is successful
                        if (response.success === 1) {
                            // Redirect to dashboard without showing success on login page
                            window.location.href = '/dashboard';
                        } else {
                            // If login fails (e.g., wrong credentials)
                            showLoginError();
                        }
                    },
                    error: function() {
                        // Also show the cool error animation for any error response
                        showLoginError();
                    }
                });
            });

            function showLoginError() {
                // Add red border and shake animation
                $('.form-control').addClass('error-shake');

                // Remove the shake animation after it completes
                setTimeout(function() {
                    $('.form-control').removeClass('error-shake');
                }, 500);
            }
        });
    </script>
    <script>
        // Check if the session flash message exists
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Logout Successful',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</body>

</html>
