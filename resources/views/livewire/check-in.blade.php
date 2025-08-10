@php
    use Carbon\Carbon;
    use App\Helper\ThaiDate
@endphp
<div>
    <div class="container-fluid about py-5">
        <div class="container col-lg-5 col-xs-12">
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1>เช็คอินรับเหรียญเงิน (Silver)</h1>
                <h4 class="text-primary"><i class="fas fa-coins" style="color: silver"></i> : {{$userCoinsSilver}}</h4>
                @if (false === array_search(Carbon::now()->toDateString(), $usercheckedIn) && Carbon::now()->between($startCheckInDate, $endCheckInDate))
                    <button type="button" class="btn btn-lg btn-success" wire:click="checkIn()"><i class="fas fa-coins"></i> เช็คอินรับเหรียญ</button
                @elseif (Carbon::now()->lessThan($startCheckInDate))
                    <span>เริ่ม Check-in ได้ตั้งแต่วันที่ {{ThaiDate::toDateTime($startCheckInDate)}} - {{ThaiDate::toDateTime($endCheckInDate->clone()->subDay())}}</span>
                @endif
            </div>
            
            <div class="row mt-5">
                @foreach ($periodList as $key => $item)
                    <div class="column">
                        
                        @if (false !== array_search($item, $usercheckedIn))
                        <img src="{{asset('assets/front/img/coin-b.png')}}" class="img-ball" alt="selected coin">
                        @else
                        <img src="{{asset('assets/front/img/coin.png')}}" class="img-ball" alt="unselected coin">
                        @endif
                        
                        <p>วันที่ {{$key+1}}</p>
                    </div>  
                @endforeach
            </div>
        </div>
    </div>
    <div wire:teleport="body">
        <div wire:ignore.self id="dialog-container" class= "dialog-container">
            <div class= "dialog-header">
                @if ($dialogSuccess)
                <i class='fas fa-check-circle' style='font-size:48px;color:#47ed00'></i>
                @else
                <i class='fas fa-times-circle' style='font-size:48px;color:red'></i>   
                @endif
                <p>{{$dialogHeaderText}}</p>
            </div>
            <div id= "dialog-body" class= "dialog-body"></div>
            <div class= "dialog-footer">
                <a onclick= "closeModal()">ตกลง</a>
            </div>
        </div>
    </div>

    <!-- เงื่อนไขเหรียญเงิน -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div>
                    </div>
                <div class="accordion bg-light rounded p-4" id="accordionExample">
                        <div class="accordion-item border-0 mb-4">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button text-dark fs-5 fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseTOne">
                                <i class="fas fa-coins" style="color: silver"></i> : เงื่อนไขการรับเหรียญเงิน (Silver)
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body my-2">
                                    <h6>- สามารถรับได้จากการเช็คอินทุกวัน วันละ 100 เหรียญ และรับจากการเติมเครดิตสูงสุด 1,000 เหรียญ และจะรีเซ็ตเหรียญเงินทุกวันอังคาร</h6>
                                    <h6>- สามารถนำไปใช้ดูทีเด็ดของเซียนอันดับ 1 2 และ 3 ได้ โดยอันดับ 1 จะใช้ 30 เหรียญเงิน, อันดับ 2 จะใช้ 20 เหรียญเงิน, อันดับ 3 จะใช้ 10 เหรียญเงิน อันดับที่ 4 ขึ้นไปดูฟรี</h6>
                                </div>
                            </div>
                        </div>
                </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                <div class="accordion bg-light rounded p-4" id="accordionExample">
                        <div class="accordion-item border-0 mb-4">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button text-dark fs-5 fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseTOne">
                                <i class="fas fa-coins" style="color: goldenrod"></i> : เงื่อนไขการรับเหรียญทอง (Gold)
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body my-2">
                                    <h6>- สามารถรับได้จากการทาบผลบอลถูก หากทายผลชนะได้รับ 100 เหรียญ, ชนะครึ่ง 50 เหรียญ, เสมอหรือแพ้ได้รับ 0 เหรียญ</h6>
                                    <h6>- สามารถนำไปแลกเป็นเครดิตเข้าเล่นของเว็บ GUBET ได้ โดยที่ 2,000 เหรียญทอง จะแลกเครดิตได้ 100 บาท</h6>
                                </div>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        window.addEventListener('check-in-success', event => {
            this.showModal();
        });
        window.addEventListener('check-in-fail', event => {
            this.showModal();
        });
        
        window.addEventListener('close-modal', event => {
            this.closeModal();
        });
    });
    function showModal(msg = "Error")
    {
        const container = document.getElementById('dialog-container');
        container.style.top = '30%';
        container.style.opacity = 1;
    }

    function closeModal()
    {
        const container = document.getElementById('dialog-container');
        container.style.top = '-30%';
        container.style.opacity = 0;
    }
</script>
@endpush
@push('styles')

@endpush