<!DOCTYPE html>
<html>
<head>
    <title>Baby Kick Counter - Today's Kicks</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Google Font: Comic Neue -->
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
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
        .header h1 { font-size: 3rem; margin-bottom: 0; }
        .header p  { font-size: 1.2rem; margin: 0; }
        .btn-baby {
            background-color: #FF99CC; border: none; color: #fff;
            padding: 10px 20px; font-size: 1.2rem; border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 5px;
        }
        .btn-baby:hover { background-color: #FF80B3; }
        .user-info { font-size: 1.2rem; margin-bottom: 20px; color: #FF6699; text-align: center; }
        .congrats-message { font-size: 1.4rem; color: #27ae60; text-align: center; margin-bottom: 20px; }
        .pregnancy-info p { font-size: 1.1rem; margin: 4px 0; color: #555; }
        .table thead { background-color: #FF99CC; color: #fff; }
        .table tbody tr { background-color: #FFF8F0; }
        .card { border: none; margin-bottom: 30px; }
        .modal-header { background-color: #FFCCE5; border-bottom: none; }
        .modal-header h5, .modal-header .close { color: #fff; }
        .modal-footer .btn-secondary { background-color: #ccc; border: none; }
    </style>
</head>
<body>

<div class="container my-4">
    <!-- Header + Logout -->
    <div class="header">
        <div>
            <h1><i class="fas fa-baby"></i> Baby Kick Counter</h1>
            <p>Keep track of your baby's kicks in a fun, easy way!</p>
        </div>
        @if(Auth::check())
            <a href="#" class="btn btn-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endif
    </div>

    <!-- User Info + Funfact -->
    @if(Auth::check())
        <div class="user-info">
            Hi <strong>{{ Auth::user()->name }}</strong>! Welcome to your Baby Kick Counter.
        </div>
        <div class="user-info">
            Funfact – The developing fetus will begin moving around 12 weeks of pregnancy,
            but a mother may start noticing kicks between 16 and 24 weeks!
        </div>
    @endif

    <!-- Today's Date + Total + Top Buttons -->
    <div class="text-center mb-4">
        <h2>Today's Date: {{ \Carbon\Carbon::now()->format('l, F j, Y') }}</h2>
        <h4>Total Kicks Today: {{ $countToday }}</h4>

        <a href="{{ route('kicks.all') }}" class="btn btn-baby">View All-Time Kicks</a>
        <a href="{{ route('kicks.stats') }}" class="btn btn-baby">View Stats</a>
        @if(Auth::user()->birth_date)
            <button class="btn btn-baby" disabled>
                <i class="fas fa-calendar-alt"></i> Record Baby Birth
            </button>
        @else
            <button class="btn btn-baby" data-toggle="modal" data-target="#birthModal">
                <i class="fas fa-calendar-alt"></i> Record Baby Birth
            </button>
        @endif
    </div>

    <!-- Pregnancy Info (if before birth) -->
    @if(is_null(Auth::user()->birth_date))
        <div class="text-center mb-4">
            @if(is_null($lmpDate))
                <button class="btn btn-baby" data-toggle="modal" data-target="#lmpModal">
                    <i class="fas fa-calendar"></i> Record Last Period Date
                </button>
            @else
                <div class="pregnancy-info mx-auto" style="max-width: 400px;">
                    <p><strong>Last Period:</strong>  {{ $lmpDate->format('l, F j, Y') }}</p>
                    <p><strong>Due Date:</strong>     {{ $dueDate->format('l, F j, Y') }}</p>
                    <p><strong>Gestational Age:</strong> {{ $currentWeeks }}w {{ $currentDays }}d</p>
                    <p><strong>Time Remaining:</strong>  {{ $remainingWeeks }}w {{ $remainingDays }}d</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Log Kick + Today's Kicks Table (if before birth) -->
    @if(is_null(Auth::user()->birth_date))
        <div class="text-center mb-4">
            <button class="btn btn-baby" data-toggle="modal" data-target="#kickModal">
                <i class="fas fa-plus"></i> Log Kick
            </button>
        </div>
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
                            @forelse($todayKicks as $i => $kick)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $kick->kick_time->format('g:i A') }}</td>
                                    <td>{{ $kick->description ?? '—' }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('kicks.destroy', $kick->id) }}"
                                              onsubmit="return confirm('Mark this kick inactive?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm" style="background:#e74c3c;color:#fff;">
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
    @else
        <div class="congrats-message">
            Congratulations on your baby being born on
            {{ Auth::user()->birth_date->format('l, F j, Y') }}!
        </div>
    @endif
</div>

<!-- Kick Modal -->
<div class="modal fade" id="kickModal" tabindex="-1" role="dialog" aria-labelledby="kickModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('kicks.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="kickModalLabel">
            <i class="fas fa-plus-circle"></i> Log a New Kick
          </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="description">Description (optional):</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button class="btn btn-baby">Save Kick</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- LMP Modal -->
<div class="modal fade" id="lmpModal" tabindex="-1" role="dialog" aria-labelledby="lmpModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('kicks.recordLmp') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="lmpModalLabel">
            <i class="fas fa-calendar-alt"></i> Record Last Period Date
          </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="lmp_date">Last Period Date</label>
            <input type="date" id="lmp_date" name="lmp_date" class="form-control" required>
            @error('lmp_date')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button class="btn btn-baby">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Birth Modal -->
<div class="modal fade" id="birthModal" tabindex="-1" role="dialog" aria-labelledby="birthModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('kicks.recordBirth') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="birthModalLabel">
            <i class="fas fa-calendar-alt"></i> Record Baby Birth
          </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="birth_date">Baby Birth Date</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control" required>
            @error('birth_date')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button class="btn btn-baby">Save Birth Date</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- jQuery, Popper.js, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
