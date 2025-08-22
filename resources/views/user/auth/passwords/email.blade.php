@extends('../layouts.app') {{-- You can adjust the layout path as per your structure --}}

@section('content')
<div class="login-card" role="main" aria-label="Doctor Registration Form">
  <div class="icon-circle" aria-hidden="true">
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path d="M12 2a3 3 0 0 0-3 3v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-2V5a3 3 0 0 0-3-3zm-1 5V5a1 1 0 1 1 2 0v2h-2zm-3 3h8v5H8v-5z"/>
    </svg>
  </div>
  <h1 class="title">Reset Password</h1>
  <p class="subtitle">Please enter your email address to receive a password reset link.</p>

  <div class="tabs" role="tablist" aria-label="User reset password form">
    <button class="tab active" role="tab" aria-selected="true" aria-controls="login-panel" id="login-tab" tabindex="0">Reset Password Form</button>

  </div>
                <div class="card-header bg-primary text-white">Reset Password</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.password.email') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email">Email Address</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus>

                            @error('email')
                                <span class="invalid-feedback d-block mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Send Password Reset Link
                        </button>
                    </form>
                </div>
           
@endsection
