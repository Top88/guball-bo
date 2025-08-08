<div>
    <!-- About Start -->
    <div class="container-fluid about bg-dark py-5">
        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-9 col-lg-7 col-xl-5">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-5">
                    <h2 class="text-uppercase text-center mb-5">สมัครสมาชิก</h2>

                    <form wire:submit.prevent:="register">
                        @csrf
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label" for="nickName">ชื่อเล่น</label>
                            <input type="text" id="nickName" wire:model="form.nickName" class="form-control form-control-lg @error('form.nickName') is-invalid @enderror" autocomplete="รวย"/>
                            @error('form.nickName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label" for="fullName">ชื่อ-นามสกุล</label>
                            <input type="text" id="fullName" wire:model="form.fullName" class="form-control form-control-lg @error('form.fullName') is-invalid @enderror" autocomplete="รวย สำราญ"/>
                            @error('form.fullName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label" for="phone">เบอร์โทร</label>
                            <input type="tel" id="phone" wire:model.live.debounce.1000ms="form.phone" class="form-control form-control-lg @error('form.phone') is-invalid @enderror" autocomplete="0812345678"/>
                            @error('form.phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label" for="password">รหัสผ่าน</label>
                            <input type="password" id="password" wire:model='form.password' class="form-control form-control-lg @error('form.password') is-invalid @enderror" />
                            @error('form.password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="d-grid gap-2 col-6 mx-auto ">
                            <span wire:loading class="text-center">กำลังบันทึก...</span>
                            <button  type="submit" wire:loading.attr="disabled" class="btn btn-success btn-block btn-lg gradient-custom-4">ลงทะเบียน</button>
                        </div>

                        <p class="text-center text-muted mt-5 mb-0">มีบัญชีอยู่แล้วใช่ไหม?
                            <a href="{{ route('login') }}" class="fw-bold text-body"><u>เข้าสู่ระบบที่นี่</u></a>
                        </p>

                    </form>

                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
</div>
