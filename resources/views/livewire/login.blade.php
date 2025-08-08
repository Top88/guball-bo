<div>
    <!-- About Start -->
    <div class="container-fluid about py-5">
        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-5">
                    <div class="wrapper">
                        <div class="logo">
                            <img src="{{ asset('assets/front/img/ball.png') }}" alt="">
                        </div>
                        <div class="text-center mt-4 name">
                            {{ config('settings.web_name') }}
                        </div>
                        <form class="p-3 mt-3" wire:submit.prevent="login">
                            @csrf
                            <div class="form-field d-flex align-items-center">
                                <span class="far fa-user"></span>
                                <input type="text" wire:model.defer='form.phone' id="phone" placeholder="เบอร์โทร" autocomplete="0812345678">
                            </div>

                            <div class="form-field d-flex align-items-center">
                                <span class="fas fa-key"></span>
                                <input type="password" wire:model.defer='form.password' id="password" placeholder="รหัสผ่าน" >
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li class="list-unstyled">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                            @endif
                            <button type="submit" class="btn mt-3">เข้าสู่ระบบ</button>
                        </form>
                        <div class="text-center fs-6">
                            <a href="https://line.me/R/ti/p/@xux2081v" target="_blank">ลืมรหัสผ่าน?</a> or <a href="https://line.me/R/ti/p/@xux2081v" target="_blank">สมัครสมาชิก</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
</div>
