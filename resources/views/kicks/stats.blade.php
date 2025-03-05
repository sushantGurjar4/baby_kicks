<!DOCTYPE html>
<html>
<head>
    <title>Baby Kick Counter - Statistics</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Google Font: Comic Neue -->
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .card {
            border: none;
            margin-bottom: 30px;
        }
        .chart-container {
            width: 100%;
            margin: 0 auto;
        }
        .stats-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .stats-header h2 {
            font-size: 2rem;
            color: #FF6699;
        }
    </style>
</head>
<body>

<div class="container my-4">
    <!-- Header with Logout -->
    <div class="header">
        <div>
            <h1><i class="fas fa-baby"></i> Baby Kick Counter</h1>
            <p>Track your baby's kicks in a fun, easy way!</p>
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
            Hi <strong>{{ Auth::user()->name }}</strong>! Here are your kick statistics.
        </div>
    @endif

    <!-- Statistics Header -->
    <div class="stats-header">
        <h2>Average Kicks per Day: {{ $average }}</h2>
    </div>

    <!-- Chart Container -->
    <div class="card">
        <div class="card-header text-center">
            <h4 class="mb-0">Kicks Per Day</h4>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="kicksChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Link back to Today's Kicks -->
    <div class="text-center">
        <a href="{{ route('kicks.index') }}" class="btn btn-baby">View Today's Kicks</a>
    </div>
</div>

<!-- Chart.js Script -->
<script>
    // Data passed from the controller: labels (dates) and data (kick counts)
    const labels = {!! json_encode($labels) !!};
    const data = {!! json_encode($data) !!};

    const ctx = document.getElementById('kicksChart').getContext('2d');
    const kicksChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Kicks',
                data: data,
                backgroundColor: 'rgba(255, 153, 204, 0.7)',
                borderColor: 'rgba(255, 153, 204, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Day (dd/mm)'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Kicks'
                    }
                }
            }
        }
    });
</script>

<!-- jQuery, Popper.js, and Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
