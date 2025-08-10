@php
    use App\Helper\Number;
@endphp
<div>
    <!-- ทีเด็ด 3 ทีม -->
    <div class="container-fluid about bg-bb py-5">
        <div class="container">
            <img src="assets/front/img/banner1.gif" class="img-fluid rounded w-100 pb-2" alt="Image">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6">
                    <div class="about-img">
                        <img src="{{ asset('assets/front/img/banner2.gif')}}" class="img-fluid rounded w-100 pb-2" alt="Image">
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6">
                    @if(($mostPredicTeam[0]['prediction_count'] ?? 0) > 0)
                        <h2><i class="fa-solid fa-trophy"></i> ทีเด็ด 3 ทีม คนทายผลบอลสูงสุด</h2>
                    @endif
                    <div class="row g-4 text-center align-items-center justify-content-center">
                        @foreach([0,1,2] as $index)
                            @if(($mostPredicTeam[$index]['prediction_count'] ?? 0) > 0)
                                <div class="col-sm-4">
                                    <div class="bg-primary rounded p-4">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="counter-value fs-1 fw-bold text-dark" data-toggle="counter-up">
                                                {{$mostPredicTeam[$index]['prediction_count'] ?? 0}}
                                            </span>
                                            <h4 class="text-dark fs-1 mb-0" style="font-weight: 600; font-size: 25px;">
                                                {{ Number::shortNumberUnit($mostPredicTeam[$index]['prediction_count'] ?? 0) }}
                                            </h4>
                                        </div>
                                        <div class="w-100 d-flex align-items-center justify-content-center">
                                            <p class="text-white mb-0">{{ $this->mostPredicTeam[$index]['name'] ?? 'ไม่มี' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
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
                <div class="col-lg-6">
                    <img src="{{ asset('assets/front/img/guball-1.jpg') }}" class="img-fluid rounded w-100 pb-2" alt="Image">
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('assets/front/img/guball-2.jpg') }}" class="img-fluid rounded w-100 pb-2" alt="Image">
                </div>
            </div>
            <a href="https://webball.live/" target="_blank">
                <img src="{{ asset('assets/front/img/banner.gif') }}" class="img-fluid rounded w-100 pb-2" alt="Image">
            </a>

            <div class="row g-5 align-items-center">
                <!-- สัปดาห์ -->
                <div class="col-lg-6">
                    <h2><i class="fa-solid fa-ranking-star"></i> 10 อันดับเซียนทายแม่นประจำสัปดาห์</h2>
                    <table>
                        <thead>
                        <tr>
                            <th>อันดับ</th>
                            <th>ชื่อ</th>
                            <th>ความแม่นยำ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 0; $i < 10; $i++)
                            @if($sianAccurateWeek[$i] ?? null)
                                @php
                                    $acc = max(0, min(100, (float) ($sianAccurateWeek[$i]?->accurate ?? 0)));
                                    $barClass = $acc >= 70 ? 'bg-success' : ($acc >= 40 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <tr>
                                    <th>
                                        @if($i+1 == 1)
                                            <i class="fa-solid fa-trophy" style="color: gold;"></i>
                                        @elseif($i+1 == 2)
                                            <i class="fa-solid fa-trophy" style="color: silver;"></i>
                                        @elseif($i+1 == 3)
                                            <i class="fa-solid fa-trophy" style="color: #cd7f32;"></i>
                                        @else
                                            <span class="d-inline-flex justify-content-center align-items-center me-1"
                                                  style="width:28px;height:28px;border-radius:50%;background:#f1f1f1;font-weight:600;">
                                                {{ $i+1 }}
                                            </span>
                                        @endif
                                        @if($i+1 <= 3) {{ $i+1 }} @endif
                                    </th>
                                    <td><a class="text-dark">{{ $sianAccurateWeek[$i]?->user->nick_name }}</a></td>
                                    <td style="min-width:180px">
                                        <div class="progress" style="height:18px;">
                                            <div class="progress-bar {{ $barClass }}"
                                                 role="progressbar"
                                                 style="width: {{ $acc }}%;"
                                                 aria-valuenow="{{ $acc }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($acc, 2) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th>
                                        @if($i+1 <= 3)
                                            {{-- กรณีไม่มีข้อมูล แต่อยากให้หัวตารางสวยสม่ำเสมอ --}}
                                            @if($i+1 == 1)
                                                <i class="fa-solid fa-trophy" style="color: gold;"></i> {{ $i+1 }}
                                            @elseif($i+1 == 2)
                                                <i class="fa-solid fa-trophy" style="color: silver;"></i> {{ $i+1 }}
                                            @else
                                                <i class="fa-solid fa-trophy" style="color: #cd7f32;"></i> {{ $i+1 }}
                                            @endif
                                        @else
                                            <span class="d-inline-flex justify-content-center align-items-center me-1"
                                                  style="width:28px;height:28px;border-radius:50%;background:#f1f1f1;font-weight:600;">
                                                {{ $i+1 }}
                                            </span>
                                        @endif
                                    </th>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endfor
                        </tbody>
                    </table>
                </div>

                <!-- เดือน -->
                <div class="col-lg-6">
                    <h2><i class="fa-solid fa-ranking-star"></i> 10 อันดับเซียนทายแม่นประจำเดือน</h2>
                    <table>
                        <thead>
                        <tr>
                            <th>อันดับ</th>
                            <th>ชื่อ</th>
                            <th>ความแม่นยำ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 0; $i < 10; $i++)
                            @if($sianAccurateMonth[$i] ?? null)
                                @php
                                    $acc = max(0, min(100, (float) ($sianAccurateMonth[$i]?->accurate ?? 0)));
                                    $barClass = $acc >= 70 ? 'bg-success' : ($acc >= 40 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <tr>
                                    <th>
                                        @if($i+1 == 1)
                                            <i class="fa-solid fa-trophy" style="color: gold;"></i>
                                        @elseif($i+1 == 2)
                                            <i class="fa-solid fa-trophy" style="color: silver;"></i>
                                        @elseif($i+1 == 3)
                                            <i class="fa-solid fa-trophy" style="color: #cd7f32;"></i>
                                        @else
                                            <span class="d-inline-flex justify-content-center align-items-center me-1"
                                                  style="width:28px;height:28px;border-radius:50%;background:#f1f1f1;font-weight:600;">
                                                {{ $i+1 }}
                                            </span>
                                        @endif
                                        @if($i+1 <= 3) {{ $i+1 }} @endif
                                    </th>
                                    <td><a class="text-dark">{{ $sianAccurateMonth[$i]?->user->nick_name }}</a></td>
                                    <td style="min-width:180px">
                                        <div class="progress" style="height:18px;">
                                            <div class="progress-bar {{ $barClass }}"
                                                 role="progressbar"
                                                 style="width: {{ $acc }}%;"
                                                 aria-valuenow="{{ $acc }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($acc, 2) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th>
                                        @if($i+1 <= 3)
                                            @if($i+1 == 1)
                                                <i class="fa-solid fa-trophy" style="color: gold;"></i> {{ $i+1 }}
                                            @elseif($i+1 == 2)
                                                <i class="fa-solid fa-trophy" style="color: silver;"></i> {{ $i+1 }}
                                            @else
                                                <i class="fa-solid fa-trophy" style="color: #cd7f32;"></i> {{ $i+1 }}
                                            @endif
                                        @else
                                            <span class="d-inline-flex justify-content-center align-items-center me-1"
                                                  style="width:28px;height:28px;border-radius:50%;background:#f1f1f1;font-weight:600;">
                                                {{ $i+1 }}
                                            </span>
                                        @endif
                                    </th>
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
                <div class="col-lg-6">
                    <div class="accordion bg-light rounded p-4" id="accordionExample">
                        <div class="accordion-item border-0 mb-4">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button text-dark fs-5 fw-bold rounded-top"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true">
                                    <i class="fa-solid fa-list"></i>  กติกา ทายผลบอล
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show">
                                <div class="accordion-body my-2">
                                    <h6>1. ลูกค้าสามารถเข้าร่วมกิจกรรมได้ฟรี เพียงสมัครเป็นสมาชิกเว็บ GUBET.COM</h6>
                                    <h6>2. ลูกค้าสามารถเลือกทายผลฟุตบอลได้เพียงวันละ 3 คู่ เท่านั้น</h6>
                                    <h6>3. เมื่อลูกค้าเลือกทายผลฟุตบอลครบทั้ง 3 คู่แล้ว ให้ยืนยันด้วยการคลิกปุ่ม "ทายผล" ด้านล่าง</h6>
                                    <h6>4. หากลูกค้าเลือกทายผลผิดคู่หรือผิดฝั่งสามารถแก้ไขได้ด้วยการคลิกปุ่ม "รีเซ็ต"ด้านล่าง</h6>
                                    <h6>5. เมื่อลูกค้าเลือกทายผลเป็นที่เรียบร้อยแล้วจะไม่สามารถกลับมาแก้ไขการทายผลได้อีก</h6>
                                    <h6>6. ทางเว็บไซต์กำหนดเกณฑ์การให้คะแนนในการทายผลแต่ละคู่ดังนี้ ชนะเต็ม 100 เหรียญทอง ชนะครึ่ง 50 เหรียญทอง เสมอหรือแพ้ เท่ากับ 0</h6>
                                    <h6>7. ลูกค้าสามารถติดตามผลการทายผลบอลได้ที่ประวัติคะแนน</h6>
                                    <h6>8. ลูกค้าสามารถตรวจสอบอันดับคะแนนได้ที่ปุ่มอันดับคะแนนด้านบนแล้วเลือกเดือนที่ต้องการตรวจสอบ</h6>
                                    <h6>9. หากพบข้อผิดพลาดหรือมีข้อเสนอแนะสามารถติดต่อเจ้าหน้าที่ได้ทาง LINE</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-img RotateMoveRight rounded">
                        <img src="{{ asset('assets/front/img/ball2.png') }}" class="img-fluid rounded w-100" alt="Image">
                        <a class="faq-btn btn btn-primary rounded-pill text-white py-3 px-5" href="https://webball.live/" target="_blank">
                            <i class="fas fa-video"></i> ดูบอลออนไลน์<i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->
