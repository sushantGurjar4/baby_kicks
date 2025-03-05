<!DOCTYPE html>
<html>
<head>
    <title>Baby Kick Counter - Today's Kicks</title>
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
            margin: 0 5px;
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
        /* Modal Styles */
        .modal-header {
            background-color: #FFCCE5;
            border-bottom: none;
        }
        .modal-header h5 {
            font-size: 1.8rem;
            color: #fff;
        }
        .modal-header .close {
            color: #fff;
            opacity: 1;
        }
        .modal-footer .btn-secondary {
            background-color: #ccc;
            border: none;
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
            Hi <strong>{{ Auth::user()->name }}</strong>! Welcome to your Baby Kick Counter.
        </div>
        <div class="user-info">Funfact - The developing fetus will begin moving around 12 weeks of pregnancy but a mother may start noticing the kicking between 16 and 24 weeks!</div>
    @endif

    <!-- Today's Date, Total Kicks, and Navigation Buttons -->
    <div class="text-center mb-4">
        <h2>Today's Date: {{ \Carbon\Carbon::now()->format('l, F j, Y') }}</h2>
        <h4>Total Kicks Today: {{ $countToday }}</h4>
        <!-- Navigation Buttons -->
        <a href="{{ route('kicks.all') }}" class="btn btn-baby">View All-Time Kicks</a>
        <a href="{{ route('kicks.stats') }}" class="btn btn-baby">View Stats</a>
    </div>

    <!-- Log Kick Button -->
    <div class="text-center mb-4">
        <button type="button" class="btn btn-baby" data-toggle="modal" data-target="#kickModal">
            <i class="fas fa-plus"></i> Log Kick
        </button>
    </div>

    <!-- Today's Kicks Table -->
    <div class="card">
        <div class="card-header text-center">
            <h4 class="mb-0">Today's Kicks (Newest First)</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Time</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayKicks as $index => $kick)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <!-- Only the time is shown -->
                                <td>{{ $kick->kick_time->format('g:i A') }}</td>
                                <td>{{ $kick->description ?? '—' }}</td>
                                <td>
                                    <form action="{{ route('kicks.destroy', $kick->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to mark this kick as inactive?');">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-sm" style="background-color: #e74c3c; border: none; color: #fff; padding: 5px 10px; border-radius: 4px;">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No kicks logged yet for today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Logging a New Kick -->
<div class="modal fade" id="kickModal" tabindex="-1" role="dialog" aria-labelledby="kickModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('kicks.store') }}" method="POST">
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kickModalLabel">
                        <i class="fas fa-baby"></i>
                        <i class="fas fa-baby-carriage"></i>
                        Log a New Kick
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Description Input -->
                    <div class="form-group">
                        <label for="description">Description (optional):</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter details (e.g., Baby was extra active)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-baby">
                        <i class="fas fa-save"></i> Save Kick
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- jQuery, Popper.js, and Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
