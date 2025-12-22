@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Benvenuto, {{ Auth::user()->name }}</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                @if(!$application)
                    <h5 class="card-title">Nessuna domanda presente</h5>
                    <p class="card-text">Puoi compilare una nuova domanda cliccando sul pulsante qui sotto.</p>
                    @if($canCreate)
                        <a href="{{ route('application.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-file-earmark-plus"></i> Nuova domanda di differenziale stipendiale
                        </a>
                    @else
                        <div class="alert alert-warning">Le iscrizioni sono chiuse o non ancora aperte.</div>
                    @endif
                @else
                    <h5 class="card-title">Stato della tua domanda: 
                        <span class="badge bg-{{ $application->status == 'submitted' ? 'success' : 'warning' }}">
                            {{ $application->status == 'submitted' ? 'INVIATA' : 'BOZZA' }}
                        </span>
                    </h5>
                    
                    @if($application->status == 'submitted')
                        <p class="mt-2">Domanda inviata il {{ $application->submission_date->format('d/m/Y H:i') }}</p>
                        <a href="{{ route('application.preview', $application->id) }}" class="btn btn-info">
                            <i class="bi bi-eye"></i> Visualizza Dettagli
                        </a>
                    @else
                        <p class="mt-2">Puoi ancora modificare la tua domanda prima dell'invio definitivo.</p>
                        @if($canEdit)
                            <a href="{{ route('application.edit', $application->id) }}" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Modifica domanda di differenziale
                            </a>
                        @else
                           <div class="alert alert-warning">Tempo scaduto per le modifiche.</div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
