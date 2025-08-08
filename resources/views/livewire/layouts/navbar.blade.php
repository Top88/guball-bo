<div>
    <!-- navbar -->
    <div class="container-fluid sticky-top px-0">
        <div class="position-absolute bg-dark" style="left: 0; top: 0; width: 100%; height: 100%;">
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-white px-4">
                <a href="{{ route('index') }}" class="navbar-brand p-0">
                    <h1 class="text-primary m-0"><img src="{{asset('/assets/front/img/logo-guball.png')}}" class="img-fluid" alt="logo"></h1>
                    <!-- <h1 class="text-primary m-0"><i class="fas fa-donate me-3"></i>{{ config('settings.web_name') }}</h1> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ route('index') }}" class="nav-item nav-link"><i class="fa-solid fa-house"></i> หน้าแรก</a>
                        <!-- <a href="{{ route('select-game') }}" class="nav-item nav-link"><i class="fa-solid fa-trophy"></i> ทายผลบอล</a> -->
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-trophy"></i> ทายผลบอล
                            </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('select-single-game') }}" class="dropdown-item">ทายบอลเต็ง</a>
                                    <a href="{{ route('select-game') }}" class="dropdown-item">ทายบอลสเต็ป</a>
                                </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ranking-star"></i> อันดับคะแนน
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ route('prediction-rank-single') }}" class="dropdown-item">อันดับบอลเต็ง</a>
                                <a href="{{ route('prediction-rank-step') }}" class="dropdown-item">อันดับบอลสเต็ป</a>
                            </div>
                        </div>
                        <!-- <a href="{{ route('prediction-rank') }}" class="nav-item nav-link"><i class="fa-solid fa-ranking-star"></i> อันดับคะแนน</a> -->
                        <a href="https://line.me/R/ti/p/@xux2081v" target="_blank" class="nav-item nav-link"><i class="fa-brands fa-line"></i> ติดต่อเรา</a>
                        @auth
                        <a href="{{ route('check-in') }}" class="nav-item nav-link"><i class="fas fa-map-marker-alt"></i> เช็คอิน</a>
                        @endauth

                        @guest
                        <div class="contact-info d-md-flex">
                            @if (Route::has('register') && !Route::is('register'))
                            <!-- <a href="{{ route('register') }}" class="btn btn-secondary rounded-pill text-white py-2 px-4 ms-2 flex-wrap flex-sm-shrink-0"><i class="fa fa-user-plus"></i> สมัครสมาชิก</a> -->
                            <a href="https://line.me/R/ti/p/@xux2081v" target="_blank" class="btn btn-secondary rounded-pill text-white py-2 px-4 ms-2 flex-wrap flex-sm-shrink-0"><i class="fa fa-user-plus"></i> สมัครสมาชิก</a>
                            @endif
                            @if (Route::has('login') && !Route::is('login'))
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill text-white py-2 px-4 ms-2 flex-wrap flex-sm-shrink-0" ><i class="fa fa-user"></i> เข้าสู่ระบบ</a>
                            @endif


                        </div>
                        @endguest
                        @auth
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-circle-user"></i> ข้อมูลส่วนตัว</a>
                                <div class="dropdown-menu m-0">
                                    <span class="dropdown-item textred">{{$nickName}}</span>
                                    <a class="dropdown-item textred"><i class="fas fa-coins" style="color: silver"></i> {{ $coins_silver }} เหรียญเงิน</a>
                                    <a class="dropdown-item textred"><i class="fas fa-coins" style="color: goldenrod"></i> {{ $coins_gold }} เหรียญทอง</a>
                                    <!-- <a href="history-user.html" class="dropdown-item"><i class="fas fa-gifts"></i> แลกของรางวัล</a> -->
                                    <a href="{{ route('point-history') }}" class="dropdown-item"><i class="fas fa-list"></i> ประวัติคะแนน</a>
                                    <a href="{{ route('exchange-credit-history') }}" class="dropdown-item"><i class="fas fa-money-bill"></i> ประวัติแลกเครดิต</a>
                                    <a href="{{ route('change-password') }}" class="dropdown-item"><i class="fas fa-exchange-alt"></i> เปลี่ยนรหัสผ่าน</a>

                                    @can('access-admin')
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item"><i class="fas fa-users"></i> ผู้ดูแลระบบ</a>
                                    @endcan
                                    <a href="javascript:void(0)" wire:click='logout' class="dropdown-item"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
                                </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
