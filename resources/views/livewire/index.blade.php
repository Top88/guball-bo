@php
    use App\Helper\Number;
@endphp
<div>
    <!-- ทีเด็ด 3 ทีม -->
    <div class="container-fluid about bg-bb py-5">
        <div class="container">
            <img src="assets/front/img/banner1.gif" class="img-fluid rounded w-100 pb-2" alt="Image">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6" data-wow-delay="0.1s">
                    <div class="about-img">
                        <img src="{{ asset('assets/front/img/banner2.gif')}}" class="img-fluid rounded w-100 pb-2" alt="Image">
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6" data-wow-delay="0.3s">
                    @if(($mostPredicTeam[0]['prediction_count'] ?? 0) > 0)
                        <h2><i class="fa-solid fa-trophy"></i> ทีเด็ด 3 ทีม คนทายผลบอลสูงสุด</h2>
                    @endif
                    <div class="row g-4 text-center align-items-center justify-content-center">
                        @if(($mostPredicTeam[0]['prediction_count'] ?? 0) > 0)
                            <div class="col-sm-4">
                                <div class="bg-primary rounded p-4">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="counter-value fs-1 fw-bold text-dark" data-toggle="counter-up">{{$mostPredicTeam[0]['prediction_count'] ?? 0}}</span>
                                        <h4 class="text-dark fs-1 mb-0" style="font-weight: 600; font-size: 25px;">{{Number::shortNumberUnit($mostPredicTeam[0]['prediction_count'] ?? 0)}}</h4>
                                    </div>
                                    <div class="w-100 d-flex align-items-center justify-content-center">
                                        <p class="text-white mb-0">{{$this->mostPredicTeam[0]['name'] ?? 'ไม่มี'}}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(($mostPredicTeam[1]['prediction_count'] ?? 0) > 0)
                            <div class="col-sm-4">
                                <div class="bg-primary rounded p-4">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="counter-value fs-1 fw-bold text-dark" data-toggle="counter-up">{{$mostPredicTeam[1]['prediction_count'] ?? 0}}</span>
                                        <h4 class="text-dark fs-1 mb-0" style="font-weight: 600; font-size: 25px;">{{Number::shortNumberUnit($mostPredicTeam[1]['prediction_count'] ?? 0)}}</h4>
                                    </div>
                                    <div class="w-100 d-flex align-items-center justify-content-center">
                                        <p class="text-white mb-0">{{$this->mostPredicTeam[1]['name'] ?? 'ไม่มี'}}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(($mostPredicTeam[2]['prediction_count'] ?? 0) > 0)
                            <div class="col-sm-4">
                                <div class="bg-primary rounded p-4">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="counter-value fs-1 fw-bold text-dark" data-toggle="counter-up">{{$mostPredicTeam[2]['prediction_count'] ?? 0}}</span>
                                        <h4 class="text-dark fs-1 mb-0" style="font-weight: 600; font-size: 25px;">{{Number::shortNumberUnit($mostPredicTeam[2]['prediction_count'] ?? 0)}}</h4>
                                    </div>
                                    <div class="w-100 d-flex align-items-center justify-content-center">
                                        <p class="text-white mb-0">{{$this->mostPredicTeam[2]['name'] ?? 'ไม่มี'}}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- อันดับทายผล -->
    <div class="container-fluid faq py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-wow-delay="0.1s">
                    <img src="{{ asset('assets/front/img/guball-1.jpg') }}" class="img-fluid rounded w-100 pb-2" alt="Image">
                </div>
                <div class="col-lg-6" data-wow-delay="0.1s">
                    <img src="{{ asset('assets/front/img/guball-2.jpg') }}" class="img-fluid rounded w-100 pb-2" alt="Image">
                </div>
            </div>
            <a href="https://webball.live/" target="_blank"><img src="{{ asset('assets/front/img/banner.gif') }}" class="img-fluid rounded w-100 pb-2" alt="Image"></a>
            <div class="row g-5 align-items-center">
                <div class="col-lg-6" data-wow-delay="0.1s">
                    <h4 class="text-primary"><i class="fa-solid fa-ranking-star"></i> 10 อันดับเซียนทายแม่นประจำสัปดาห์</h4>
                    <table>
                        <thead>
                        <tr>
                            <th scope="col">อันดับ</th>
                            <th scope="col">ชื่อ</th>
                            <th scope="col">ความแม่นยำ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 0; $i < 10; $i++)
                            @if($sianAccurateWeek[$i] ?? null)
                                <tr>
                                    <th scope="row">{{$i + 1}}</th>
                                    <td><a class="text-dark">{{$sianAccurateWeek[$i]?->user->nick_name}}</a></td>
                                    <td>{{number_format($sianAccurateWeek[$i]?->accurate, 2)}}%</td>
                                </tr>
                            @else
                                <tr>
                                    <th scope="row">{{$i + 1}}</th>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endfor
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6" data-wow-delay="0.1s">
                    <h4 class="text-primary"><i class="fa-solid fa-ranking-star"></i> 10 อันดับเซียนทายแม่นประจำเดือน</h4>
                        <table>
                            <thead>
                            <tr>
                                <th scope="col">อันดับ</th>
                                <th scope="col">ชื่อ</th>
                                <th scope="col">ความแม่นยำ</th>
                            </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 10; $i++)
                                    @if($sianAccurateMonth[$i] ?? null)
                                        <tr>
                                            <th scope="row">{{$i + 1}}</th>
                                            <td><a class="text-dark">{{$sianAccurateMonth[$i]?->user->nick_name}}</a></td>
                                            <td>{{number_format($sianAccurateMonth[$i]?->accurate, 2)}}%</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th scope="row">{{$i + 1}}</th>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endfor
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <!-- อันดับทายผล -->

    <!-- กติกา -->
    <div class="container-fluid faq py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div>
                        <!-- <h1 class="display-4">กติกา ทายผลบอล</h1> -->
                    </div>
                <div class="accordion bg-light rounded p-4" id="accordionExample">
                        <div class="accordion-item border-0 mb-4">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button text-dark fs-5 fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseTOne">
                                <i class="fa-solid fa-list"></i>  กติกา ทายผลบอล
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body my-2">
                                    <h6>1. ลูกค้าสามารถเข้าร่วมกิจกรรมได้ฟรี เพียงสมัครเป็นสมาชิกเว็บ GUBET.COM</h6>
                                    <h6>2. ลูกค้าสามารถเลือกทายผลฟุตบอลได้เพียงวันละ 3 คู่ เท่านั้น</h6>
                                    <h6>3. เมื่อลูกค้าเลือกทายผลฟุตบอลครบทั้ง 3 คู่แล้ว ให้ยืนยันด้วยการคลิกปุ่ม "ทายผล" ด้านล่าง</h6>
                                    <h6>4. หากลูกค้าเลือกทายผลผิดคู่หรือผิดฝั่งสามารถแก้ไขได้ด้วยการคลิกปุ่ม "รีเซ็ต"ด้านล่าง</h6>
                                    <h6>5. เมื่อลูกค้าคลิกปุ่มทายผลเป็นที่เรียบร้อยแล้วจะไม่สามารถกลับมาแก้ไขการทายผลได้อีก</h6>
                                    <h6>6. ทางเว็บไซต์กำหนดเกณฑ์การให้คะแนนในการทายผลแต่ละคู่ดังนี้ ชนะเต็ม 100 เหรียญทอง ชนะครึ่ง 50 เหรียญทอง เสมอหรือแพ้ เท่ากับ 0</h6>
                                    <h6>7. ลูกค้าสามารถติดตามผลการทายผลบอลได้ที่ประวัติคะแนน</h6>
                                    <h6>8. ลูกค้าสามารถตรวจสอบอันดับคะแนนได้ที่ปุ่มอันดับคะแนนด้านบนแล้วเลือกเดือนที่ต้องการตรวจสอบ</h6>
                                    <h6>9. หากพบข้อผิดพลาดหรือมีข้อเสนอแนะสามารถติดต่อเจ้าหน้าที่ได้ทาง LINE</h6>

                                </div>
                            </div>
                        </div>
                </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="faq-img RotateMoveRight rounded">
                        <img src="{{ asset('assets/front/img/ball2.png') }}" class="img-fluid rounded w-100" alt="Image">
                        <a class="faq-btn btn btn-primary rounded-pill text-white py-3 px-5" href="https://webball.live/" target="_blank"><i class="fas fa-video"></i> ดูบอลออนไลน์<i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->
</div>
