<?php

namespace App\Http\Controllers\RiotAPI;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;


class MatchHistory extends Controller
{

    public string $apiKey;
    public string $summonerName;
    public Client $client;
    public mixed $puuId;
    public mixed $summonerId;

    public function __construct(){

        $this->apiKey = env('RIOT_API_KEY');
        $this->client = new Client;

    }

    /**
     * @throws GuzzleException
     */
    public function getMatchHistory(Request $request)
    {

        $summonerName = $request->input('summonerName');
        $validateName = $request->validate(['summonerName' => 'required|regex:/^[a-zA-Z0-9]+$/',]);

        if($validateName !==null){

            $this->summonerName = $summonerName;

        }else{

            return redirect("/");

        }
        try {

        //Get information on a player's LoL Account
        $response = $this->client->request('GET', "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/$this->summonerName?api_key=$this->apiKey");

        $data = json_decode($response->getBody(), true);

        //dd($data);

        $this->puuId = $data['puuid'];
        $this->summonerId = $data['id'];
        $this->summonerName = $data['name'];

        //dd($data);

        //Get player's last 12 played games
        $response = $this->client->request('GET', "https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/$this->puuId/ids?start=0&count=12&api_key=$this->apiKey");
        $matchIds = json_decode($response->getBody(), true);

        $matches = [];
        foreach ($matchIds as $matchId) {

            $response = $this->client->request('GET', "https://europe.api.riotgames.com/lol/match/v5/matches/$matchId?api_key=$this->apiKey");
            $matchData = json_decode($response->getBody(), true);

            //dd($participants);
            foreach ($matchData['info']['participants'] as $participant) {

                if ($participant['puuid'] === $this->puuId) {

                    //dd($matchData['info']['participants']);
                    $matches[] = $matchData['info']['participants'];
                    //dd($participant);
                    break;
                }
            }
        }

        //Get information on player's current Ranked 5v5 Solo history
        $response = $this->client->request('GET', "https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/$this->summonerId?api_key=$this->apiKey");
        $summonerInfo = json_decode($response->getBody(), true);
        $stats = $summonerInfo[0];

        //Get information on played champions
        $response = $this->client->request('GET', "https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/$this->summonerId/top?count=3&api_key=$this->apiKey");
        $champInfo = json_decode($response->getBody(), true);
        //dd($champInfo);

        $champId = [];
        foreach ($champInfo as $champ) {

            $champId[] = $champ['championId'];

        }

        //Here we obtain champion name and images

        $response = $this->client->request('GET', "https://ddragon.leagueoflegends.com/api/versions.json");
        $version = json_decode($response->getBody(), true)[0];

        $response = $this->client->request('GET', "https://ddragon.leagueoflegends.com/cdn/$version/data/en_US/champion.json");
        $champData = json_decode($response->getBody(), true)['data'];


        $champNames = [];
        foreach ($champId as $id) {
            foreach ($champData as $champ) {
                if ($champ['key'] == $id) {

                    $champ['name'] = str_replace(' ', '', $champ['name']);
                    $champNames[] = $champ['name'];
                }
            }
        }

        //Here we obtain the summoner icon images

        $response = $this->client->request('GET', "https://ddragon.leagueoflegends.com/cdn/$version/data/en_US/summoner.json");
        $spellData = json_decode($response->getBody(), true)['data'];

        $spellKeys = [];
        $spellNames = [];

        foreach($spellData as $spell) {
            $spellKeys[] = $spell['key'];
        }

        //$spellNames[] = $spell['image']['full'];

        foreach($spellKeys as $key){
            foreach($spellData as $spell){
                if($spell['key'] == $key){
                    $spellNames[] = $spell['image']['full'];
                }
            }
        }

        //dd($participant);

        return view('matchList', compact('matches', 'stats', 'champNames', 'version', 'data', 'spellNames', 'spellData'));

        }catch(Exception){

            return response()->view('errors.404',[], 404);

        }
    }
}
