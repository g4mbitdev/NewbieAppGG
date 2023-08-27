@extends('templates.template1')

@section('content')

    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1>Newbie.V4</h1>
                <p>Still can't think of something catchy to add here!</p>
            </div>
        </div>
    </section>

    <form method="get" action="{{route('matchHistory')}}">
        @csrf
        <div class="input-group mb-5 w-50 text-center container mb-5">
            <input type="text" name="summonerName" id="summonerName" class="form-control" minlength="3" maxlength="16" aria-label="Default" aria-describedby="inputGroup-sizing-default" placeholder="Insert summoner name here">
            <button type="submit">Search</button>
        </div>
    </form>

    <script>

        const summonerName = document.getElementById("summonerName");
        summonerName.addEventListener("input", function(event){
            const value = summonerName.value;
            const valid = /^[a-zA-Z0-9]+$/.test(value);
            if(!valid){
                summonerName.setCustomValidity("Invalid Summoner Name, please try again.");
            }else{
                summonerName.setCustomValidity("");
            }
        })

    </script>

@endsection
