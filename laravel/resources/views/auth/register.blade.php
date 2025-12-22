@extends('layouts.app')

@php
$areas = [
    'COLLABORATORI PROFESSIONALI',
    'ISTRUTTORI',
    'FUNZIONARI ED EQ',
    'DIRIGENTI',
];

$profiles = [
    'COLLABORATORE PROFESSIONALE AMMINISTRATIVO',
    'COLLABORATORE PROFESSIONALE TECNICO',
    'FUNZIONARIO AMMINISTRATIVO',
    'FUNZIONARIO AVVOCATO',
    'FUNZIONARIO ECONOMICO-FINANZIARIO',
    'FUNZIONARIO INFORMATICO',
    'FUNZIONARIO TECNICO',
    'FUNZIONARIO VIGILANZA',
    'ISTRUTTORE AMMINISTRATIVO',
    'ISTRUTTORE ECONOMICO-FINANZIARIO',
    'ISTRUTTORE INFORMATICO',
    'ISTRUTTORE TECNICO',
    'ISTRUTTORE VIGILANZA',
];
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header fw-bold">Registrazione Utente - Anagrafica</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <h5 class="mt-2 mb-3 text-primary">Dati Utenza</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nome</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Cognome</label>
                            <input type="text" name="surname" class="form-control" required value="{{ old('surname') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3 text-primary">Anagrafica & Servizio</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Matricola</label>
                            <input type="text" name="matricola" class="form-control" required value="{{ old('matricola') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Area Appartenenza Attuale</label>
                            <select name="area_appartenenza" class="form-select" required>
                                <option value="">Seleziona Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" {{ old('area_appartenenza') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Profilo Attuale</label>
                            <select name="profilo_attuale" class="form-select" required>
                                <option value="">Seleziona Profilo</option>
                                @foreach($profiles as $profile)
                                    <option value="{{ $profile }}" {{ old('profilo_attuale') == $profile ? 'selected' : '' }}>{{ $profile }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nato a</label>
                            <input type="text" name="nato_a" class="form-control" required value="{{ old('nato_a') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Il (Data di Nascita)</label>
                            <input type="date" name="data_nascita" class="form-control" required value="{{ old('data_nascita') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Residente a</label>
                            <input type="text" name="residente_a" class="form-control" required value="{{ old('residente_a') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Via/Piazza</label>
                            <input type="text" name="via" class="form-control" required value="{{ old('via') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>CAP</label>
                            <input type="text" name="cap" class="form-control" required value="{{ old('cap') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Prov.</label>
                            <input type="text" name="prov" class="form-control" required value="{{ old('prov') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Codice Fiscale</label>
                            <input type="text" name="codice_fiscale" class="form-control" required value="{{ old('codice_fiscale') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Telefono</label>
                            <input type="text" name="telefono" class="form-control" required value="{{ old('telefono') }}">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Registrati</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
