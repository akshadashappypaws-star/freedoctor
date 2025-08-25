<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Doctor Dashboard') - FreeDoctor</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet" />

    <!-- Additional CSS -->
    @stack('css')
    
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --secondary-color: #858796;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light-color);
        }

        .sidebar {
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        }

        .sidebar .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .nav-item .nav-link:hover {
            color: #fff;
        }

                .sidebar .nav-item.active .nav-link {
            color: #fff;
        }
        
        /* Search Results Dropdown Styles */
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .search-results-container {
            padding: 1rem;
        }
        
        .search-result-item {
            padding: 0.75rem;
            border-bottom: 1px solid #f8f9fc;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }
        
        .search-result-item:hover {
            background-color: #f8f9fc;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .search-result-title {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.25rem;
        }
        
        .search-result-subtitle {
            font-size: 0.875rem;
            color: #858796;
            margin-bottom: 0.25rem;
        }
        
        .search-result-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .search-result-category {
            display: inline-block;
            background: #4e73df;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-right: 0.5rem;
        }
        
        .search-result-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            float: left;
        }
        
        .search-no-results {
            text-align: center;
            padding: 2rem;
            color: #858796;
        }
        
        .search-loading {
            text-align: center;
            padding: 1rem;
            color: #858796;
        }
        
        .mobile-search-results {
            max-height: 300px;
            overflow-y: auto;
        }
        
        /* Make search form relative for dropdown positioning */
        .navbar-search {
            position: relative;
        }

        .sidebar-brand {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e3e6f0;
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }

        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }

        .border-left-info {
            border-left: 0.25rem solid var(--info-color) !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('doctor.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="sidebar-brand-text mx-3">FreeDoctor <sup>Dr</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::routeIs('doctor.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('doctor.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">
                Campaign Management
            </div>

            <!-- Nav Item - Campaigns -->
            <li class="nav-item {{ Request::routeIs('doctor.campaigns*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('doctor.campaigns') }}">
                    <i class="fas fa-fw fa-calendar-plus"></i>
                    <span>My Campaigns</span></a>
            </li>

            <!-- Nav Item - Patients -->
            <li class="nav-item {{ Request::routeIs('doctor.patients*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('doctor.patients') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Patient Registrations</span></a>
            </li>

            <!-- Nav Item - Sponsors -->
            <li class="nav-item {{ Request::routeIs('doctor.sponsors*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('doctor.sponsors') }}">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Sponsorships</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">
                Business
            </div>

            <!-- Nav Item - Business Reach Out -->
            <li class="nav-item {{ Request::routeIs('doctor.business*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('doctor.business-reach-out') }}">
                    <i class="fas fa-fw fa-briefcase"></i>
                    <span>Business Opportunities</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">
                Account
            </div>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ Request::routeIs('doctor.profile*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('doctor.profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span></a>
            </li>

            <!-- Nav Item - Notifications -->
            <li class="nav-item {{ Request::routeIs('doctor.notifications*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('doctor.notifications') }}">
                    <i class="fas fa-fw fa-bell"></i>
                    <span>Notifications</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block" />

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" id="universalSearchInput" class="form-control bg-light border-0 small" placeholder="Search campaigns, doctors, categories... (min 3 chars)" aria-label="Search" aria-describedby="basic-addon2" />
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="universalSearchBtn">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Search Results Dropdown -->
                        <div class="search-results-dropdown" id="searchResultsDropdown" style="display: none;">
                            <div class="search-results-container">
                                <div id="searchResultsContent"></div>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" id="universalSearchInputMobile" class="form-control bg-light border-0 small" placeholder="Search... (min 3 chars)" aria-label="Search" aria-describedby="basic-addon2" />
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="universalSearchBtnMobile">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- Mobile Search Results -->
                                <div class="mobile-search-results mt-3" id="mobileSearchResults" style="display: none;">
                                    <div id="mobileSearchResultsContent"></div>
                                </div>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    Dr. {{ Auth::guard('doctor')->user()->name ?? 'Doctor' }}
                                </span>
                                <img class="img-profile rounded-circle" src="https://via.placeholder.com/60x60?text=Dr" alt="Doctor Profile" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('doctor.profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <!-- Welcome Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Welcome to FreeDoctor</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h1 class="h3 text-gray-900 mb-4">Healthcare Made Accessible & Affordable</h1>
                                        <p class="lead mb-4">Connect with verified doctors, join sponsored medical campaigns, and get quality healthcare at up to 80% discount through our community platform.</p>
                                        
                                        <!-- Stats Cards -->
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6 mb-4">
                                                <div class="card border-left-primary shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Verified Doctors</div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">500+</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-user-md fa-2x text-gray-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-3 col-md-6 mb-4">
                                                <div class="card border-left-success shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Campaigns</div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">150+</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-3 col-md-6 mb-4">
                                                <div class="card border-left-info shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Happy Patients</div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">50,000+</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-heart fa-2x text-gray-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-3 col-md-6 mb-4">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sponsors</div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">200+</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-hand-holding-heart fa-2x text-gray-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Call to Action -->
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <a href="{{ route('user.campaigns') }}" class="btn btn-primary btn-lg mr-3">
                                                    <i class="fas fa-search mr-2"></i>Explore Campaigns
                                                </a>
                                                <a href="{{ route('user.register') }}" class="btn btn-success btn-lg">
                                                    <i class="fas fa-user-plus mr-2"></i>Join Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search Demo Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Search Demo</h6>
                                </div>
                                <div class="card-body">
                                    <p>Try the search functionality in the top navigation bar. Type "rural", "dental", "heart" or any other medical term to see the AJAX search results.</p>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Features:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>Real-time search as you type</li>
                                            <li>Search campaigns, doctors, and categories</li>
                                            <li>Results appear below the search bar</li>
                                            <li>Click on results to view details</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; {{ date('Y') }} FreeDoctor. Medical services made accessible.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('doctor.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>

    <!-- Additional JS -->
    @stack('js')

    <script>
        // Add some interactivity
        $(document).ready(function() {
            // Toggle sidebar
            $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
                $("body").toggleClass("sidebar-toggled");
                $(".sidebar").toggleClass("toggled");
                if ($(".sidebar").hasClass("toggled")) {
                    $('.sidebar .collapse').collapse('hide');
                }
            });

            // Close any open menu accordions when window is resized below 768px
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    $('.sidebar .collapse').collapse('hide');
                }
                
                // Toggle the side navigation when window is resized below 480px
                if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
                    $("body").addClass("sidebar-toggled");
                    $(".sidebar").addClass("toggled");
                    $('.sidebar .collapse').collapse('hide');
                }
            });

            // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
            $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
                if ($(window).width() > 768) {
                    var e0 = e.originalEvent,
                        delta = e0.wheelDelta || -e0.detail;
                    this.scrollTop += (delta < 0 ? 1 : -1) * 30;
                    e.preventDefault();
                }
            });
            
            // Universal Search Functionality
            let searchTimeout;
            
            function performUniversalSearch(query, isDesktop = true) {
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                // Hide results if query is too short
                if (!query || query.length < 3) {
                    hideSearchResults();
                    return;
                }
                
                // Debounce search
                searchTimeout = setTimeout(() => {
                    const resultsContainer = isDesktop ? '#searchResultsContent' : '#mobileSearchResultsContent';
                    const dropdown = isDesktop ? '#searchResultsDropdown' : '#mobileSearchResults';
                    
                    $(resultsContainer).html('<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
                    $(dropdown).show();
                    
                    $.ajax({
                        url: '{{ route("user.campaigns.search") }}',
                        method: 'POST',
                        data: {
                            search: query,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Search response:', response); // Debug
                            displaySearchResults(response, isDesktop);
                        },
                        error: function(xhr) {
                            console.error('Search error:', xhr);
                            $(resultsContainer).html('<div class="search-no-results"><i class="fas fa-exclamation-triangle"></i><br>Search failed. Please try again.</div>');
                        }
                    });
                }, 500); // Increased debounce time
            }
            
            function displaySearchResults(data, isDesktop = true) {
                const resultsContainer = isDesktop ? '#searchResultsContent' : '#mobileSearchResultsContent';
                const dropdown = isDesktop ? '#searchResultsDropdown' : '#mobileSearchResults';
                
                // Check if we have any results
                const hasCampaigns = data.campaigns && data.campaigns.length > 0;
                const hasCategories = data.categories && data.categories.length > 0;
                const hasDoctors = data.doctors && data.doctors.length > 0;
                const hasSpecialties = data.specialties && data.specialties.length > 0;
                
                if (!hasCampaigns && !hasCategories && !hasDoctors && !hasSpecialties) {
                    $(resultsContainer).html('<div class="search-no-results"><i class="fas fa-search"></i><br>No results found</div>');
                    $(dropdown).show();
                    return;
                }
                
                let html = '';
                
                // Display categories first (only if they exist and are relevant)
                if (hasCategories) {
                    html += '<div class="search-section-title" style="font-weight: bold; padding: 0.5rem 0; color: #667eea; border-bottom: 1px solid #e3e6f0; margin-bottom: 0.5rem;"><i class="material-icons" style="font-size: 16px; vertical-align: middle;">category</i> Medical Categories</div>';
                    data.categories.forEach(category => {
                        html += `
                            <div class="search-result-item" onclick="window.location.href='{{ route("user.campaigns") }}?category=${encodeURIComponent(category.category_name)}'">
                                <div class="search-result-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <span class="material-icons" style="color: white; font-size: 18px;">${category.icon_class || 'local_hospital'}</span>
                                </div>
                                <div style="overflow: hidden;">
                                    <div class="search-result-title" style="color: #667eea;">${category.category_name}</div>
                                    <div class="search-result-subtitle" style="color: #8e9bae;">Medical Category</div>
                                    <div class="search-result-meta">
                                        <i class="fas fa-stethoscope"></i> ${category.campaigns_count || 0} active campaigns
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                
                // Display campaigns
                if (hasCampaigns) {
                    html += '<div class="search-section-title" style="font-weight: bold; padding: 0.5rem 0; color: #1cc88a; border-bottom: 1px solid #e3e6f0; margin-bottom: 0.5rem;"><i class="fas fa-stethoscope" style="margin-right: 8px;"></i>Health Campaigns</div>';
                    data.campaigns.forEach(campaign => {
                        html += `
                            <div class="search-result-item" onclick="window.location.href='${campaign.view_url}'">
                                <div class="search-result-icon" style="background: linear-gradient(135deg, #1cc88a, #17a673);">
                                    <span class="material-icons" style="color: white; font-size: 18px;">${campaign.category_icon || 'local_hospital'}</span>
                                </div>
                                <div style="overflow: hidden;">
                                    <div class="search-result-title">${campaign.title}</div>
                                    <div class="search-result-subtitle">
                                        <span class="search-result-category" style="background: #1cc88a;">${campaign.category}</span>
                                        Dr. ${campaign.doctor_name} - ${campaign.specialty}
                                    </div>
                                    <div class="search-result-meta">
                                        <i class="fas fa-map-marker-alt"></i> ${campaign.location} | 
                                        <i class="fas fa-calendar"></i> ${campaign.start_date}
                                        ${campaign.registration_payment > 0 ? ' | <i class="fas fa-rupee-sign"></i> ₹' + campaign.registration_payment : ' | <span style="color: #1cc88a; font-weight: bold;">Free</span>'}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                
                // Display doctors if found
                if (hasDoctors) {
                    html += '<div class="search-section-title" style="font-weight: bold; padding: 0.5rem 0; color: #f6c23e; border-bottom: 1px solid #e3e6f0; margin-bottom: 0.5rem;"><i class="fas fa-user-md" style="margin-right: 8px;"></i>Doctors</div>';
                    data.doctors.forEach(doctor => {
                        html += `
                            <div class="search-result-item">
                                <div class="search-result-icon" style="background: linear-gradient(135deg, #f6c23e, #daa520);">
                                    <i class="fas fa-user-md" style="color: white; font-size: 18px;"></i>
                                </div>
                                <div style="overflow: hidden;">
                                    <div class="search-result-title">Dr. ${doctor.name}</div>
                                    <div class="search-result-subtitle">${doctor.specialty}</div>
                                    <div class="search-result-meta">
                                        <i class="fas fa-graduation-cap"></i> ${doctor.qualification}
                                        ${doctor.experience !== 'Not specified' ? ' | <i class="fas fa-clock"></i> ' + doctor.experience : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                
                // Display specialties if found
                if (hasSpecialties) {
                    html += '<div class="search-section-title" style="font-weight: bold; padding: 0.5rem 0; color: #e74a3b; border-bottom: 1px solid #e3e6f0; margin-bottom: 0.5rem;"><i class="fas fa-heartbeat" style="margin-right: 8px;"></i>Specialties</div>';
                    data.specialties.forEach(specialty => {
                        html += `
                            <div class="search-result-item" onclick="window.location.href='{{ route("user.campaigns") }}?specialty=${encodeURIComponent(specialty.name)}'">
                                <div class="search-result-icon" style="background: linear-gradient(135deg, #e74a3b, #c0392b);">
                                    <i class="fas fa-heartbeat" style="color: white; font-size: 18px;"></i>
                                </div>
                                <div style="overflow: hidden;">
                                    <div class="search-result-title">${specialty.name}</div>
                                    <div class="search-result-subtitle">Medical Specialty</div>
                                </div>
                            </div>
                        `;
                    });
                }
                
                // Add "View All Results" link if we have campaigns
                if (hasCampaigns || hasCategories) {
                    const searchQuery = isDesktop ? $('#universalSearchInput').val() : $('#universalSearchInputMobile').val();
                    html += `
                        <div class="search-result-item" style="text-align: center; border-top: 2px solid #e3e6f0; background: #f8f9fc;" onclick="window.location.href='{{ route("user.campaigns") }}?search=' + encodeURIComponent('${searchQuery}')">
                            <div class="search-result-title" style="color: #4e73df;">
                                <i class="fas fa-arrow-right mr-2"></i>View All Results (${data.total || 0})
                            </div>
                        </div>
                    `;
                }
                
                $(resultsContainer).html(html);
                $(dropdown).show();
            }
            
            function hideSearchResults() {
                $('#searchResultsDropdown').hide();
                $('#mobileSearchResults').hide();
            }
            
            // Desktop Search Events
            $('#universalSearchInput').on('input', function() {
                const query = $(this).val().trim();
                performUniversalSearch(query, true);
            });
            
            $('#universalSearchBtn').on('click', function(e) {
                e.preventDefault();
                const query = $('#universalSearchInput').val().trim();
                if (query) {
                    // Redirect to campaigns page with search
                    window.location.href = '{{ route("user.campaigns") }}?search=' + encodeURIComponent(query);
                }
            });
            
            // Mobile Search Events
            $('#universalSearchInputMobile').on('input', function() {
                const query = $(this).val().trim();
                performUniversalSearch(query, false);
            });
            
            $('#universalSearchBtnMobile').on('click', function(e) {
                e.preventDefault();
                const query = $('#universalSearchInputMobile').val().trim();
                if (query) {
                    // Redirect to campaigns page with search
                    window.location.href = '{{ route("user.campaigns") }}?search=' + encodeURIComponent(query);
                }
            });
            
            // Hide search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.navbar-search, .dropdown-menu').length) {
                    hideSearchResults();
                }
            });
            
            // Prevent search dropdown from closing when clicking inside
            $('#searchResultsDropdown, #mobileSearchResults').on('click', function(e) {
                e.stopPropagation();
            });
            
            // Make search functions globally available
            window.performUniversalSearch = performUniversalSearch;
            window.hideSearchResults = hideSearchResults;
        });
    </script>
</body>
</html>
