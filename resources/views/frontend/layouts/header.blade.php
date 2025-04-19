<header class="header-with-topbar">
            <!-- start header top bar -->
            <!-- <div class="header-top-bar top-bar-dark bg-gradient-flamingo-amethyst-green disable-fixed">
                <div class="container-fluid">
                    <div class="row h-42px align-items-center m-0">
                        <div class="col-12 text-center justify-content-center">
                            <div class="fs-14 text-white">Provide data analytics solutions for startup business enterprises. <a href="#" class="btn btn-link-gradient btn-small text-white thin">Explore services<i class="feather icon-feather-arrow-right"></i><span class="bg-white opacity-4"></span></a></div>
                        </div> 
                    </div>
                </div>
            </div> -->
            <!-- end header top bar -->
            <!-- start navigation -->
            <nav class="navbar navbar-expand-lg header-light bg-transparent disable-fixed border-bottom border-color-transparent-dark-very-light">
                <div class="container-fluid">
                    <div class="col-auto">
                        <a class="navbar-brand" href="demo-data-analysis.html">
                            <img src="{{ asset('frontend/images/demo-data-analysis-logo-black.png') }}" data-at2x="{{ asset('frontend/images/demo-data-analysis-logo-black@2x.png') }}" alt="" class="default-logo">
                            <img src="{{ asset('frontend/images/demo-data-analysis-logo-black.png') }}" data-at2x="{{ asset('frontend/images/demo-data-analysis-logo-black@2x.png') }}" alt="" class="alt-logo">
                            <img src="{{ asset('frontend/images/demo-data-analysis-logo-black.png') }}" data-at2x="{{ asset('frontend/images/demo-data-analysis-logo-black@2x.png') }}" alt="" class="mobile-logo"> 
                        </a>
                    </div>
                    <div class="col-auto menu-order left-nav">
                        <button class="navbar-toggler float-start" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                            <span class="navbar-toggler-line"></span>
                            <span class="navbar-toggler-line"></span>
                            <span class="navbar-toggler-line"></span>
                            <span class="navbar-toggler-line"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav"> 
                            <ul class="navbar-nav alt-font"> 
                                <li class="nav-item"><a href="{{route('front.home')}}" class="nav-link">Trang chủ</a></li>
                                <li class="nav-item"><a href="{{route('front.song')}}" class="nav-link">Bài hát</a></li>
                                <li class="nav-item"><a href="{{route('front.cate')}}" class="nav-link">Thể loại</a></li>
                                <li class="nav-item"><a href="{{route('front.event')}}" class="nav-link">Sự kiện</a></li>
                                <li class="nav-item"><a href="{{route('front.singer')}}" class="nav-link">Nghệ sĩ</a></li>
                                <li class="nav-item"><a href="{{route('front.blog')}}" class="nav-link">Blog</a></li>
                                <li class="nav-item"><a href="demo-data-analysis-contact.html" class="nav-link">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-auto ms-auto d-none d-sm-flex">
                        <div class="header-icon">
                            <div class="header-button ms-25px">
                            @if(Auth::check())
                            <div class="dropdown">
                                <a href="#" class="btn btn-small btn-round-edge btn-box-shadow fw-700 ls-0px btn-icon-left dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ Auth::user()->photo ?? asset('backend/images/default-avatar.png') }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">  
                                    {{ Auth::user()->full_name }}
                                </a>
                                
                                <!-- Dropdown Menu -->
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('front.profile') }}">Hồ sơ cá nhân</a></li>
                                    <li>
                                        <form action="{{ route('admin.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @else
                                <a href="{{ route('admin.login') }}" class="btn btn-small btn-hover-animation-switch btn-round-edge btn-box-shadow fw-700 ls-0px btn-icon-left">
                                    <i data-lucide="user"></i> Đăng nhập
                                </a>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- end navigation -->
        </header>