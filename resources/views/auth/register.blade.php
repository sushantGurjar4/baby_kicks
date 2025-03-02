<!DOCTYPE html>
<html>
<head>
    <title>Baby Kick Counter - Register</title>
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
        .register-card {
            max-width: 400px;
            margin: 0 auto;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .register-card-header {
            background-color: #FF99CC;
            color: #fff;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .register-card-header h4 {
            margin: 0;
        }
        .register-card-body {
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
    <p>Create an account to start tracking your baby's kicks!</p>
</div>

<!-- Register Card -->
<div class="register-card">
    <div class="register-card-header">
        <h4><i class="fas fa-user-plus"></i> Register</h4>
    </div>
    <div class="register-card-body">

        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" type="text"
                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="invalid-feedback d-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required>
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

            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control"
                       name="password_confirmation" required>
            </div>

            <!-- Register Button -->
            <button type="submit" class="btn btn-baby">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>

    </div>
</div>

<!-- Already have an account? -->
<div class="text-center mt-3">
    @if (Route::has('login'))
        <p>
            Already have an account?
            <a href="{{ route('login') }}" style="color:#FF99CC;">
                Log in here
            </a>
        </p>
    @endif
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
