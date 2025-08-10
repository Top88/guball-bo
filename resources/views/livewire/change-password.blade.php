<div>
    <!-- ทายผลบอล โชว์ 5 แถว-->
    <div class="container-fluid about py-5">
        <div class="container col-lg-8 col-xs-12">
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1>เปลี่ยนรหัสผ่าน</h1>

                <div class="col-4 mx-auto">
                    <div class=" mb-3">
                        <input type="password" class="form-control" wire:model="oldPassword" placeholder="รหัสผ่านเก่า">
                        @error('oldPassword') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" wire:model="newPassword" placeholder="รหัสผ่านใหม่">
                        @error('newPassword') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" wire:model="confirmPassword" placeholder="ยืนยันรหัสผ่านใหม่">
                        @error('confirmPassword') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="button" class="btn btn-danger" wire:click="resetField">รีเซ็ต</button>
                <button type="button" class="btn btn-success" wire:click="submit">ยืนยัน</button>
            </div>

        </div>
    </div>
</div>
