<!DOCTYPE html>
<html>
<head>
    <title>Baby Kick Counter - Login</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Google Font: Comic Neue -->
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #FFF8F0; /* Soft pastel off-white */
            font-family: 'Comic Neue', cursive;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .baby-header {
            background-color: #FFCCE5;
            padding: 2rem 1rem;
            text-align: center;
            border-radius: 0 0 40% 40%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .baby-header h1 {
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .baby-header p {
            color: #fff;
            font-size: 1.1rem;
        }
        .login-card {
            max-width: 400px;
            margin: 0 auto;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .login-card-header {
            background-color: #FF99CC;
            color: #fff;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .login-card-header h4 {
            margin: 0;
        }
        .login-card-body {
            padding: 1.5rem;
        }
        .btn-baby {
            background-color: #FF99CC;
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
        }
        .btn-baby:hover {
            background-color: #FF80B3;
        }
        .form-check-label {
            font-weight: 500;
        }
        .baby-footer {
            margin-top: 1rem;
            text-align: center;
        }
        .baby-footer a {
            color: #FF99CC;
        }
        .baby-footer a:hover {
            color: #FF80B3;
        }
    </style>
</head>
<body>

<!-- Baby-Themed Header -->
<div class="baby-header">
    <h1><i class="fas fa-baby"></i> Baby Kick Counter</h1>
    <p>Log in to keep track of your baby's kicks!</p>
</div>

<!-- Login Card -->
<div class="login-card">
    <div class="login-card-header">
        <h4><i class="fas fa-sign-in-alt"></i> Login</h4>
    </div>
    <div class="login-card-body">

        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- The login form (from Laravel's default scaffolding) -->
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback d-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                       name="password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback d-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="remember"
                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-baby">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>

            <!-- Forgot Your Password? -->
            <div class="baby-footer">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                @endif
            </div>
        </form>

    </div>
</div>

<!-- Register Link (Optional) -->
<div class="text-center mt-3">
    @if (Route::has('register'))
        <p>
            Don't have an account?
            <a href="{{ route('register') }}" style="color:#FF99CC;">
                Register Here
            </a>
        </p>
    @endif
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
