@php
    use Carbon\Carbon;
    use App\Helper\ThaiDate;
    use App\Domain\Football\Match\PredictionResult;
@endphp
<div>
    <!-- ผลทายของลูกค้า -->
    <div class="container-fluid about py-5">
        <div class="container col-lg-5 col-xs-12">
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-4">{{$this->rank ?? 'ไม่มีอันดับ'}}<i class="fa-solid fa-trophy"></i> {{$user->nick_name}}</h1>
                <h4 class="text-primary">เกมทายผลบอล วันที่ {{ ThaiDate::toDateTime(Carbon::now()->toDateTimeString(), isShort: true) }}</h4>
            </div>
            <table>
              <thead>
                <tr>
                  <th style="width: 35%;">เจ้าบ้าน</th>
                  <th style="width: 30%;">อัตราต่อรอง</th>
                  <th style="width: 35%;">ทีมเยือน</th>
                </tr>
              </thead>
                <tbody>
                    @foreach ($this->lastPredic as $leagueId => $matches)
                        @php
                            $league = $matches->first()->league;
                            $leagueName = optional($league)->name ?? 'No league';
                            $leagueCountry = optional($league)->country ?? '(No country)';
                        @endphp
                        <th colspan="4" class="bg-th">{{ $leagueName }} {{ $leagueCountry }}</th>

                        @foreach ($matches as $match)
                            @php
                                $isSingle = $match->prediction->first()?->type === 'single';
                                $selectedTeam = $match->prediction->first()?->selected_team_id;
                            @endphp
                            <tr>
                                <td>
                                    <div class="radio-item">
                                        <input type="radio" {{ $match->home_team_id === $selectedTeam ? 'checked' : '' }} disabled>
                                        <label for="radio-{{ $match->id }}">
                                            {{ $match->homeTeam->name }}
                                            @if($isSingle && $match->home_team_id === $selectedTeam)
                                                <i class="fas fa-star text-warning"></i>
                                            @endif
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    เวลา {{ ThaiDate::toTime($match->match_date) }}
                                    <hr>
                                    @if ($match->rate_type == 'normal')
                                        <p>{{ $match->home_team_rate }}/{{ $match->away_team_rate }}</p>
                                    @elseif($match->rate_type == 'pp')
                                        <p>@lang('website.rate_type.pp')</p>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="radio-item">
                                        <input type="radio" {{ $match->away_team_id === $selectedTeam ? 'checked' : '' }} disabled>
                                        <label for="radio-{{ $match->id }}-2">
                                            {{ $match->awayTeam->name }}
                                            @if($isSingle && $match->away_team_id === $selectedTeam)
                                                <i class="fas fa-star text-warning"></i>
                                            @endif
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <div class="row">
              <div class="col-12 mt-2 text-center">
                  <a href="{{route('prediction-rank')}}" class="btn btn-primary" role="button" style="width: 80%;">อันดับคะแนน</a>
              </div>
             </div>

             <br>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>วันที่</th>
                                <th>บอลเต็ง <i class="fas fa-star text-warning"></i></th>
                                <th>บอลสเต็ป</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->pointHistories ?? [] as $key => $items)
                                @php
                                    $singles = $items->where('type', 'single');
                                    $steps = $items->where('type', 'step');
                                @endphp
                                <tr>
                                    <td><strong>{{ $key }}</strong></td>

                                    {{-- ✅ บอลเต็ง --}}
                                    <td>
                                        <div class="d-flex flex-wrap justify-content-center gap-2">
                                            @foreach ($singles as $prediction)
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>{{ $prediction->team->name ?? '-' }}</span>
                                                    {!! \App\Domain\Football\Match\PredictionResult::toBadge($prediction->result ?? null) !!}
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>

                                    {{-- ✅ บอลสเต็ป --}}
                                    <td>
                                        <div class="d-flex flex-wrap justify-content-center gap-2">
                                            @foreach ($steps as $prediction)
                                                <div class="d-flex align-items-center gap-1">
                                                    <span>{{ $prediction->team->name ?? '-' }}</span>
                                                    {!! \App\Domain\Football\Match\PredictionResult::toBadge($prediction->result ?? null) !!}
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

        </div>
    </div>
    <!-- End -->
</div>
