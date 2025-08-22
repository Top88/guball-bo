@php
  use Illuminate\Support\Facades\Auth;
  $authUser   = Auth::user();
  $authNick   = $authUser->nick_name ?? '';
  $authSilver = $authUser->coins_silver ?? 0;
  $authGold   = $authUser->coins_gold ?? 0;

  $isRankActive    = Route::is('prediction-rank-single') || Route::is('prediction-rank-step');
  $isPredictActive = Route::is('select-single-game')     || Route::is('select-game');
  $isProfileActive = Route::is('point-history') || Route::is('exchange-credit-history') || Route::is('change-password');

  // กันเคสโปรเจกต์ไม่มีการประกาศ route('logout')
  $logoutAction = Route::has('logout') ? route('logout') : url('/logout');
@endphp

<div>
  <!-- ===== DESKTOP/TABLET TOP NAVBAR ===== -->
  <div class="container-fluid px-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3 py-2 fixed-top">
      <!-- Brand -->
      <a href="{{ route('index') }}" class="navbar-brand d-flex align-items-center">
        <img src="{{ asset('/assets/front/img/logo-guball.png') }}" alt="logo" class="img-fluid">
      </a>

      <!-- Toggler -->
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
              aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
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

          <!-- ทายผลบอล -->
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="predictDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-trophy"></i> ทายผลบอล
            </a>
            <ul class="dropdown-menu" aria-labelledby="predictDropdown">
              <li><a href="{{ route('select-single-game') }}" class="dropdown-item">ทายบอลเต็ง</a></li>
              <li><a href="{{ route('select-game') }}" class="dropdown-item">ทายบอลสเต็ป</a></li>
            </ul>
          </li>

          <!-- อันดับคะแนน -->
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="rankDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-ranking-star"></i> อันดับคะแนน
            </a>
            <ul class="dropdown-menu" aria-labelledby="rankDropdown">
              <li><a href="{{ route('prediction-rank-single') }}" class="dropdown-item">อันดับบอลเต็ง</a></li>
              <li><a href="{{ route('prediction-rank-step') }}" class="dropdown-item">อันดับบอลสเต็ป</a></li>
            </ul>
          </li>
            <li class="nav-item">
                <a href="{{ route('match-results') }}" class="nav-link">
                    <i class="fa-solid fa-futbol"></i> ผลการแข่งขัน
                </a>
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
                    <a href="https://line.me/R/ti/p/@xux2081v" target="_blank" class="btn btn-secondary rounded-pill text-white py-2 px-3 flex-fill">
                      <i class="fa fa-user-plus"></i> สมัครสมาชิก
                    </a>
                  @endif
                  @if (Route::has('login') && !Route::is('login'))
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill text-white py-2 px-3 flex-fill">
                      <i class="fa fa-user"></i> เข้าสู่ระบบ
                    </a>
                  @endif
                </div>
              </li>
            @endif

            {{-- เดสก์ท็อป: ปุ่มแยกตามเดิม --}}
            @if (Route::has('register') && !Route::is('register'))
              <li class="nav-item d-none d-lg-block ms-2">
                <a href="https://line.me/R/ti/p/@xux2081v" target="_blank" class="btn btn-secondary rounded-pill text-white py-2 px-3">
                  <i class="fa fa-user-plus"></i> สมัครสมาชิก
                </a>
              </li>
            @endif
            @if (Route::has('login') && !Route::is('login'))
              <li class="nav-item d-none d-lg-block ms-2">
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill text-white py-2 px-3">
                  <i class="fa fa-user"></i> เข้าสู่ระบบ
                </a>
              </li>
            @endif
          @endguest

          @auth
            <!-- โปรไฟล์ผู้ใช้ -->
            <li class="nav-item dropdown ms-lg-2 mt-2 mt-lg-0">
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-circle-user me-1"></i> ข้อมูลส่วนตัว
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><span class="dropdown-item textred"><i class="fa-solid fa-circle-user me-1"></i> {{ $authNick }}</span></li>
                <li><span class="dropdown-item textred"><i class="fas fa-coins" style="color:silver"></i> {{ $authSilver }} เหรียญเงิน</span></li>
                <li><span class="dropdown-item textred"><i class="fas fa-coins" style="color:goldenrod"></i> {{ $authGold }} เหรียญทอง</span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="{{ route('point-history') }}" class="dropdown-item"><i class="fas fa-list"></i> ประวัติคะแนน</a></li>
                <li><a href="{{ route('exchange-credit-history') }}" class="dropdown-item"><i class="fas fa-money-bill"></i> ประวัติแลกเครดิต</a></li>
                <li><a href="{{ route('change-password') }}" class="dropdown-item"><i class="fas fa-exchange-alt"></i> เปลี่ยนรหัสผ่าน</a></li>
                @can('access-admin')
                  <li><a href="{{ route('admin.dashboard') }}" class="dropdown-item"><i class="fas fa-users"></i> ผู้ดูแลระบบ</a></li>
                @endcan
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ $logoutAction }}">
                    @csrf
                    <button type="submit"
                            class="dropdown-item text-danger d-flex align-items-center gap-2">
                      <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                    </button>
                  </form>
                </li>
              </ul>
            </li>
          @endauth
        </ul>
      </div>
    </nav>
  </div>
</div>

{{-- ===== MOBILE BOTTOM NAVBAR ===== --}}
<div class="mobile-dock-wrap d-lg-none">
  <nav class="mobile-dock">
    @guest
      <a href="{{ route('index') }}" class="dock-item {{ Route::is('index') ? 'active' : '' }}">
        <i class="fa-solid fa-house dock-icon"></i><span class="dock-label">หน้าหลัก</span>
      </a>
      <a href="https://line.me/R/ti/p/@xux2081v" target="_blank" class="dock-item">
        <i class="fa-solid fa-user-plus dock-icon"></i><span class="dock-label">สมัครสมาชิก</span>
      </a>
      <a href="{{ route('select-game') }}" class="dock-item {{ $isPredictActive ? 'active' : '' }}">
        <i class="fa-solid fa-trophy dock-icon"></i><span class="dock-label">ทายผลบอล</span>
      </a>
      <a href="https://line.me/R/ti/p/@xux2081v" target="_blank" class="dock-item">
        <i class="fa-brands fa-line dock-icon"></i><span class="dock-label">ติดต่อเรา</span>
      </a>
      <a href="{{ route('login') }}" class="dock-item {{ Route::is('login') ? 'active' : '' }}">
        <i class="fa-solid fa-right-to-bracket dock-icon"></i><span class="dock-label">เข้าสู่ระบบ</span>
      </a>
    @endguest

    @auth
      <a href="{{ route('index') }}" class="dock-item {{ Route::is('index') ? 'active' : '' }}">
        <i class="fa-solid fa-house dock-icon"></i><span class="dock-label">หน้าหลัก</span>
      </a>

      <div class="dropup">
        <button class="dock-item dock-btn dropdown-toggle {{ $isRankActive ? 'active' : '' }}" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
          <i class="fa-solid fa-ranking-star dock-icon"></i><span class="dock-label">อันดับคะแนน</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark dock-dropdown">
          <li><a class="dropdown-item" href="{{ route('prediction-rank-single') }}">บอลเต็ง</a></li>
          <li><a class="dropdown-item" href="{{ route('prediction-rank-step') }}">บอลสเต็ป</a></li>
        </ul>
      </div>

      <div class="dropup">
        <button class="dock-item dock-btn dropdown-toggle {{ $isPredictActive ? 'active' : '' }}" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
          <i class="fa-solid fa-trophy dock-icon"></i><span class="dock-label">ทายผลบอล</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark dock-dropdown">
          <li><a class="dropdown-item" href="{{ route('select-single-game') }}">บอลเต็ง</a></li>
          <li><a class="dropdown-item" href="{{ route('select-game') }}">บอลสเต็ป</a></li>
        </ul>
      </div>

      <a href="{{ route('check-in') }}" class="dock-item {{ Request::is('checkin*') ? 'active' : '' }}">
        <i class="fa-solid fa-calendar-check dock-icon"></i><span class="dock-label">เช็คอิน</span>
      </a>
      

      <div class="dropup">
        <button class="dock-item dock-btn dropdown-toggle {{ $isProfileActive ? 'active' : '' }}" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
          <i class="fa-solid fa-circle-user dock-icon"></i><span class="dock-label">ข้อมูลส่วนตัว</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark dock-dropdown">
          <li>
            <span class="dropdown-item d-flex justify-content-between align-items-center">
              <span><i class="fas fa-coins me-2" style="color:silver"></i>เหรียญเงิน</span><strong>{{ $authSilver }}</strong>
            </span>
          </li>
          <li>
            <span class="dropdown-item d-flex justify-content-between align-items-center">
              <span><i class="fas fa-coins me-2" style="color:goldenrod"></i>เหรียญทอง</span><strong>{{ $authGold }}</strong>
            </span>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="{{ route('point-history') }}"><i class="fas fa-list me-2"></i>ประวัติคะแนน</a></li>
          <li><a class="dropdown-item" href="{{ route('exchange-credit-history') }}"><i class="fas fa-money-bill me-2"></i>ประวัติแลกเครดิต</a></li>
          <li><a class="dropdown-item" href="{{ route('change-password') }}"><i class="fas fa-exchange-alt me-2"></i>เปลี่ยนรหัสผ่าน</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ $logoutAction }}">
              @csrf
              <button type="submit"
                      class="dropdown-item text-danger d-flex align-items-center gap-2">
                <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
              </button>
            </form>
          </li>
        </ul>
      </div>
    @endauth
  </nav>
</div>

<style>
/* ===== Top navbar fix spacing & logo ===== */
body{ padding-top:64px; } /* ปรับตามความสูง fixed-top ของคุณ */
.navbar-brand img{ height:44px; width:auto; object-fit:contain; }
@media (max-width:991.98px){ .navbar-brand img{ height:40px; } }

/* ===== Mobile dock ===== */
@media (max-width:991.98px){
  body{ padding-bottom:96px; } /* กันคอนเทนต์ทับแถบล่าง */

  .mobile-dock-wrap{
    position:fixed; left:50%; bottom:max(12px, env(safe-area-inset-bottom));
    transform:translateX(-50%); width:min(560px, calc(100% - 24px)); z-index:1030;
  }
  .mobile-dock{
    display:grid; grid-template-columns:repeat(5,1fr);
    gap:10px; padding:12px 14px; border-radius:20px;
    background: linear-gradient(180deg, #ff5e01 0%, #e34f02 100%);
    box-shadow:0 10px 22px rgba(0,0,0,.28), inset 0 1px 0 rgba(255,255,255,.18);
    position:relative; overflow:visible;
    bottom: -8px !important;
  }
  .dock-item{
    width:100%; border:0; background:transparent;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    text-decoration:none; color:#eee; font-size:12px; line-height:1.1;
    border-radius:12px; padding:4px 0; transition:color .15s ease, transform .1s ease;
  }
  .dock-btn{ width:100%; }
  .dock-icon{ font-size:22px; margin-bottom:6px; filter:drop-shadow(0 1px 0 rgba(0,0,0,.25)); }
  .dock-label{ color:#eaeaea; white-space:nowrap; }
  .dock-item:hover{ color:#ffd95b; transform:translateY(-1px); }
  .dock-item:hover .dock-label{ color:#ffd95b; }
  .dock-item.active .dock-label{ background:#ffd95b; color:#5b4500; padding:3px 8px; border-radius:9999px; font-weight:600; }

  /* Dropup menu */
  .dropup{ position:relative; }
  .dropup .dropdown-menu{
    bottom:calc(100% + 10px); left:50%; transform:translateX(-50%);
    right:auto; margin:0; padding:.5rem 0; border-radius:12px;
    min-width:220px; max-width:calc(100vw - 32px);
    white-space:normal; word-break:break-word;
    box-shadow:0 8px 24px rgba(0,0,0,.28); z-index:1055;
  }
  .dropup .dropdown-menu::after{
    content:""; position:absolute; left:50%; bottom:-6px; transform:translateX(-50%) rotate(45deg);
    width:12px; height:12px; background:#343a40; box-shadow:1px 1px 2px rgba(0,0,0,.2);
  }
  .dock-dropdown .dropdown-item{
    display:flex; align-items:center; gap:.6rem;
    padding:.7rem 1rem; font-size:15px; line-height:1.25; white-space:normal;
  }
  .dock-dropdown .dropdown-item i{ width:18px; flex:0 0 18px; text-align:center; }

  /* เคสข้อความบรรทัดยาว + ค่าด้านขวา (เช่น เหรียญ) */
  .dock-dropdown .dropdown-item.d-flex{ flex-wrap:wrap; row-gap:.25rem; }
  .dock-dropdown .dropdown-item.d-flex strong{ margin-left:auto; }

  /* ปุ่มริมซ้าย/ขวา ป้องกันล้นจอ */
  .mobile-dock .dropup:first-child .dropdown-menu{ left:0; right:auto; transform:none; }
  .mobile-dock .dropup:first-child .dropdown-menu::after{ left:22px; transform:rotate(45deg); }
  .mobile-dock .dropup:last-child  .dropdown-menu{ right:0; left:auto; transform:none; }
  .mobile-dock .dropup:last-child  .dropdown-menu::after{ left:auto; right:22px; transform:rotate(45deg); }
}
</style>
