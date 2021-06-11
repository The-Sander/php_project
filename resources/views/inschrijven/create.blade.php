@extends('layouts.templatenonav')

@section('title', 'Inschrijven')

@section('main')

@include('shared.alert')
<h1>Inschrijven</h1>



<h4>Ik heb mezelf al ingeschreven en zou graag mijn inschrijving wijzigen.</h4>
<div class='row'>
    <p class='col'>Mijn token</p> <input class='col-sm' type="text" id="tokentxt"> <a id="tokenbtn"
        class="col btn btn-success"> Laad mijn account </a>
</div>
@if(!session()->has('success'))
<hr>
@if(isset($avond))
<h4>Nieuwe inschrijving voor {{$avond->evenementnaam}}</h4>
<form action="/inschrijven" method="post" novalidate>
    @csrf
    <div class="form-group">
        <input type='text' name='token' id='token' style="display:none" value="{{isset($alumni)?$alumni[0]->token:""}}">
        <label for="name">Naam</label>
        <input type="text" name="name" id="name" class="form-control {{ $errors->first('name') ? 'is-invalid' : '' }}"
            placeholder="Naam" required value="{{isset($alumni)?$alumni[0]->achternaam:''}}">
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    </div>
    <div class="form-group">
        <label for="name">Voornaam</label>
        <input type="text" name="surname" id="surname"
            class="form-control {{ $errors->first('name') ? 'is-invalid' : '' }}" placeholder="Voornaam" required
            value="{{isset($alumni)?$alumni[0]->voornaam:''}}">
        <div class="invalid-feedback">{{ $errors->first('surname') }}</div>
    </div>

    <div class="form-group">
        <label for="email">E-mailadres</label>
        <input type="email" name="email" id="email"
            class="form-control {{ $errors->first('name') ? 'is-invalid' : '' }}" placeholder="E-mailadres" required
            value="{{isset($alumni)?$alumni[0]->mail:''}}">
        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
    </div>

    @foreach ($avond->vragen as $vraag)
    @if($vraag->typevraag->type == "Text")
    <div class="form-group">
        <label for="{{$vraag->id}}">{{$vraag->inhoud}}</label>
        <input class="form-control" type='text' name='{{$vraag->id}}' id='vraag{{$vraag->id}}'>
    </div>
    @elseif($vraag->typevraag->type == "Selecteer")
    <label for="{{$vraag->id}}">{{$vraag->inhoud}} </label>
    <select id="vraag{{$vraag->id}}" name="{{$vraag->id}}"
        class="form-control @error('select_antwoord') is-invalid @enderror" required>
        @foreach($vraag->antwoorden as $vraagantwoord)
        <option value="{{$vraagantwoord->id}}"> {{ $vraagantwoord->inhoud }}</option>
        @endforeach
    </select>
    @endif
    @endforeach
    <input type="number" id="avondID" name="avondID" value={{$avond->id}} style="display:none">
    <button type="submit" class="btn btn-success">Submit</button>
</form>
@else

<h4>Ik heb kom mezelf inschrijven voor volgende avond:</h4>
@foreach($evenementen as $evenement)
@if(sizeof($evenement->vragen)>0)
<p><a href='/inschrijven?avond={{$evenement->id}}'>{{$evenement->evenementnaam}}</a></p>
@endif
@endforeach
@endif



@endif
@endsection
@section('script_after')
<script defer>
    $(function(){
        $("#tokenbtn").on('click', function(){
         console.log('hey')
        const token = $('#tokentxt').val()
        $(this).attr('href', `/inschrijven?id=${token}`);
     })
 }())
</script>

@endsection
