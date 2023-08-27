
@extends('templates.template2')

@section('content')

    <section>
        <div class="container">
            <div class="summoner-icon">
            </div>
            <table class="table table-borderless">
                <thead>
                <tr>
                    <tb scope="col">
                        <img src="https://ddragon.leagueoflegends.com/cdn/13.7.1/img/profileicon/{{$data['profileIconId']}}.png" class="img-thumbnail" width="100" height="100" alt="Summoner Icon">
                    </tb>
                    <tb>Summoner: {{$stats['summonerName']}}</tb>
                    <tb></tb>
                    <tb></tb>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Most Played Champions:
                        @foreach($champNames as $name)
                            <img src="https://ddragon.leagueoflegends.com/cdn/{{$version}}/img/champion/{{$name}}.png">
                        @endforeach
                    </th>
                    <th scope="row">Level: {{$data['summonerLevel']}}</th>
                    <th scope="row">Rank: {{$stats['tier']}} {{$stats['rank']}}</th>
                    <th scope="row">Win Ratio: {{$stats['wins']}}/{{$stats['losses']}}</th>
                </tr>

                </tbody>
            </table>
        </div>
    </section>

    <section>
        <div class="container">
            <ul class="list-group">
                @foreach($matches as $x => $match)
                    <li class="list-group-item">
                        @foreach($match as $participant)
                            @if($participant['puuid'] === $data['puuid'])
                                <div>
                                    <img src="https://cdn.communitydragon.org/latest/champion/{{$participant['championId']}}/square" class="w-10 h-10" alt="...">
                                    <h5>{{$participant['championName']}}</h5>
                                    Position:{{$participant['individualPosition']}}<br>
                                    KDA:{{$participant['kills']}}/{{$participant['deaths']}}/{{$participant['assists']}}<br>
                                    Gold Earned:{{$participant['goldEarned']}}<br>
                                    @if($participant['win'] == 'true')
                                        VICTORY
                                    @else
                                        DEFEAT
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">Add Date Here</small>
                                </div>
                            @endif

                        @endforeach
                        <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#collapseExample{{$x}}" aria-expanded="false" aria-controls="collapseExample{{$x}}">Show Game Details</button>
                        <div class="collapse" id="collapseExample{{$x}}">
                            <div class="row">
                                <div class="col">
                                    <ul class="list-group list-group-flush" id="victors">
                                        @foreach($match as $participant)
                                            @if($participant['win'] === true)
                                                    <li class="list-group-item bg-info">
                                                        <img src="https://cdn.communitydragon.org/latest/champion/{{$participant['championId']}}/square" width="50" height="50" alt="...">
                                                        {{$participant['championName']}} ||
                                                        Creep Score:{{$participant['totalMinionsKilled']}} ||
                                                        Vision Score:{{$participant['visionScore']}}
                                                        KDA:{{$participant['kills']}}/{{$participant['deaths']}}/{{$participant['assists']}} ||
                                                        Damage Dealt to Champions: {{$participant['totalDamageDealtToChampions']}} ||
                                                        @foreach($spellData as $spell)
                                                            @if($participant['summoner2Id']  == $spell['key'])
                                                                Summoner Spell 1:
                                                                <img src="https://ddragon.leagueoflegends.com/cdn/{{$version}}/img/spell/{{$spell['image']['full']}}">
                                                            @elseif($participant['summoner1Id']  == $spell['key'])
                                                                Summoner Spell 2:
                                                                <img src="https://ddragon.leagueoflegends.com/cdn/{{$version}}/img/spell/{{$spell['image']['full']}}">
                                                            @endif
                                                        @endforeach
                                                    </li>
                                            @else()
                                                <li class="list-group-item bg-danger" >
                                                    <img src="https://cdn.communitydragon.org/latest/champion/{{$participant['championId']}}/square" width="50" height="50" alt="{{$participant['championName']}}">
                                                    {{$participant['championName']}} ||
                                                    Creep Score:{{$participant['totalMinionsKilled']}} ||
                                                    Vision Score:{{$participant['visionScore']}} ||
                                                    KDA:{{$participant['kills']}}/{{$participant['deaths']}}/{{$participant['assists']}} ||
                                                    Damage Dealt to Champions: {{$participant['totalDamageDealtToChampions']}} ||
                                                        @foreach($spellData as $spell)
                                                            @if($participant['summoner2Id']  == $spell['key'])
                                                            Summoner Spell 1:
                                                            <img src="https://ddragon.leagueoflegends.com/cdn/{{$version}}/img/spell/{{$spell['image']['full']}}" alt="{{$spell['image']['full']}}">
                                                            @elseif($participant['summoner1Id']  == $spell['key'])
                                                            Summoner Spell 2:
                                                            <img src="https://ddragon.leagueoflegends.com/cdn/{{$version}}/img/spell/{{$spell['image']['full']}}" alt="{{$spell['image']['full']}}">
                                                            @endif
                                                    @endforeach
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

@endsection
