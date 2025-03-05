<!DOCTYPE html>
<html>
<head>
    <title>Baby Kick Counter - All-Time Kicks</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Google Font: Comic Neue -->
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #FFF8F0;
            font-family: 'Comic Neue', cursive;
            color: #333;
        }
        .header {
            background-color: #FFCCE5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 1.2rem;
        }
        .btn-baby {
            background-color: #FF99CC;
            border: none;
            color: #fff;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-baby:hover {
            background-color: #FF80B3;
        }
        .user-info {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #FF6699;
            text-align: center;
        }
        .table thead {
            background-color: #FF99CC;
            color: #fff;
        }
        .table tbody tr {
            background-color: #FFF8F0;
        }
        .card {
            border: none;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container my-4">
    <!-- Header with Logout -->
    <div class="header">
        <div>
            <h1><i class="fas fa-baby"></i> Baby Kick Counter</h1>
            <p>Keep track of your baby's kicks in a fun, easy way!</p>
        </div>
        @if(Auth::check())
            <div>
                <a href="#" class="btn btn-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        @endif
    </div>

    <!-- Logged In User Info -->
    @if(Auth::check())
        <div class="user-info">
            Hi <strong>{{ Auth::user()->name }}</strong>! Here are all your kicks.
        </div>
    @endif

    <!-- Total Lifetime Kicks -->
    <div class="text-center mb-4">
        <h2>Total Lifetime Kicks: {{ $countAll }}</h2>
        <!-- Link back to today's kicks -->
        <a href="{{ route('kicks.index') }}" class="btn btn-baby">View Today's Kicks</a>
    </div>

    <!-- All-Time Kicks Table -->
    <div class="card">
        <div class="card-header text-center">
            <h4 class="mb-0">All Kicks (Newest First)</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kick Date &amp; Time</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allKicks as $index => $kick)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kick->kick_time->format('l, F j, Y g:i A') }}</td>
                                <td>{{ $kick->description ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No kicks logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery, Popper.js, and Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
