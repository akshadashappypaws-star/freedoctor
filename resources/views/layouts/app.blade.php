<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>FreeDoctor Login</title>
      <title>{{ config('app.name', 'Laravel') }}</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
      <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
  integrity="sha512-dNmRXVU8zICfaKpRIKyx0jRkCVKxQZHqnn2G+1p15Yb+NnKH0eAZm0Qtv2jQREcouW6eB0k4cF6hKAoAoiTHbg=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    :root {
      --primary-color: #383F45;
      --secondary-color: #E7A51B;
      --background-color: #f5f5f5;
      --surface-color: #ffffff;
      --text-primary: #212121;
      --text-secondary: #686868;
      --shadow-color: rgba(0, 0, 0, 0.12);
      --accent-color: #F7C873;
      --success-color: #4CAF50;
      --danger-color: #E53935;
      --border-radius: 16px;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: var(--text-primary);
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      box-sizing: border-box;
    }

    /* Container */
    .login-card {
      background: var(--surface-color);
      border-radius: var(--border-radius);
      width: 100%;
      max-width: 400px;
      max-height: 90vh;
      padding: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 2px solid var(--accent-color);
      overflow-y: auto;
      position: relative;
    }

    /* Icon */
    .icon-circle {
      background: #f5c518;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 16px;
    }
    .icon-circle svg {
      width: 28px;
      height: 28px;
      fill: #2a1a00;
    }

    /* Title and subtitle */
    .title {
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 4px;
     
    }
    .subtitle {
      font-weight: 400;
      font-size: 0.875rem;
      color: #cfcce0;
      margin-bottom: 24px;
   
      text-align: center;
    }

    /* Tabs */
    .tabs {
      display: flex;
      background: #1f1a3f;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 24px;
      width: 100%;
    
    }
    .tab {
      flex: 1;
      text-align: center;
      padding: 8px 0;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.875rem;
      color: #b0aee0;
      transition: background 0.3s, color 0.3s;
      border: none;
      outline: none;
    }
    .tab.active {
      background: linear-gradient(90deg, #7a5de8, #5a4de8);
      color: white;
    }

    /* Form */
    form {
      width: 100%;
     
      flex-direction: column;
      gap: 16px;
    }
    form.active {
      display: flex;
    }

    label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.875rem;
      font-weight: 500;
      color: #b0aee0;
      user-select: none;
    }
    label svg {
      width: 16px;
      height: 16px;
      fill: #b0aee0;
      flex-shrink: 0;
    }
/* Base styles for inputs and selects */

/* Placeholder color only applies to inputs */
input::placeholder {
  color: #6b658f;
}

/* Focus state for inputs and selects */
input:focus,
select:focus {
  background: #3a3570;
}

/* If you want the dropdown arrow to match your design */
select {
  /* removes default arrow in some browsers so you can add a custom one if you like */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  /* add a little right padding so text doesn't overlap your custom arrow */
  padding-right: 2.5rem;
  /* position relative if you plan to absolutely position a custom arrow */
  position: relative;
}

    /* Submit button */
    button[type="submit"] {
      margin-top: 8px;
      background: linear-gradient(90deg, #7a5de8, #5a4de8);
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: 600;
      font-size: 1rem;
      padding: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      user-select: none;
      transition: background 0.3s;
    }
    button[type="submit"]:hover {
      background: linear-gradient(90deg, #5a4de8, #7a5de8);
    }
    button svg {
      width: 18px;
      height: 18px;
      fill: white;
      flex-shrink: 0;
    }

    /* Back Arrow Button Hover Effect */
    .back-arrow-btn:hover {
      background: rgba(255, 255, 255, 1) !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
    }
 


    /* Container */
    .login-card {
      background: var(--surface-color);
      border-radius: var(--border-radius);
      width: 100%;
      max-width: 420px;
      padding: 40px 30px 50px;
      box-shadow: 0 20px 60px var(--shadow-color);
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 2px solid var(--accent-color);
    }

    /* Icon */
    .icon-circle {
      background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
      border: 3px solid var(--primary-color);
    }
    .icon-circle svg {
      width: 32px;
      height: 32px;
      fill: var(--primary-color);
    }

    /* Title and subtitle */
    .title {
      font-weight: 700;
      font-size: 1.4rem;
      margin-bottom: 4px;
      color: var(--primary-color);
      text-align: center;
    }
    .subtitle {
      font-weight: 400;
      font-size: 0.85rem;
      color: var(--text-secondary);
      margin-bottom: 20px;
      text-align: center;
    }

    /* Tabs */
    .tabs {
      display: flex;
      background: var(--primary-color);
      border-radius: 10px;
      overflow: hidden;
      margin-bottom: 20px;
      width: 100%;
    }
    .tab {
      flex: 1;
      text-align: center;
      padding: 8px 0;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.8rem;
      color: var(--accent-color);
      transition: background 0.3s, color 0.3s;
      border: none;
      outline: none;
    }
    .tab.active {
      background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
      color: var(--primary-color);
    }

    /* Form */
    form {
      width: 100%;
      flex-direction: column;
      gap: 15px;
    }
    form.active {
      display: flex;
    }

    label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--primary-color);
      user-select: none;
      margin-bottom: 3px;
    }
    label svg {
      width: 18px;
      height: 18px;
      fill: var(--secondary-color);
      flex-shrink: 0;
    }

/* Base styles for inputs and selects */
.form-control, input, select, textarea {
  background: var(--background-color);
  border: 2px solid var(--accent-color);
  border-radius: 10px;
  color: var(--text-primary);
  font-size: 0.85rem;
  padding: 10px 12px;
  width: 100%;
  transition: all 0.3s ease;
}

/* Placeholder color only applies to inputs */
input::placeholder {
  color: var(--text-secondary);
}

/* Focus state for inputs and selects */
input:focus,
select:focus,
.form-control:focus {
  background: var(--surface-color);
  border-color: var(--secondary-color);
  box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
  outline: none;
}

/* Google Login Button */
.google-login-btn {
  background: #4285f4;
  border: none;
  border-radius: 10px;
  color: white;
  font-weight: 600;
  font-size: 0.85rem;
  padding: 10px 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  transition: all 0.3s ease;
  margin-bottom: 10px;
}

.google-login-btn:hover {
  background: #3367d6;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
}

.google-icon {
  width: 18px;
  height: 18px;
  background: white;
  border-radius: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2px;
}

.divider {
  display: flex;
  align-items: center;
  margin: 15px 0;
  color: var(--text-secondary);
  font-size: 0.8rem;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--accent-color);
}

.divider span {
  padding: 0 10px;
  background: var(--surface-color);
}

/* If you want the dropdown arrow to match your design */
select {
  /* removes default arrow in some browsers so you can add a custom one if you like */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  /* add a little right padding so text doesn't overlap your custom arrow */
  padding-right: 2.5rem;
  /* position relative if you plan to absolutely position a custom arrow */
  position: relative;
}

    /* Submit button */
    button[type="submit"], .btn-primary {
      margin-top: 8px;
      background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
      border: 2px solid var(--primary-color);
      border-radius: 10px;
      color: var(--primary-color);
      font-weight: 700;
      font-size: 0.85rem;
      padding: 10px 16px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      user-select: none;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    button[type="submit"]:hover, .btn-primary:hover {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      transform: translateY(-1px);
      box-shadow: 0 4px 15px var(--shadow-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      body {
        padding: 10px;
      }
      
      .login-card {
        max-width: 100%;
        padding: 15px;
        max-height: 95vh;
      }
      
      .title {
        font-size: 1.2rem;
      }
      
      .subtitle {
        font-size: 0.8rem;
      }
      
      .back-arrow-container {
        top: 10px;
        left: 10px;
      }
      
      .back-arrow-btn {
        padding: 8px 12px;
        font-size: 0.8rem;
      }
      
      .form-control, input, select, textarea {
        font-size: 0.8rem;
        padding: 8px 10px;
      }
      
      button[type="submit"], .btn-primary, .google-login-btn {
        font-size: 0.8rem;
        padding: 8px 12px;
      }
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 12px;
        border-radius: 12px;
      }
      
      .logo-section img {
        height: 50px !important;
      }
      
      .title {
        font-size: 1.1rem;
      }
      
      .subtitle {
        font-size: 0.75rem;
        margin-bottom: 15px;
      }
      
      .tabs {
        margin-bottom: 15px;
      }
      
      form {
        gap: 12px;
      }
    }
    button svg {
      width: 20px;
      height: 20px;
      fill: currentColor;
      flex-shrink: 0;
    }

    /* Back Arrow Button Styles */
    .back-arrow-container {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1000;
    }
    
    .back-arrow-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--surface-color);
      color: var(--primary-color);
      text-decoration: none;
      padding: 12px 16px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.9rem;
      border: 2px solid var(--secondary-color);
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px var(--shadow-color);
    }
    
    .back-arrow-btn:hover {
      background: var(--secondary-color);
      color: var(--primary-color);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px var(--shadow-color);
      text-decoration: none;
    }
    
    /* Form Elements */
    .alert {
      padding: 12px 16px;
      border-radius: 12px;
      margin-bottom: 20px;
      border: none;
    }
    
    .alert-danger {
      background: linear-gradient(135deg, var(--danger-color), #ff6b6b);
      color: white;
      border: 2px solid var(--danger-color);
    }
    
    .alert-success {
      background: linear-gradient(135deg, var(--success-color), #66bb6a);
      color: white;
      border: 2px solid var(--success-color);
    }
    
    .btn-link {
      color: var(--secondary-color);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      transition: color 0.3s ease;
    }
    
    .btn-link:hover {
      color: var(--primary-color);
      text-decoration: underline;
    }
    
    .form-check-input {
      background-color: var(--background-color);
      border: 2px solid var(--accent-color);
      border-radius: 4px;
    }
    
    .form-check-input:checked {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }
    
    .form-check-label {
      color: var(--text-primary);
      font-weight: 500;
      font-size: 0.8rem;
    }

    /* Back Arrow Button Hover Effect */
    .back-arrow-btn:hover {
      background: rgba(255, 255, 255, 1) !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
    }
 
  </style>
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Back Arrow Styles -->
  <link rel="stylesheet" href="{{ asset('css/back-arrow.css') }}">
</head>
<body>
    @yield('content')
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzlA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- Cross Authentication Modal Script -->
    @if(session('cross_auth_error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const crossAuthData = @json(session('cross_auth_error'));
            
            Swal.fire({
                title: 'Already Logged In!',
                html: `
                    <div style="text-align: center;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🔒</div>
                        <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                            You are currently logged in as <strong>${crossAuthData.current_user_name}</strong> 
                            in the <strong>${crossAuthData.current_portal} Portal</strong>.
                        </p>
                        <p style="color: #666; margin-bottom: 1.5rem;">
                            To access the <strong>${crossAuthData.intended_portal} Portal</strong>, 
                            please logout from your current session first.
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🚪 Logout & Continue',
                cancelButtonText: '↩️ Go Back',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-popup-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form to logout
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = crossAuthData.logout_route;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                } else {
                    // Go back to the current portal dashboard
                    window.location.href = '{{ url("/") }}/' + crossAuthData.current_guard + '/dashboard';
                }
            });
        });
    </script>
    @endif
</body>
</html>
