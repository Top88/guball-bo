<div>
    <!-- NAVBAR: Bootstrap Collapse (Fixed Top) -->
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3 py-2 fixed-top">
            <!-- Brand -->
            <a href="{{ route('index') }}" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('/assets/front/img/logo-guball.png') }}" alt="logo" class="img-fluid" style="height:40px; width:auto; object-fit:contain;">
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </button>

            <!-- Collapsible -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <!-- หน้าแรก -->
                    <li class="nav-item">
                        <a href="{{ route('index') }}" class="nav-link">
                            <i class="fa-solid fa-house"></i> หน้าแรก
                        </a>
                    </li>

                    <!-- ทายผลบอล (Dropdown) -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="predictDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-trophy"></i> ทายผลบอล
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="predictDropdown">
                            <li><a href="{{ route('select-single-game') }}" class="dropdown-item">ทายบอลเต็ง</a></li>
                            <li><a href="{{ route('select-game') }}" class="dropdown-item">ทายบอลสเต็ป</a></li>
                        </ul>
                    </li>

                    <!-- อันดับคะแนน (Dropdown) -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="rankDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ranking-star"></i> อันดับคะแนน
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="rankDropdown">
                            <li><a href="{{ route('prediction-rank-single') }}" class="dropdown-item">อันดับบอลเต็ง</a></li>
                            <li><a href="{{ route('prediction-rank-step') }}" class="dropdown-item">อันดับบอลสเต็ป</a></li>
                        </ul>
                    </li>

                    <!-- ติดต่อเรา -->
                    <li class="nav-item">
                        <a href="https://line.me/R/ti/p/@xux2081v" target="_blank" class="nav-link">
                            <i class="fa-brands fa-line"></i> ติดต่อเรา
                        </a>
                    </li>

                    @auth
                        <!-- เช็คอิน -->
                        <li class="nav-item">
                            <a href="{{ route('check-in') }}" class="nav-link">
                                <i class="fas fa-map-marker-alt"></i> เช็คอิน
                            </a>
                        </li>
                    @endauth

                    @guest
                        {{-- มือถือ: สมัคร + เข้าสู่ระบบ แถวเดียว --}}
                        @if ((Route::has('register') && !Route::is('register')) || (Route::has('login') && !Route::is('login')))
                            <li class="nav-item w-100 d-lg-none mt-2">
                                <div class="d-flex gap-2">
                                    @if (Route::has('register') && !Route::is('register'))
                                        <a href="https://line.me/R/ti/p/@xux2081v" target="_blank"
                                           class="btn btn-secondary rounded-pill text-white py-2 px-3 flex-fill">
                                            <i class="fa fa-user-plus"></i> สมัครสมาชิก
                                        </a>
                                    @endif
                                    @if (Route::has('login') && !Route::is('login'))
                                        <a href="{{ route('login') }}"
                                           class="btn btn-primary rounded-pill text-white py-2 px-3 flex-fill">
                                            <i class="fa fa-user"></i> เข้าสู่ระบบ
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endif

                        {{-- เดสก์ท็อป: ปุ่มแยกตามเดิม --}}
                        @if (Route::has('register') && !Route::is('register'))
                            <li class="nav-item d-none d-lg-block ms-2">
                                <a href="https://line.me/R/ti/p/@xux2081v" target="_blank"
                                   class="btn btn-secondary rounded-pill text-white py-2 px-3">
                                    <i class="fa fa-user-plus"></i> สมัครสมาชิก
                                </a>
                            </li>
                        @endif
                        @if (Route::has('login') && !Route::is('login'))
                            <li class="nav-item d-none d-lg-block ms-2">
                                <a href="{{ route('login') }}"
                                   class="btn btn-primary rounded-pill text-white py-2 px-3">
                                    <i class="fa fa-user"></i> เข้าสู่ระบบ
                                </a>
                            </li>
                        @endif
                    @endguest

                    @auth
                        <!-- โปรไฟล์ผู้ใช้ (Dropdown) -->
                        <li class="nav-item dropdown ms-lg-2 mt-2 mt-lg-0">
                            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-user me-1"></i> ข้อมูลส่วนตัว
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><span class="dropdown-item textred"><i class="fa-solid fa-circle-user me-1"></i> {{ $nickName }}</span></li>
                                <li><span class="dropdown-item textred"><i class="fas fa-coins" style="color: silver"></i> {{ $coins_silver }} เหรียญเงิน</span></li>
                                <li><span class="dropdown-item textred"><i class="fas fa-coins" style="color: goldenrod"></i> {{ $coins_gold }} เหรียญทอง</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="{{ route('point-history') }}" class="dropdown-item"><i class="fas fa-list"></i> ประวัติคะแนน</a></li>
                                <li><a href="{{ route('exchange-credit-history') }}" class="dropdown-item"><i class="fas fa-money-bill"></i> ประวัติแลกเครดิต</a></li>
                                <li><a href="{{ route('change-password') }}" class="dropdown-item"><i class="fas fa-exchange-alt"></i> เปลี่ยนรหัสผ่าน</a></li>
                                @can('access-admin')
                                    <li><a href="{{ route('admin.dashboard') }}" class="dropdown-item"><i class="fas fa-users"></i> ผู้ดูแลระบบ</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="javascript:void(0)" wire:click="logout" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a></li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </div>
</div>

<style>
    /* ชดเชยความสูง navbar fixed-top เพื่อไม่ให้คอนเทนต์โดนทับ */
    body { 
        padding-top: 64px; /* ปรับตามความสูง navbar จริงของคุณ */
    }

    /* ปรับโลโก้ให้สมส่วน */
    .navbar-brand img {
        height: 44px;   /* desktop */
        width: auto;
        object-fit: contain;
    }
    @media (max-width: 991.98px) {
        .navbar-brand img {
            height: 40px; /* mobile/tablet */
        }
    }
</style>
