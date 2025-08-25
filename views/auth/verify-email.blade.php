@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="login-card" role="main" aria-label="Doctor Registration Form">
  <div class="icon-circle" aria-hidden="true">
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path d="M12 2a3 3 0 0 0-3 3v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-2V5a3 3 0 0 0-3-3zm-1 5V5a1 1 0 1 1 2 0v2h-2zm-3 3h8v5H8v-5z"/>
    </svg>
  </div>
  <h1 class="title">Verify Your Email Address</h1>
  <p class="subtitle">Email needs to be verified.</p>

  
      
      @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" role="alert">
          A fresh verification link has been sent to your email address.
        </div>
      @endif

      <p>Before proceeding, please check your email for a verification link.</p>
      <p>If you did not receive the email,</p>
  @php
      if (auth('doctor')->check()) {
          $resendRoute = 'doctor.verification.send';
      } elseif (auth('admin')->check()) {
          $resendRoute = 'admin.verification.send';
      } else {
          $resendRoute = 'user.verification.send';
      }
  @endphp

  <form method="POST" action="{{ route($resendRoute) }}">
        @csrf
        <button type="submit" class="btn btn-primary">Request another</button>
      </form>
    </div>
  
@endsection
