@extends('layouts.app')

@section('title', 'Admin Register')

@section('content')
<div class="register-box">
  <div class="register-logo"><b>Admin</b> Register</div>
  <div class="card">
    <div class="card-body register-card-body">
      <form method="POST" action="{{ route('admin.register') }}">
        @csrf
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Full Name" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
        </div>
        <div class="row">
          <div class="col-12"><button type="submit" class="btn btn-primary btn-block">Register</button></div>
        </div>
      </form>
      <p class="mt-3 mb-1">Already have an account? <a href="{{ route('admin.login') }}">Login</a></p>
    </div>
  </div>
</div>
@endsection
