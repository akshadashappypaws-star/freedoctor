@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="login-box">
  <div class="login-logo"><b>Admin</b> Login</div>
  <div class="card">
    <div class="card-body login-card-body">
      <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
        </div>
        <div class="row">
          <div class="col-8"><a href="{{ route('admin.password.request') }}">Forgot Password?</a></div>
          <div class="col-4"><button type="submit" class="btn btn-primary btn-block">Login</button></div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
