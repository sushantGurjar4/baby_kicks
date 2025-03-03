<!DOCTYPE html>
<html>
<head>
    <title>Baby Kick Counter</title>
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
        }
        .header {
            background-color: #FFCCE5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
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
        /* User Info Styling */
        .user-info {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #FF6699;
            text-align: center;
        }
        /* Customized Modal Styles */
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
        /* Delete Button Styling */
        .btn-delete {
            background-color: #e74c3c;
            border: none;
            color: #fff;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 4px;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="container my-4">
    <!-- Top Row: Header (Left) + Logout Button (Right) -->
    <div class="d-flex justify-content-between align-items-center header">
        <div>
            <h1><i class="fas fa-baby"></i> Baby Kick Counter</h1>
            <p>Keep track of your baby's kicks in a fun, easy way!</p>
        </div>
        <!-- Logout Button (only show if user is authenticated) -->
        @if(Auth::check())
            <div>
                <a href="#" class="btn btn-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <!-- Hidden Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        @endif
    </div>

    <!-- Display Logged In User Info -->
    @if(Auth::check())
        <div class="user-info">
            Logged in as: <strong>{{ Auth::user()->name }}</strong>
        </div>
    @endif

    <!-- Success Alert (if a kick was logged) -->
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Button to trigger Kick Logging Modal -->
    <div class="text-center mb-4">
        <button type="button" class="btn btn-baby" data-toggle="modal" data-target="#kickModal">
            <i class="fas fa-plus"></i> Log Kick
        </button>
    </div>

    <!-- Kicks Listing Table -->
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
                            <th>Action</th> <!-- New Action Column -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kicks as $index => $kick)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kick->kick_time->format('l, F j, Y g:i A') }}</td>
                                <td>{{ $kick->description ?? '—' }}</td>
                                <td>
                                    <!-- Delete Button/Form -->
                                    <form action="{{ route('kicks.destroy', $kick->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to mark this kick as inactive?');">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No kicks logged yet.</td>
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
