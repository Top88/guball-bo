<div class="container py-4">
    @forelse ($resultsByDate as $date => $matches)
        <h3 class="mt-4">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>{{ __('website.general.home_team') }}</th>
                    <th>{{ __('website.general.away_team') }}</th>
                    <th>{{ __('website.general.result') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($matches as $match)
                    <tr>
                        <td>
                            @if ($match->result?->team_match_result === 'home')
                                <i class="fa-solid fa-trophy text-warning"></i>
                            @endif
                            {{ $match->homeTeam?->name }}
                        </td>
                        <td>
                            @if ($match->result?->team_match_result === 'away')
                                <i class="fa-solid fa-trophy text-warning"></i>
                            @endif
                            {{ $match->awayTeam?->name }}
                        </td>
                        <td>
                            @if ($match->result?->team_match_result === 'draw')
                                {{ __('website.general.draw') }}
                            @else
                                {{ $match->result?->home_team_goal }} - {{ $match->result?->away_team_goal }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p>{{ __('website.general.no_data') }}</p>
    @endforelse
</div>
