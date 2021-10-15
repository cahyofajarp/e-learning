<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Adminmart Template - The Ultimate Multipurpose admin template</title>
    <!-- Custom CSS -->
    <link href="{{ asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    @stack('add-style')
</head>
<style>
    *{    font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
}
    .btn-custom-purple{
         background:linear-gradient(to right,#8971ea,#7f72ea,#7574ea,#6a75e9,#5f76e8);
        color:white;
    }
    
    .btn-custom-green-blue{
        background:linear-gradient(to right,#348F50 ,#56B4D3 );
        color:white;
    }
    
    .btn-custom-green{
        background:linear-gradient(to right,#8bc34a ,#7ecb5aeb );
        color:white;
    }
    .btn-custom-yellow{
        background: linear-gradient(to right,orange,#fce043);
        color: white;
    }
    .btn-custom-blue{
        background: linear-gradient(to right,#2193b0,#6dd5ed);
        color:white;
    }
    .btn-custom-red{
        background: linear-gradient(to right,#cb2d3e,#ef473a);
        color:white;
    }
    .btn-custom-purple:hover,.btn-custom-green:hover,
    .btn-custom-yellow:hover,.btn-custom-blue:hover,
    .btn-custom-red:hover{
        color:#fefefe;
        box-shadow: 0 7px 12px 0 rgb(95 118 232 / 21%);
        transition:all 1s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    </style>
<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="app">
            
            <header class="topbar" data-navbarbg="skin6">
                <nav class="navbar top-navbar navbar-expand-md">
                    <div class="navbar-header" data-logobg="skin6">
                        <!-- This is for the sidebar toggle which is visible on mobile only -->
                        <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                                class="ti-menu ti-close"></i></a>
                        <!-- ============================================================== -->
                        <!-- Logo -->
                        <!-- ============================================================== -->
                        
                        <div class="navbar-brand">
                            <!-- Logo icon -->
                            <a href="index.html">
                                <b class="logo-icon">
                                    <h3 class="dark-logo" style="color:black;font-weight:100"><b>E L</b></h3>
                                </b>
                                <!--End Logo icon -->
                                <!-- Logo text -->
                                <span class="logo-text">
                                    <h3 class="dark-logo" style="color:black;font-weight:100">EARNING</h3>
                                </span>
                            </a>
                        </div>
                        <!-- ============================================================== -->
                        <!-- End Logo -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Toggle which is visible on mobile only -->
                        <!-- ============================================================== -->
                        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                            data-toggle="collapse" data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                                class="ti-more"></i></a>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-collapse collapse" id="navbarSupportedContent">
                        <!-- ============================================================== -->
                        <!-- toggle and nav items -->
                        <!-- ============================================================== -->
                        <Navbar :readnotifications="{{ auth()->user()->notifications }}" :unreads="{{ auth()->user()->unreadNotifications->sortByDesc('created_at') }}" :userid="{{ auth()->user()->id }}"></Navbar>
                        <!-- ============================================================== -->
                        <!-- Right side toggle and nav items -->
                        <!-- ============================================================== -->
                        <ul class="navbar-nav float-right">
                            <!-- ============================================================== -->
                            <!-- Search -->
                            <!-- ============================================================== -->
                            <li class="nav-item d-none d-md-block">
                                <a class="nav-link" href="javascript:void(0)">
                                    <form>
                                        <div class="customize-input">
                                            <input class="form-control custom-shadow custom-radius border-0 bg-white"
                                                type="search" placeholder="Search" aria-label="Search">
                                            <i class="form-control-icon" data-feather="search"></i>
                                        </div>
                                    </form>
                                </a>
                            </li>
                            <!-- ============================================================== -->
                            <!-- User profile and search -->
                            <!-- ============================================================== -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('assets/images/users/profile-pic.jpg') }}" alt="user" class="rounded-circle"
                                        width="40">
                                    <span class="ml-2 d-none d-lg-inline-block"><span>Hello,</span> <span
                                            class="text-dark">{{ auth()->user()->name }}</span> <i data-feather="chevron-down"
                                            class="svg-icon"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                    <a class="dropdown-item" href="javascript:void(0)"><i data-feather="user"
                                            class="svg-icon mr-2 ml-1"></i>
                                        My Profile</a>
                                    <a class="dropdown-item" href="javascript:void(0)"><i data-feather="credit-card"
                                            class="svg-icon mr-2 ml-1"></i>
                                        My Balance</a>
                                    <a class="dropdown-item" href="javascript:void(0)"><i data-feather="mail"
                                            class="svg-icon mr-2 ml-1"></i>
                                        Inbox</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0)"><i data-feather="settings"
                                            class="svg-icon mr-2 ml-1"></i>
                                        Account Setting</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                  document.getElementById('logout-form').submit();" class="dropdown-item" href="javascript:void(0)"><i data-feather="power"
                                            class="svg-icon mr-2 ml-1"></i>
                                        Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    <div class="dropdown-divider"></div>
                                    <div class="pl-4 p-3"><a href="javascript:void(0)" class="btn btn-sm btn-info">View
                                            Profile</a></div>
                                </div>
                            </li>
                            <!-- ============================================================== -->
                            <!-- User profile and search -->
                            <!-- ============================================================== -->
                        </ul>
                    </div>
                </nav>
            </header>
        </div>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                       @if (auth()->user()->roles == 'admin')
                       <li class="sidebar-item"> 
                         <a class="sidebar-link sidebar-link" href="{{ route('admin.home') }}" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a></li>
                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">Data Users</span></li>

                    
                    <li class="sidebar-item"> 
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span class="hide-menu">Users </span></a>
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item"><a href="{{ route('admin.student.index') }}" class="sidebar-link"><span
                                            class="hide-menu"> Student
                                        </span></a>
                                </li>
                                <li class="sidebar-item"><a href="{{ route('admin.teacher.index') }}" class="sidebar-link"><span
                                            class="hide-menu"> Teacher
                                        </span></a>
                                </li>
                                </li>
                            </ul>
                    </li>

                    <li class="list-divider"></li>
                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ route('admin.levelclass.index') }}"
                        aria-expanded="false"><i data-feather="layers" class="feather-icon"></i><span
                            class="hide-menu">Levelclass</span></a></li>
                    <li class="list-divider"></li>
                        
                    <li class="sidebar-item"> 
                        <a class="sidebar-link sidebar-link"
                         href="{{ route('admin.classroom.index') }}"
                        aria-expanded="false"><i data-feather="home" 
                        class="feather-icon"></i>
                            <span class="hide-menu">Classroom</span>
                        </a>
                    </li>

                    <li class="list-divider"></li>
                    <li class="sidebar-item"> 
                        <a class="sidebar-link sidebar-link"
                         href="{{ route('admin.lesson.index') }}"
                        aria-expanded="false"><i data-feather="book" 
                        class="feather-icon"></i>
                            <span class="hide-menu">Lesson</span>
                        </a>
                    </li>
                    <li class="list-divider"></li>
                    <li class="sidebar-item"> 
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false"><i data-feather="bar-chart" class="feather-icon"></i><span class="hide-menu">Learning Management </span></a>
                        <ul aria-expanded="false" class="collapse  first-level base-level-line">
                            <li class="sidebar-item">
                                <a href="{{ route('admin.lessonTeacher.index') }}" class="sidebar-link"><span class="hide-menu"> Lesson Teacher </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('admin.test.index') }}" class="sidebar-link"><span class="hide-menu"> Test Management </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('admin.studentWork.classroom') }}" class="sidebar-link"><span class="hide-menu"> Student Work </span></a>
                            </li>
                            </li>
                        </ul>
                    </li>
                       @elseif(auth()->user()->roles == 'guru')
                        
                       <li class="sidebar-item {{ Request::is('guru/work/data-siswa/*') ||  Request::is('guru/test/data-siswa/*') ? 'selected' : ''}}"> 
                            <a class="sidebar-link sidebar-link {{ Request::is('guru/work/data-siswa/*') ||  Request::is('guru/test/data-siswa/*') ? 'active'  : ''}}" href="{{ route('teacher.home') }}" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a>
                        </li>
                        <li class="list-divider"></li>
                        
                        <li class="sidebar-item {{ Request::is('guru/student-management/*') ? 'selected' : ''}}"> 
                            <a class="sidebar-link sidebar-link {{ Request::is('guru/student-management/*') ? 'active'  : ''}}"  href="{{ route('teacher.student') }}" aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span class="hide-menu">Student Management</span></a>
                        </li>
                        <li class="list-divider"></li>
                        <li class="sidebar-item {{ Request::is('guru/test-management/*') ? 'selected' : ''}}"> 
                            <a class="sidebar-link sidebar-link {{ Request::is('guru/test-management/*') ? 'active'  : ''}}" href="{{ route('teacher.test.classroom') }}" aria-expanded="false"><i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu">Test Management</span></a>
                        </li>
                        <li class="list-divider"></li>
                          <li class="sidebar-item {{ Request::is('guru/work/student/*') ? 'selected' : ''}}"> 
                            <a class="sidebar-link sidebar-link {{ Request::is('guru/work/student/*') ? 'active'  : ''}}" href="{{ route('teacher.work.student.classroom') }}" aria-expanded="false"><i data-feather="book" class="feather-icon"></i><span class="hide-menu">Work Management</span></a>
                        </li>
                        {{-- <li class="sidebar-item" {{ Request::is('guru/test-management/*') ? 'selected' : ''}}> 
                            <a class="sidebar-link sidebar-link {{ Request::is('guru/test-management/*') ? 'active'  : ''}}" href="{{ route('teacher.work.student.classroom') }}" aria-expanded="false"><i data-feather="book" class="feather-icon"></i><span class="hide-menu">Work Management</span></a>
                        </li> --}}
                        <li class="list-divider"></li>
                        
                        @elseif (auth()->user()->roles == 'siswa')
                            
                        <li class="sidebar-item"> 
                            <a class="sidebar-link sidebar-link" href="{{ route('student.home') }}" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a>
                        </li>
                        <li class="list-divider"></li>
                    
                        <li class="nav-small-cap"><span class="hide-menu">Learning</span></li>

                    
                        <li class="sidebar-item"> 
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false"><i data-feather="book" class="feather-icon"></i><span class="hide-menu">Learning </span></a>
                                <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                    <li class="sidebar-item"><a href="{{ route('student.material.lesson') }}" class="sidebar-link"><span
                                        class="hide-menu"> Materi
                                        </span></a>
                                    </li>
                                    <li class="sidebar-item"><a href="{{ route('student.work.lesson') }}" class="sidebar-link"><span
                                                class="hide-menu"> Tugas
                                            </span></a>
                                    </li>
                                    <li class="sidebar-item"><a href="{{ route('student.test.lesson') }}" class="sidebar-link"><span
                                                class="hide-menu"> Test
                                            </span></a>
                                    </li>
                                </ul>
                        </li>
                       @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb {{ Request::is('admin/home*') ||  Request::is('guru/home*')  ||  Request::is('siswa/home*')  ? 'd-block' : 'd-none' }} ">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                            
                        @php
                            
                            $greetings = "";
                            /* This sets the $time variable to the current hour in the 24 hour clock format */
                            $time = date("H");

                            /* Set the $timezone variable to become the current timezone */
                            $timezone = date("in");

                            /* If the time is less than 1200 hours, show good morning */
                            if ($time < "12") {
                                $greetings = "Good Morning";
                            } else

                            /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
                            if ($time >= "12" && $time < "17") {
                                $greetings = "Good Afternoon";
                            } else

                            /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
                            if ($time >= "17" && $time < "19") {
                                $greetings = "Good Evening";
                            } else

                            /* Finally, show good night if the time is greater than or equal to 1900 hours */
                            if ($time >= "19") {
                                $greetings = "Good Night";
                            }
                        @endphp
                        {{ $greetings }} {{ auth()->user()->name }}!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                @yield('content')
            </div>
            <footer class="footer text-center text-muted">
                All Rights Reserved by Adminmart. Designed and Developed by <a
                    href="https://wrappixel.com">WrapPixel</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->

      
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    
    <!-- apps -->
    <!-- apps -->
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!--This page JavaScript -->

    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
 
    @stack('add-script')
</body>

</html>