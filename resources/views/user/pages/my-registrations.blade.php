@extends('user.master')

@section('title', 'My Registrations')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material UI Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- DataTables CSS -->
     <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ==========================================
           BOOTSTRAP + MATERIAL UI INTEGRATION
           Modern Design with Custom Color Scheme
           ========================================== */
        
        :root {
            /* Custom Color Scheme */
            --primary-color: #ffc107;
            --primary-dark: #e6ac00;
            --secondary-color: #343a40;
            --accent-color: #F8F9FA;
            --text-primary: #343a40;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --header-color: #383E44; /* Updated header color */
            --gradient-primary: linear-gradient(135deg, #ffc107 0%, #e6ac00 100%);
            --gradient-secondary: linear-gradient(135deg, #343a40 0%, #495057 100%);
            --gradient-header: linear-gradient(135deg, #383E44 0%, #3a3a3a 100%); /* New header gradient */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            
            /* Layout Constraints for Sidebar - FIXED */
            --sidebar-width: 280px;
            --content-max-width: calc(100vw - var(--sidebar-width));
            --mobile-breakpoint: 768px;
        }

        /* Global Reset and Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #fff3cd 20%, #f8f9fa 100%);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .main-wrapper {
            width: 100vw;
            max-width: 100vw;
            overflow-x: hidden;
        }

        .content-area {
            width: var(--content-max-width);
            max-width: var(--content-max-width);
            margin-left: var(--sidebar-width);
            overflow-x: hidden;
            position: relative;
        }

        /* Sidebar simulation for demo */
        .sidebar-placeholder {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            z-index: 1000;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-placeholder h3 {
            color: #ffc107;
            margin-bottom: 20px;
            font-size: 1.2rem;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
        }

        .sidebar-placeholder ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-placeholder li {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: background 0.3s;
        }

        .sidebar-placeholder li:hover {
            background: rgba(255, 193, 7, 0.2);
        }

        /* ==========================================
           CONTAINER FIXES
           ========================================== */
        
        .container-fluid {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: calc(var(--content-max-width) - 2rem);
            padding: 0 1rem;
            margin: 0 auto;
            overflow-x: hidden;
        }

        /* ==========================================
           PAGE HEADER SECTION - IMPROVED SPACING
           ========================================== */
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 4rem 0;
            margin: 0;
            width: 100%;
            max-width: 100%;
            border-radius: 0;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat center center;
            background-size: cover;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            text-align: center;
            font-size: 1.3rem;
            margin-top: 0.5rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        /* ==========================================
           SCROLLABLE CONTENT AREA
           ========================================== */
        
        .scrollable-content {
            height: calc(100vh - 200px); /* Adjust based on header height */
            overflow-y: auto;
            overflow-x: hidden;
            padding: 2rem 0;
        }

        /* ==========================================
           BOOTSTRAP INTEGRATION - UPDATED
           ========================================== */
        
        /* Override Bootstrap primary colors with our theme */
        .btn-primary {
            background: var(--gradient-primary) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
        }

        .btn-secondary {
            background: var(--gradient-secondary) !important;
            border-color: var(--secondary-color) !important;
        }

        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active {
            background: #495057 !important;
            border-color: #495057 !important;
        }

        /* Bootstrap Card Integration */
        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: var(--gradient-header) !important; /* Updated header color */
            color: white !important;
            border-bottom: none;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .card-header.bg-warning {
            background: var(--gradient-header) !important; /* Override warning class */
            color: white !important;
        }

        .card-body {
            padding: 1.5rem;
            overflow-x: auto;
        }

        /* ==========================================
           TABLE RESPONSIVE FIXES
           ========================================== */
      .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
}

.table {
    color: var(--text-primary);
    margin-bottom: 0;
    width: 100%;
    font-size: 0.95rem;
    min-width: 800px; /* This ensures the table is wide enough to trigger scroll */
}

.table thead th {
    background: var(--gradient-primary) !important;
    color: white;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 1.2rem 1rem;
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table tbody td {
    padding: 1.2rem 1rem;
    border-color: var(--border-color);
    vertical-align: middle;
    word-wrap: break-word;
}

.table tbody tr:hover {
    background-color: rgba(255, 193, 7, 0.1);
}

/* Scrollbar Customization for Webkit Browsers */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #ffc107;
    border-radius: 4px;
    border: 1px solid #f8f9fa;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #e0a800;
}

.table-responsive::-webkit-scrollbar-corner {
    background: #f8f9fa;
}

/* Scrollbars for Firefox */
.table-responsive {
    scrollbar-width: thin;
    scrollbar-color: #ffc107 #f8f9fa;
}


        /* ==========================================
           MATERIAL UI INTEGRATION - UPDATED
           ========================================== */
        
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        .material-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.25rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .material-btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .material-btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(255, 193, 7, 0.3);
            color: white;
            text-decoration: none;
        }

        .material-btn-secondary {
            background: var(--gradient-header); /* Updated to header color */
            color: white;
        }

        .material-btn-secondary:hover {
            background: #3a3a3a;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(84, 84, 84, 0.3);
            color: white;
            text-decoration: none;
        }

        /* ==========================================
           RESPONSIVE DESIGN SYSTEM - ENHANCED
           ========================================== */
        
        @media (max-width: 1024px) {
            :root {
                --content-max-width: calc(100vw - 250px);
                --sidebar-width: 250px;
            }
            
            .sidebar-placeholder {
                width: 250px;
            }
        }
        
        @media (max-width: 768px) {
            :root {
                --content-max-width: 100vw;
                --sidebar-width: 0px;
            }
            
            .sidebar-placeholder {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar-placeholder.show {
                transform: translateX(0);
            }
            
            .content-area {
                margin-left: 0;
                width: 100vw;
            }
            
            .container {
                padding: 0 0.5rem;
                max-width: calc(100vw - 1rem);
            }
            
            .page-header {
                padding: 2rem 0;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table {
                font-size: 0.875rem;
                min-width: 600px;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.75rem 0.5rem;
            }
            
            .material-btn {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 0.25rem;
                max-width: calc(100vw - 0.5rem);
            }
            
            .page-header {
                padding: 1.5rem 0;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .table {
                font-size: 0.75rem;
                min-width: 500px;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.5rem 0.25rem;
            }
        }

        /* ==========================================
           CUSTOM SCROLLBARS
           ========================================== */
        
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--header-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #3a3a3a;
        }

        /* Horizontal scrollbar for tables */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        /* ==========================================
           UTILITY CLASSES
           ========================================== */
        
        .text-gradient {
            background: var(--gradient-header);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shadow-custom {
            box-shadow: var(--shadow);
        }

        .shadow-custom-lg {
            box-shadow: var(--shadow-lg);
        }

        .border-custom {
            border: 1px solid var(--border-color);
        }

        .bg-gradient-primary {
            background: var(--gradient-primary);
        }

        .bg-gradient-secondary {
            background: var(--gradient-secondary);
        }

        .bg-gradient-header {
            background: var(--gradient-header);
        }

        /* Force prevent horizontal overflow on all elements */
        * {
            max-width: 100%;
        }

        /* Mobile menu toggle button */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--header-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Status badges with updated colors */
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 50px;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-primary {
            background-color: var(--header-color) !important;
        }

        .bg-warning {
            background-color: var(--primary-color) !important;
            color: var(--text-primary) !important;
        }

        /* Modal Styling */
.modal-content {
    background-color: var(--accent-color);
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: none;
}

.modal-header {
    background: var(--gradient-header);
    color: #fff;
    border-bottom: none;
    padding: 1rem 1.25rem;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
}

.modal-header .material-icons {
    font-size: 1.4rem;
}

.modal-header .btn-close {
    background: none;
    border: none;
    filter: invert(1);
}

.modal-body {
    padding: 1.5rem;
    color: var(--text-primary);
}

/* Info Card inside Modal */
.info-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.info-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    color: var(--primary-dark);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.4rem 0;
    border-bottom: 1px dashed var(--border-color);
    font-size: 0.95rem;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: var(--text-secondary);
}

.info-value {
    font-weight: 600;
    color: var(--text-primary);
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.8rem;
    border-radius: 8px;
}

.thank-you-section {
    background: var(--gradient-primary);
    padding: 1rem;
    border-radius: 10px;
    color: #fff;
    box-shadow: var(--shadow-sm);
}

.thank-you-title {
    display: flex;
    align-items: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.thank-you-text {
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}


</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="material-icons" style="font-size: 2rem; margin-right: 0.75rem; vertical-align: middle;">account_circle</i>
                Account Activities
            </h1>
            <p class="page-subtitle">Manage your registrations, sponsorships, and organization requests</p>
        </div>
    </div>

    <div class="container">
        {{-- Campaign Registrations Section --}}
        <div class="card mb-4 shadow-sm mt-5">
            <div class="card-header bg-warning text-dark">
                <h2 class="card-title mb-0 d-flex align-items-center">
                    <i class="material-icons me-2">event_available</i>
                    Campaign Registrations
                </h2>
            </div>
            <div class="card-body p-4">
<div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table id="registrationsTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Campaign Title</th>
                            <th>Specialty</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $i => $reg)
                        <tr>
                            <td><strong>#{{ $reg->id }}</strong></td>
                            <td>
                                <div class="fw-bold">{{ $reg->campaign->title ?? 'Health Camp Registration' }}</div>
                                <small class="text-muted">{{ $reg->campaign->location ?? 'Location TBD' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $reg->campaign->category ?? 'General Health' }}</span>
                            </td>
                            <td><strong class="text-success">₹{{ number_format($reg->amount ?? 0, 2) }}</strong></td>
                            <td>
                                <button class="btn btn-primary btn-sm me-1" onclick="viewRegistration({{ $reg->id }})">
                                    <i class="material-icons" style="font-size: 14px;">visibility</i> View
                                </button>
                                <button class="btn btn-secondary btn-sm" onclick="downloadRegistration({{ $reg->id }})">
                                    <i class="material-icons" style="font-size: 14px;">download</i> Invoice
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="material-icons text-muted" style="font-size: 3rem;">event_busy</i>
                                <p class="text-muted">No campaign registrations found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        {{-- Sponsor Details Section --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h2 class="card-title mb-0 d-flex align-items-center">
                    <i class="material-icons me-2">handshake</i>
                    Sponsor Details
                </h2>
            </div>
            <div class="card-body p-4">
              <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table id="sponsorsTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sponsor Name</th>
                            <th>Campaign & Doctor</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsorRequests as $i => $sponsor)
                        <tr>
                            <td><strong>#{{ $sponsor->id ?? $i+1 }}</strong></td>
                            <td>
                                <div class="fw-bold">{{ $sponsor->name ?? 'Healthcare Sponsor' }}</div>
                                <small class="text-muted">{{ $sponsor->email ?? 'sponsor@example.com' }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $sponsor->campaign_title ?? 'Health Campaign' }}</div>
                                <small class="text-muted">Dr. {{ $sponsor->doctor_name ?? auth()->user()->username }}</small>
                            </td>
                            <td><strong class="text-success">₹{{ number_format($sponsor->amount ?? 10000, 2) }}</strong></td>
                            <td>{{ $sponsor->payment_date ? \Carbon\Carbon::parse($sponsor->payment_date)->format('M d, Y') : now()->format('M d, Y') }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm me-1" onclick="viewSponsor({{ $sponsor->id ?? $i+1 }})">
                                    <i class="material-icons" style="font-size: 14px;">visibility</i> View
                                </button>
                                <button class="btn btn-secondary btn-sm" onclick="downloadSponsor({{ $sponsor->id ?? $i+1 }})">
                                    <i class="material-icons" style="font-size: 14px;">download</i> Receipt
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="material-icons text-muted" style="font-size: 3rem;">volunteer_activism</i>
                                <p class="text-muted">No sponsors found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        {{-- Organization Requests Section --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h2 class="card-title mb-0 d-flex align-items-center">
                    <i class="material-icons me-2">business</i>
                    Business Organization Requests
                </h2>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="organizationsTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Organization Name</th>
                            <th>Campaign Specification</th>
                            <th>No. of People</th>
                            <th>Assigned Doctor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizationNotices as $i => $org)
                        <tr>
                            <td><strong>#{{ $org->id ?? $i+1 }}</strong></td>
                            <td>
                                <div class="fw-bold">{{ $org->organization_name ?? 'Business Corp Ltd.' }}</div>
                                <small class="text-muted">{{ $org->email ?? 'contact@example.com' }}</small>
                            </td>
                            <td>
                                <div>{{ $org->description ?? 'Corporate Health Checkup' }}</div>
                                <small class="text-muted">{{ $org->location ?? 'Office Complex' }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ $org->number_of_people ?? '200+' }}</span></td>
                            <td>
                                @if($org->hiredDoctor)
                                    <button class="btn btn-link p-0 text-primary fw-bold" onclick="viewDoctorDetails({{ $org->hiredDoctor->id }})">
                                        Dr. {{ $org->hiredDoctor->doctor_name }}
                                    </button>
                                    <br>
                                    <small class="text-muted">{{ $org->hiredDoctor->specialty->name ?? 'General' }}</small>
                                @else
                                    <span class="text-muted">No doctor assigned</span>
                                @endif
                            </td>
                            <td>
                                @if(($org->status ?? 'pending') === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm me-1" onclick="viewOrganization({{ $org->id ?? $i+1 }})">
                                    <i class="material-icons" style="font-size: 14px;">visibility</i> View
                                </button>
                                <button class="btn btn-secondary btn-sm" onclick="downloadOrganization({{ $org->id ?? $i+1 }})">
                                    <i class="material-icons" style="font-size: 14px;">download</i> Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="material-icons text-muted" style="font-size: 3rem;">business</i>
                                <p class="text-muted">No organization requests found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>


        {{-- Modal HTML --}}
        <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel">
                            <i class="material-icons" style="margin-right: 0.5rem;">info</i>
                            Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
@include('user.partials.footer')
</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTables for all tables
    $('#registrationsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        autoWidth: false,
        columns: [
            { "width": "10%" },  // ID
            { "width": "40%" },  // Campaign Title
            { "width": "15%" },  // Specialty
            { "width": "15%" },  // Amount
            { "width": "20%", "orderable": false }  // Actions
        ],
        language: {
            search: "Search registrations:",
            lengthMenu: "Show _MENU_ registrations per page",
            info: "Showing _START_ to _END_ of _TOTAL_ registrations",
            emptyTable: "No registrations found",
            zeroRecords: "No matching registrations found"
        }
    });

    $('#sponsorsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        autoWidth: false,
        columns: [
            { "width": "10%" },  // ID
            { "width": "25%" },  // Sponsor Name
            { "width": "25%" },  // Campaign & Doctor
            { "width": "15%" },  // Amount
            { "width": "10%" },  // Date
            { "width": "15%", "orderable": false }  // Actions
        ],
        language: {
            search: "Search sponsors:",
            lengthMenu: "Show _MENU_ sponsors per page",
            info: "Showing _START_ to _END_ of _TOTAL_ sponsors",
            emptyTable: "No sponsors found",
            zeroRecords: "No matching sponsors found"
        }
    });

    $('#organizationsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        autoWidth: false,
        columns: [
            { "width": "10%" },  // ID
            { "width": "20%" },  // Organization Name
            { "width": "20%" },  // Campaign Specification
            { "width": "10%" },  // No. of People
            { "width": "15%" },  // Assigned Doctor
            { "width": "10%" },  // Status
            { "width": "15%", "orderable": false }  // Actions
        ],
        language: {
            search: "Search organizations:",
            lengthMenu: "Show _MENU_ organizations per page",
            info: "Showing _START_ to _END_ of _TOTAL_ organizations",
            emptyTable: "No organizations found",
            zeroRecords: "No matching organizations found"
        }
    });
});

// Modal functionality
function showModal(title, content) {
    document.getElementById('detailsModalLabel').innerHTML = `<i class="material-icons" style="margin-right: 0.5rem;">info</i>${title}`;
    document.getElementById('modalBody').innerHTML = content;
    var modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
}
console.log(data);
function viewRegistration(id) {
    const content = `
        <div class="info-card">
            <h4 class="info-card-title">
                <i class="material-icons" style="margin-right: 0.5rem;">event_available</i>
                Registration Information
            </h4>
            <div class="info-row">
                <span class="info-label">Registration ID:</span>
                <span class="info-value">#REG-${String(id).padStart(4, '0')}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Campaign:</span>
                <span class="info-value">Community Health Camp</span>
            </div>
            <div class="info-row">
                <span class="info-label">Location:</span>
                <span class="info-value">Healthcare Center, Medical District</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date & Time:</span>
                <span class="info-value">${new Date().toLocaleDateString('en-GB')} at 10:00 AM</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value"><span class="badge bg-success">Confirmed</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">Registration Fee:</span>
                <span class="info-value"><strong>₹0.00 (Free)</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Patient Name:</span>
                <span class="info-value">Registered Patient</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact:</span>
                <span class="info-value">+91 9876543210</span>
            </div>
        </div>
        
        <div class="info-card">
            <h4 class="info-card-title">
                <i class="material-icons" style="margin-right: 0.5rem;">medical_services</i>
                Services Included
            </h4>
            <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-primary); line-height: 1.8;">
                <li>General Health Checkup</li>
                <li>Blood Pressure Monitoring</li>
                <li>Blood Sugar Testing</li>
                <li>Basic Laboratory Tests</li>
                <li>Doctor Consultation</li>
                <li>Health Education Session</li>
            </ul>
        </div>
        
        <div class="thank-you-section">
            <h4 class="thank-you-title">
                <i class="material-icons" style="margin-right: 0.5rem;">favorite</i>
                Thank You for Registering!
            </h4>
            <p class="thank-you-text">
                Your registration has been confirmed. Please arrive 15 minutes early on the scheduled date. 
                Bring your ID proof and any existing medical records for better consultation.
            </p>
            <p style="margin: 0; font-size: 0.9rem; opacity: 0.8;">
                <i class="material-icons" style="font-size: 1rem; margin-right: 0.25rem;">phone</i>
                For any queries, contact us at: +91 9876543210
            </p>
        </div>
    `;
    showModal('Registration Details', content);
}

function viewSponsor(id) {
    const content = `
        <div class="info-card">
            <h4 class="info-card-title">
                <i class="material-icons" style="margin-right: 0.5rem;">handshake</i>
                Sponsor Information
            </h4>
            <div class="info-row">
                <span class="info-label">Sponsor ID:</span>
                <span class="info-value">#SPO-${String(id).padStart(4, '0')}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Organization:</span>
                <span class="info-value">Healthcare Sponsor</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">sponsor@example.com</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">+91 9876543210</span>
            </div>
            <div class="info-row">
                <span class="info-label">Campaign Title:</span>
                <span class="info-value">Health Campaign</span>
            </div>
            <div class="info-row">
                <span class="info-label">Doctor Name:</span>
                <span class="info-value">Dr. Healthcare Professional</span>
            </div>
            <div class="info-row">
                <span class="info-label">Sponsored Amount:</span>
                <span class="info-value"><strong>₹10,000</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Status:</span>
                <span class="info-value"><span class="badge bg-success">Completed</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Date:</span>
                <span class="info-value">${new Date().toLocaleDateString('en-GB')}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value"><span class="badge bg-success">Active</span></span>
            </div>
        </div>
        
        <div class="info-card">
            <h4 class="info-card-title">
                <i class="material-icons" style="margin-right: 0.5rem;">card_giftcard</i>
                Sponsorship Impact
            </h4>
            <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-primary); line-height: 1.8;">
                <li>Supporting community healthcare initiatives</li>
                <li>Providing quality medical services to underserved areas</li>
                <li>Enabling free health checkups for families in need</li>
                <li>Contributing to preventive healthcare programs</li>
                <li>Making healthcare accessible to all economic backgrounds</li>
            </ul>
        </div>
        
        <div class="thank-you-section">
            <h4 class="thank-you-title">
                <i class="material-icons" style="margin-right: 0.5rem;">emoji_emotions</i>
                Thank You for Your Support!
            </h4>
            <p class="thank-you-text">
                Your generous sponsorship helps us provide quality healthcare services to the community. 
                Together, we're making healthcare accessible to everyone.
            </p>
            <p style="margin: 0; font-size: 0.9rem; opacity: 0.8;">
                <i class="material-icons" style="font-size: 1rem; margin-right: 0.25rem;">card_membership</i>
                Your certificate will be mailed within 7 business days.
            </p>
        </div>
    `;
    showModal('Sponsor Details', content);
}

// Function to show modal with content
function showModal(title, content) {
    const modal = document.createElement('div');
    modal.classList.add('modal', 'fade');
    modal.tabIndex = -1;
    modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${title}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}


// View Organization Details
function viewOrganization(id) {
    const content = `
        <div class="info-card">
            <h4 class="info-card-title">
                <i class="material-icons" style="margin-right: 0.5rem;">business</i>
                Organization Information
            </h4>
            <div class="info-row">
                <span class="info-label">Request ID:</span>
                <span class="info-value">#ORG-${String(id).padStart(4, '0')}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Organization:</span>
                <span class="info-value">Healthcare Organization</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact Person:</span>
                <span class="info-value">Organization Representative</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">+91 9876543210</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">contact@organization.com</span>
            </div>
            <div class="info-row">
                <span class="info-label">Location:</span>
                <span class="info-value">Healthcare Complex, Medical District</span>
            </div>
            <div class="info-row">
                <span class="info-label">Expected Participants:</span>
                <span class="info-value">50 employees</span>
            </div>
            <div class="info-row">
                <span class="info-label">Service Duration:</span>
                <span class="info-value">${new Date().toLocaleDateString('en-GB')} - ${new Date(Date.now() + 30*24*60*60*1000).toLocaleDateString('en-GB')}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value"><span class="badge bg-success">Pending</span></span>
            </div>
        </div>
        
        <div class="info-card">
            <h4 class="info-card-title">
                <i class="material-icons" style="margin-right: 0.5rem;">medical_services</i>
                Requested Services
            </h4>
            <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-primary); line-height: 1.8;">
                <li>On-site Health Checkup Camp</li>
                <li>Vaccination Drive</li>
                <li>Health Awareness Sessions</li>
                <li>Emergency Medical Setup</li>
                <li>Health Records Management</li>
                <li>Follow-up Consultation Services</li>
            </ul>
        </div>
        
        <div class="thank-you-section">
            <h4 class="thank-you-title">
                <i class="material-icons" style="margin-right: 0.5rem;">celebration</i>
                Partnership Confirmed!
            </h4>
            <p class="thank-you-text">
                We're excited to partner with your organization to provide comprehensive healthcare services 
                to your employees. Our team will coordinate with your HR department for smooth execution.
            </p>
            <p style="margin: 0; font-size: 0.9rem; opacity: 0.8;">
                <i class="material-icons" style="font-size: 1rem; margin-right: 0.25rem;">description</i>
                Service agreement will be shared via email.
            </p>
        </div>
    `;
    showModal('Organization Details', content);
}

// Professional PDF Generation Functions
function generateProfessionalPDF(type, id, data) {
    // Create a new window for PDF generation
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    let content = '';
    const currentDate = new Date().toLocaleDateString('en-GB');
    const invoiceNumber = `${type.toUpperCase()}-${String(id).padStart(4, '0')}`;
    
    if (type === 'REG') {
        content = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Registration Invoice - ${invoiceNumber}</title>
                <style>
                    body { font-family: 'Arial', sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
                    .invoice-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                    .header { text-align: center; border-bottom: 3px solid #ffc107; padding-bottom: 20px; margin-bottom: 30px; }
                    .company-name { font-size: 28px; font-weight: bold; color: #343a40; margin-bottom: 5px; }
                    .company-tagline { color: #6c757d; font-size: 14px; }
                    .invoice-title { font-size: 24px; color: #ffc107; margin: 20px 0; }
                    .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
                    .bill-to, .invoice-info { flex: 1; }
                    .bill-to h3, .invoice-info h3 { color: #343a40; border-bottom: 2px solid #ffc107; padding-bottom: 10px; }
                    .services-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
                    .services-table th { background: #ffc107; color: white; padding: 15px; text-align: left; font-weight: bold; }
                    .services-table td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; }
                    .total-section { text-align: right; margin-top: 20px; }
                    .total-amount { font-size: 20px; font-weight: bold; color: #343a40; background: #fff3cd; padding: 15px; border-radius: 5px; }
                    .message-box { background: linear-gradient(135deg, #ffc107, #e6ac00); color: white; padding: 20px; border-radius: 10px; margin-top: 30px; text-align: center; }
                    .whatsapp-section { background: #25d366; color: white; padding: 15px; border-radius: 8px; margin-top: 20px; text-align: center; }
                    .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 12px; border-top: 1px solid #dee2e6; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class="invoice-container">
                    <div class="header">
                        <div class="company-name">🏥 FreeDoctor Healthcare</div>
                        <div class="company-tagline">Providing Quality Healthcare for Everyone</div>
                        <div class="invoice-title">REGISTRATION INVOICE</div>
                    </div>
                    
                    <div class="invoice-details">
                        <div class="bill-to">
                            <h3>👤 Bill To:</h3>
                            <p><strong>Patient Name:</strong> ${data.patientName}</p>
                            <p><strong>Patient ID:</strong> P-${String(id).padStart(4, '0')}</p>
                            <p><strong>Phone:</strong> ${data.patientPhone}</p>
                            <p><strong>Email:</strong> ${data.patientEmail}</p>
                        </div>
                        <div class="invoice-info">
                            <h3>📋 Invoice Details:</h3>
                            <p><strong>Invoice No:</strong> ${invoiceNumber}</p>
                            <p><strong>Date:</strong> ${currentDate}</p>
                            <p><strong>Status:</strong> <span style="color: #28a745;">✅ ${data.status}</span></p>
                            <p><strong>Payment:</strong> <span style="color: #28a745;">✅ Paid</span></p>
                        </div>
                    </div>
                    
                    <table class="services-table">
                        <thead>
                            <tr>
                                <th>🏥 Service Description</th>
                                <th>📅 Date</th>
                                <th>📍 Location</th>
                                <th>💰 Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${data.campaign}</td>
                                <td>${data.date}</td>
                                <td>${data.location}</td>
                                <td>₹${data.amount}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    ${data.services && data.services.length > 0 ? `
                    <div style="margin: 30px 0;">
                        <h3 style="color: #343a40; border-bottom: 2px solid #ffc107; padding-bottom: 10px;">🩺 Services Included:</h3>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <ul style="margin: 0; padding-left: 20px; line-height: 1.8;">
                                ${data.services.map(service => `<li style="margin-bottom: 8px; color: #495057;">✓ ${service}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="total-section">
                        <div class="total-amount">
                            💳 Total Amount: ₹${data.amount}
                        </div>
                    </div>
                    
                    <div class="message-box">
                        <h3>🎉 Registration Confirmed!</h3>
                        <p>Your health checkup registration has been successfully confirmed. Please arrive 15 minutes early on the scheduled date.</p>
                    </div>
                    
                    <div class="whatsapp-section">
                        <h4>📱 WhatsApp Updates</h4>
                        <p>We will notify you about the exact timing and any updates through WhatsApp: <strong>${data.patientPhone}</strong></p>
                        <p>Please save this number and stay connected!</p>
                    </div>
                    
                    <div class="footer">
                        <p>Thank you for choosing FreeDoctor Healthcare Services</p>
                        <p>📧 support@freedoctor.com | 📞 +91 9876543210 | 🌐 www.freedoctor.com</p>
                        <p><em>This is a computer-generated invoice. No signature required.</em></p>
                    </div>
                </div>
            </body>
            </html>
        `;
    } else if (type === 'SPO') {
        content = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Sponsorship Invoice - ${invoiceNumber}</title>
                <style>
                    body { font-family: 'Arial', sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
                    .invoice-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                    .header { text-align: center; border-bottom: 3px solid #ffc107; padding-bottom: 20px; margin-bottom: 30px; }
                    .company-name { font-size: 28px; font-weight: bold; color: #343a40; margin-bottom: 5px; }
                    .company-tagline { color: #6c757d; font-size: 14px; }
                    .invoice-title { font-size: 24px; color: #ffc107; margin: 20px 0; }
                    .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
                    .bill-to, .invoice-info { flex: 1; }
                    .bill-to h3, .invoice-info h3 { color: #343a40; border-bottom: 2px solid #ffc107; padding-bottom: 10px; }
                    .services-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
                    .services-table th { background: #ffc107; color: white; padding: 15px; text-align: left; font-weight: bold; }
                    .services-table td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; }
                    .total-section { text-align: right; margin-top: 20px; }
                    .total-amount { font-size: 20px; font-weight: bold; color: #343a40; background: #fff3cd; padding: 15px; border-radius: 5px; }
                    .thank-you-section { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 25px; border-radius: 10px; margin-top: 30px; text-align: center; }
                    .benefits-section { background: #e8f5e8; padding: 20px; border-radius: 8px; margin-top: 20px; }
                    .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 12px; border-top: 1px solid #dee2e6; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class="invoice-container">
                    <div class="header">
                        <div class="company-name">🏥 FreeDoctor Healthcare</div>
                        <div class="company-tagline">Providing Quality Healthcare for Everyone</div>
                        <div class="invoice-title">SPONSORSHIP RECEIPT</div>
                    </div>
                    
                    <div class="invoice-details">
                        <div class="bill-to">
                            <h3>🏢 Sponsor Details:</h3>
                            <p><strong>Organization:</strong> HealthCare Partners Medical Center</p>
                            <p><strong>Contact Person:</strong> Dr. Sarah Johnson</p>
                            <p><strong>Phone:</strong> +91 9876543210</p>
                            <p><strong>Email:</strong> sarah.johnson@healthcarepartners.com</p>
                        </div>
                        <div class="invoice-info">
                            <h3>📋 Receipt Details:</h3>
                            <p><strong>Receipt No:</strong> ${invoiceNumber}</p>
                            <p><strong>Date:</strong> ${currentDate}</p>
                            <p><strong>Status:</strong> <span style="color: #28a745;">✅ Active</span></p>
                            <p><strong>Payment:</strong> <span style="color: #28a745;">✅ Completed</span></p>
                        </div>
                    </div>
                    
                    <table class="services-table">
                        <thead>
                            <tr>
                                <th>💝 Sponsorship Details</th>
                                <th>📅 Period</th>
                                <th>🎯 Campaign</th>
                                <th>💰 Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Healthcare Campaign Sponsorship</td>
                                <td>Annual Sponsorship</td>
                                <td>Community Health Initiative</td>
                                <td>₹25,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="total-section">
                        <div class="total-amount">
                            💝 Total Sponsorship: ₹25,000.00
                        </div>
                    </div>
                    
                    <div class="benefits-section">
                        <h3>🎁 Sponsorship Benefits:</h3>
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <li>✅ Company logo on all campaign materials</li>
                            <li>✅ Recognition during campaign events</li>
                            <li>✅ Certificate of appreciation</li>
                            <li>✅ Annual sponsorship report</li>
                            <li>✅ Tax exemption certificate</li>
                        </ul>
                    </div>
                    
                    <div class="thank-you-section">
                        <h3>🙏 Heartfelt Thanks!</h3>
                        <p>Your generous sponsorship makes a real difference in our community's health and well-being. Together, we're building a healthier future for everyone.</p>
                        <p><strong>Your support helps us serve over 1000+ patients monthly!</strong></p>
                    </div>
                    
                    <div class="footer">
                        <p>Thank you for being our valued sponsor and health partner</p>
                        <p>📧 partnerships@freedoctor.com | 📞 +91 9876543210 | 🌐 www.freedoctor.com</p>
                        <p><em>This is a computer-generated receipt. No signature required.</em></p>
                    </div>
                </div>
            </body>
            </html>
        `;
    } else if (type === 'ORG') {
        content = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Organization Agreement - ${invoiceNumber}</title>
                <style>
                    body { font-family: 'Arial', sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
                    .invoice-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                    .header { text-align: center; border-bottom: 3px solid #ffc107; padding-bottom: 20px; margin-bottom: 30px; }
                    .company-name { font-size: 28px; font-weight: bold; color: #343a40; margin-bottom: 5px; }
                    .company-tagline { color: #6c757d; font-size: 14px; }
                    .invoice-title { font-size: 24px; color: #ffc107; margin: 20px 0; }
                    .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
                    .bill-to, .invoice-info { flex: 1; }
                    .bill-to h3, .invoice-info h3 { color: #343a40; border-bottom: 2px solid #ffc107; padding-bottom: 10px; }
                    .services-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
                    .services-table th { background: #ffc107; color: white; padding: 15px; text-align: left; font-weight: bold; }
                    .services-table td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; }
                    .contact-section { background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 25px; border-radius: 10px; margin-top: 30px; text-align: center; }
                    .terms-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ffc107; }
                    .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 12px; border-top: 1px solid #dee2e6; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class="invoice-container">
                    <div class="header">
                        <div class="company-name">🏥 FreeDoctor Healthcare</div>
                        <div class="company-tagline">Corporate Health Solutions</div>
                        <div class="invoice-title">SERVICE AGREEMENT</div>
                    </div>
                    
                    <div class="invoice-details">
                        <div class="bill-to">
                            <h3>🏢 Organization Details:</h3>
                            <p><strong>Company:</strong> TechCorp Solutions Pvt. Ltd.</p>
                            <p><strong>Contact Person:</strong> Mr. Rajesh Kumar</p>
                            <p><strong>Phone:</strong> +91 9876543210</p>
                            <p><strong>Location:</strong> Tech Park, Electronic City</p>
                        </div>
                        <div class="invoice-info">
                            <h3>📋 Agreement Details:</h3>
                            <p><strong>Agreement No:</strong> ${invoiceNumber}</p>
                            <p><strong>Date:</strong> ${currentDate}</p>
                            <p><strong>Status:</strong> <span style="color: #28a745;">✅ Approved</span></p>
                            <p><strong>Duration:</strong> 7 Days</p>
                        </div>
                    </div>
                    
                    <table class="services-table">
                        <thead>
                            <tr>
                                <th>🏥 Healthcare Services</th>
                                <th>👥 Coverage</th>
                                <th>📅 Schedule</th>
                                <th>📍 Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>On-site Health Checkup Camp</td>
                                <td>250+ Employees</td>
                                <td>Weekdays 9AM-5PM</td>
                                <td>Corporate Office</td>
                            </tr>
                            <tr>
                                <td>Vaccination Drive</td>
                                <td>All Employees</td>
                                <td>As per schedule</td>
                                <td>Conference Hall</td>
                            </tr>
                            <tr>
                                <td>Health Awareness Sessions</td>
                                <td>Management + Staff</td>
                                <td>Weekly Sessions</td>
                                <td>Auditorium</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="terms-section">
                        <h3>📋 Service Inclusions:</h3>
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <li>✅ Complete health screening for all employees</li>
                            <li>✅ Professional medical team on-site</li>
                            <li>✅ Health reports and recommendations</li>
                            <li>✅ Emergency medical support during service period</li>
                            <li>✅ Follow-up consultation services</li>
                        </ul>
                    </div>
                    
                    <div class="contact-section">
                        <h3>📞 We'll Contact You Soon!</h3>
                        <p>Our healthcare coordination team will reach out to you within 24-48 hours to finalize the service schedule and arrangements.</p>
                        <p><strong>Coordination Manager:</strong> Dr. Priya Sharma</p>
                        <p><strong>Direct Line:</strong> +91 9876543210</p>
                        <p><em>We're excited to partner with your organization for better employee health!</em></p>
                    </div>
                    
                    <div class="footer">
                        <p>Thank you for choosing FreeDoctor Healthcare for your corporate wellness needs</p>
                        <p>📧 corporate@freedoctor.com | 📞 +91 9876543210 | 🌐 www.freedoctor.com</p>
                        <p><em>This is a computer-generated agreement. Digital signature applied.</em></p>
                    </div>
                </div>
            </body>
            </html>
        `;
    }
    
    printWindow.document.write(content);
    printWindow.document.close();
    
    // Wait for content to load, then print
    setTimeout(() => {
        printWindow.print();
        
        // Show success message
        showSuccessToast(`${type === 'REG' ? 'Registration Invoice' : type === 'SPO' ? 'Sponsorship Certificate' : 'Service Agreement'} is being prepared for download!`);
        
        // Close window after delay
        setTimeout(() => {
            printWindow.close();
        }, 1000);
    }, 1000);
}

function showSuccessToast(message) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-success position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 350px; animation: slideIn 0.3s ease-out;';
    toast.innerHTML = `
        <div style="display: flex; align-items: center;">
            <i class="material-icons" style="margin-right: 0.5rem; color: #28a745;">check_circle</i>
            ${message}
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Improved Download Functions - Real File Downloads
function downloadRegistration(data) {
    const content = generateRegistrationHTML(data);
    downloadAsFile(content, `Registration_Invoice_${data.id}.html`);
    showSuccessToast('Registration Invoice downloaded successfully!');
}

function downloadSponsor(id) {
    const content = generateSponsorHTML(id);
    downloadAsFile(content, `Sponsor_Certificate_${id}.html`);
    showSuccessToast('Sponsorship Certificate downloaded successfully!');
}

function downloadOrganization(id) {
    const content = generateOrganizationHTML(id);
    downloadAsFile(content, `Organization_Agreement_${id}.html`);
    showSuccessToast('Organization Agreement downloaded successfully!');
}

// Helper function to download content as file
function downloadAsFile(content, filename) {
    const blob = new Blob([content], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

function generateRegistrationHTML(data) {
    return `<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Registration Invoice</title>
<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f8f9fa;}
.container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:10px;box-shadow:0 4px 6px rgba(0,0,0,0.1);}
.header{text-align:center;border-bottom:3px solid #28a745;padding-bottom:20px;margin-bottom:30px;}
.header h1{color:#28a745;margin:0;font-size:2.5rem;}
.details{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-bottom:30px;}
.amount{background:#28a745;color:white;padding:20px;border-radius:8px;text-align:center;margin:20px 0;}
.table{width:100%;border-collapse:collapse;margin:20px 0;}
.table th,.table td{border:1px solid #ddd;padding:12px;text-align:left;}
.table th{background:#f8f9fa;font-weight:bold;}
.footer{text-align:center;margin-top:30px;padding-top:20px;border-top:2px solid #eee;color:#666;}
</style></head><body>
<div class="container">
    <div class="header">
        <h1>🏥 FreeDoctor Healthcare</h1>
        <p>Registration Invoice #${data.id || 'N/A'}</p>
        <p>Generated: ${new Date().toLocaleDateString()}</p>
    </div>
    <div class="details">
        <div><h3>Patient Information</h3>
            <p><strong>Name:</strong> ${data.patientName || 'Not specified'}</p>
            <p><strong>Phone:</strong> ${data.patientPhone || 'Not specified'}</p>
            <p><strong>Email:</strong> ${data.patientEmail || 'Not specified'}</p>
        </div>
        <div><h3>Campaign Details</h3>
            <p><strong>Campaign:</strong> ${data.campaign || 'Health Camp'}</p>
            <p><strong>Location:</strong> ${data.location || 'TBD'}</p>
            <p><strong>Date:</strong> ${data.date || new Date().toLocaleDateString()}</p>
        </div>
    </div>
    <div class="amount"><h2>Total Amount: ₹${data.amount || '0.00'}</h2></div>
    <table class="table">
        <tr><th>Service</th><th>Description</th><th>Amount</th></tr>
        <tr><td>Health Camp Registration</td><td>Registration for health campaign</td><td>₹${data.amount || '0.00'}</td></tr>
        <tr><td>Medical Consultation</td><td>Professional healthcare consultation</td><td>Included</td></tr>
    </table>
    <div class="footer">
        <p><strong>Thank you for choosing FreeDoctor Healthcare!</strong></p>
        <p>📧 support@freedoctor.com | 📞 +91 9876543210</p>
    </div>
</div></body></html>`;
}

function generateSponsorHTML(id) {
    return `<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Sponsorship Certificate</title>
<style>
body{font-family:Georgia,serif;margin:20px;background:#f8f9fa;}
.container{max-width:800px;margin:0 auto;background:white;padding:40px;border-radius:15px;box-shadow:0 8px 16px rgba(0,0,0,0.1);border:3px solid #ffc107;}
.header{text-align:center;margin-bottom:40px;}
.header h1{color:#e6ac00;margin:0;font-size:3rem;}
.title{text-align:center;font-size:2rem;color:#333;margin:30px 0;font-weight:bold;text-transform:uppercase;}
.content{text-align:center;line-height:1.8;font-size:1.1rem;margin:30px 0;}
.sponsor-name{font-size:2.5rem;color:#e6ac00;font-weight:bold;margin:20px 0;}
.details{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:30px 0;}
.detail-box{background:#f8f9fa;padding:20px;border-radius:8px;border-left:4px solid #ffc107;}
.footer{text-align:center;margin-top:40px;color:#666;border-top:2px solid #eee;padding-top:20px;}
</style></head><body>
<div class="container">
    <div class="header">
        <h1>🏥 FreeDoctor Healthcare</h1>
        <p style="font-size:1.2rem;color:#666;">Making Healthcare Accessible to All</p>
    </div>
    <div class="title">Certificate of Appreciation</div>
    <div class="content">
        <p>This certificate is proudly presented to</p>
        <div class="sponsor-name">Healthcare Sponsor #${id}</div>
        <p>In grateful recognition of your generous sponsorship and commitment to community health.</p>
    </div>
    <div class="details">
        <div class="detail-box">
            <h4>🎯 Sponsorship Details</h4>
            <p><strong>Sponsor ID:</strong> #${id}</p>
            <p><strong>Date:</strong> ${new Date().toLocaleDateString()}</p>
        </div>
        <div class="detail-box">
            <h4>💝 Impact Summary</h4>
            <p><strong>Beneficiaries:</strong> 200+ People</p>
            <p><strong>Services:</strong> Health Checkups</p>
        </div>
    </div>
    <div class="footer">
        <p><strong>Certificate issued on ${new Date().toLocaleDateString()}</strong></p>
        <p>📧 sponsors@freedoctor.com | 📞 +91 9876543210</p>
    </div>
</div></body></html>`;
}

function generateOrganizationHTML(id) {
    return `<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Organization Agreement</title>
<style>
body{font-family:'Times New Roman',serif;margin:20px;background:#f8f9fa;line-height:1.6;}
.container{max-width:800px;margin:0 auto;background:white;padding:40px;border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,0.1);}
.header{text-align:center;border-bottom:3px solid #0056b3;padding-bottom:20px;margin-bottom:30px;}
.header h1{color:#0056b3;margin:0;font-size:2.5rem;}
.title{text-align:center;font-size:1.8rem;color:#333;margin:30px 0;font-weight:bold;}
.section{margin:25px 0;}
.section h3{color:#0056b3;border-bottom:2px solid #eee;padding-bottom:5px;}
.table{width:100%;border-collapse:collapse;margin:20px 0;}
.table th,.table td{border:1px solid #ddd;padding:12px;text-align:left;}
.table th{background:#f8f9fa;font-weight:bold;}
.footer{text-align:center;margin-top:30px;padding-top:20px;border-top:2px solid #eee;color:#666;}
</style></head><body>
<div class="container">
    <div class="header">
        <h1>🏥 FreeDoctor Healthcare Services</h1>
        <p>Corporate Wellness Solutions</p>
    </div>
    <div class="title">Corporate Healthcare Service Agreement</div>
    <div class="section">
        <h3>📋 Agreement Overview</h3>
        <p><strong>Agreement ID:</strong> #${id}</p>
        <p><strong>Organization:</strong> Business Corp Ltd.</p>
        <p><strong>Date:</strong> ${new Date().toLocaleDateString()}</p>
    </div>
    <table class="table">
        <tr><th>Service</th><th>Coverage</th><th>Schedule</th></tr>
        <tr><td>Health Checkup Camp</td><td>200+ Employees</td><td>Weekdays 9AM-5PM</td></tr>
        <tr><td>Vaccination Drive</td><td>All Employees</td><td>As scheduled</td></tr>
        <tr><td>Health Awareness</td><td>Management + Staff</td><td>Weekly Sessions</td></tr>
    </table>
    <div class="footer">
        <p><strong>Thank you for choosing FreeDoctor Healthcare!</strong></p>
        <p>📧 corporate@freedoctor.com | 📞 +91 9876543210</p>
    </div>
</div></body></html>`;
}

// Update existing download functions to use new method
function downloadRegistration(data) {
    generateProfessionalPDF('REG', data.id, {
        patientName: data.patientName,
        patientPhone: data.patientPhone,
        patientEmail: data.patientEmail,
        campaign: data.campaign,
        location: data.location,
        amount: data.amount,
        date: data.date,
        status: data.status,
        services: data.services || []
    });
}

function downloadSponsor(data) {
    generateProfessionalPDF('SPO', data.id, {
        organizationName: data.name,
        amount: data.amount,
        contactPerson: data.contactPerson,
        phone: data.phone,
        email: data.email,
        paymentStatus: data.paymentStatus,
        date: data.date,
        status: data.status
    });
}

function downloadOrganization(data) {
    generateProfessionalPDF('ORG', data.id, {
        companyName: data.name,
        contactPerson: data.contactPerson,
        phone: data.phone,
        email: data.email,
        location: data.location,
        employees: data.people,
        dateFrom: data.dateFrom,
        dateTo: data.dateTo,
        status: data.status
    });
}

// View Doctor Details Function
function viewDoctorDetails(doctorId) {
    // Fetch doctor details via AJAX
    fetch(`/user/doctor-details/${doctorId}`)
        .then(response => response.json())
        .then(doctor => {
            const content = `
                <div class="info-card">
                    <h4 class="info-card-title">
                        <i class="material-icons" style="margin-right: 0.5rem;">person</i>
                        Doctor Information
                    </h4>
                    <div class="info-row">
                        <span class="info-label">Doctor Name:</span>
                        <span class="info-value">Dr. ${doctor.doctor_name}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Specialty:</span>
                        <span class="info-value">${doctor.specialty?.name || 'General Medicine'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Experience:</span>
                        <span class="info-value">${doctor.experience || 'Not specified'} years</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Hospital/Clinic:</span>
                        <span class="info-value">${doctor.hospital_name || 'Not specified'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Location:</span>
                        <span class="info-value">${doctor.location || 'Not specified'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value"><span class="badge bg-success">Verified</span></span>
                    </div>
                </div>
                
                <div class="info-card">
                    <h4 class="info-card-title">
                        <i class="material-icons" style="margin-right: 0.5rem;">medical_services</i>
                        Professional Summary
                    </h4>
                    <p style="margin: 0; color: var(--text-primary); line-height: 1.6;">
                        ${doctor.description || 'Experienced medical professional dedicated to providing quality healthcare services.'}
                    </p>
                </div>
                
                <div class="thank-you-section">
                    <h4 class="thank-you-title">
                        <i class="material-icons" style="margin-right: 0.5rem;">local_hospital</i>
                        Assigned to Your Organization
                    </h4>
                    <p class="thank-you-text">
                        This doctor has been carefully selected and assigned to handle your organization's healthcare requirements.
                        They will coordinate with your team to ensure smooth execution of all medical services.
                    </p>
                    <p style="margin: 0; font-size: 0.9rem; opacity: 0.8;">
                        <i class="material-icons" style="font-size: 1rem; margin-right: 0.25rem;">schedule</i>
                        For appointments or queries, contact through our platform.
                    </p>
                </div>
            `;
            showModal('Doctor Details', content);
        })
        .catch(error => {
            console.error('Error fetching doctor details:', error);
            // Fallback content if API fails
            const content = `
                <div class="info-card">
                    <h4 class="info-card-title">
                        <i class="material-icons" style="margin-right: 0.5rem;">person</i>
                        Doctor Information
                    </h4>
                    <div class="info-row">
                        <span class="info-label">Doctor ID:</span>
                        <span class="info-value">#DR-${String(doctorId).padStart(4, '0')}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value"><span class="badge bg-success">Assigned</span></span>
                    </div>
                </div>
                
                <div class="thank-you-section">
                    <h4 class="thank-you-title">
                        <i class="material-icons" style="margin-right: 0.5rem;">info</i>
                        Doctor Information
                    </h4>
                    <p class="thank-you-text">
                        A qualified medical professional has been assigned to your organization. 
                        Detailed information will be shared during the service coordination.
                    </p>
                </div>
            `;
            showModal('Doctor Details', content);
        });
}
</script>
@endsection

