@extends('layouts.app')

@section('title', 'Admin login')

@section('content')
<!-- Back Arrow Button -->
<div class="back-arrow-container" style="position: absolute; top: 20px; left: 20px; z-index: 1000;">
    <a href="{{ route('user.home') }}" class="back-arrow-btn" style="display: inline-flex; align-items: center; padding: 10px 15px; background: rgba(255, 255, 255, 0.9); border-radius: 50px; text-decoration: none; color: #333; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: all 0.3s ease;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>
        Back to Site
    </a>
</div>

<div class="login-card" role="main" aria-label="Admin Registration Form">
  <div class="icon-circle" aria-hidden="true">
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path d="M12 2a3 3 0 0 0-3 3v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-2V5a3 3 0 0 0-3-3zm-1 5V5a1 1 0 1 1 2 0v2h-2zm-3 3h8v5H8v-5z"/>
    </svg>
  </div>
  <h1 class="title">Welcome Admin</h1>
  <p class="subtitle">login your admin account below.</p>

  <div class="tabs" role="tablist" aria-label="Admin login or Info">
    <button class="tab active" role="tab" aria-selected="true" aria-controls="login-panel" id="login-tab" tabindex="0">Admin login</button>

  </div>

  <!-- Registration Panel -->
<!-- Login Panel -->
<form id="login-panel" role="tabpanel" aria-labelledby="login-tab" aria-hidden="false" method="POST" action="{{ route('admin.login') }}">

    @csrf

    <label for="email">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 2l-8 5-8-5V6l8 5 8-5v2z"/>
      </svg>
      Email
    </label>
    <input type="email" name="email" placeholder="admin@example.com" class="form-control" required autocomplete="email">

    <label for="password">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M12 17a2 2 0 0 0 2-2v-3a2 2 0 0 0-4 0v3a2 2 0 0 0 2 2zm6-6v-2a6 6 0 0 0-12 0v2H4v10h16V11h-2zm-6-4a4 4 0 0 1 4 4v2H8v-2a4 4 0 0 1 4-4z"/>
      </svg>
      Password
    </label>
    <input type="password" name="password" placeholder="********" required  class="form-control"autocomplete="current-password">

    <button type="submit" aria-label="Login">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M10 17l5-5-5-5v10zM5 19h2v-2H5v2zm0-10h2V7H5v2z"/>
      </svg>
      Login
    </button>
</form>


</div>
@endsection



