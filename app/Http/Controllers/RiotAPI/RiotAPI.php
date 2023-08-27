<?php

namespace App\Http\Controllers\RiotAPI;


use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;


class RiotAPI extends Controller
{
    public string $apiKey;
    public string $summonerName;
    public Client $client;

    public function __construct()
    {
        $this->apiKey = env('RIOT_API_KEY');
        $this->client = new Client;
    }

}
