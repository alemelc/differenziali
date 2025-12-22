@extends('layouts.app')

@section('content')
<h2 class="mb-4">Amministrazione</h2>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Imposta Date Apertura</div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <span class="input-group-text">Dal</span>
                        <input type="date" name="start_date" class="form-control" value="{{ $start }}">
                        <span class="input-group-text">Al</span>
                        <input type="date" name="end_date" class="form-control" value="{{ $end }}">
                        <button class="btn btn-primary">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.export') }}" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Esporta CSV</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-warning text-dark">Domande in Bozza (Più recenti per utente)</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Matricola</th>
                    <th>Dipendente</th>
                    <th>Ultim Modifica</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($draftApplications as $app)
                <tr>
                    <td>{{ $app->user->matricola }}</td>
                    <td>{{ $app->user->surname }} {{ $app->user->name }}</td>
                    <td>{{ $app->updated_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $app->status }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.application.show', $app->id) }}" class="btn btn-sm btn-info text-white">Vedi Bozza</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Nessuna bozza presente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-success text-white">Domande Inviate</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Matricola</th>
                    <th>Dipendente</th>
                    <th>Stato</th>
                    <th>Data Invio</th>
                    <th>Posizione</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sentApplications as $app)
                <tr>
                    <td>{{ $app->user->matricola }}</td>
                    <td>{{ $app->user->surname }} {{ $app->user->name }}</td>
                    <td>
                        <span class="badge bg-success">{{ $app->status }}</span>
                    </td>
                    <td>{{ $app->submission_date ? $app->submission_date->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $app->profilo_differenziale }}</td>
                    <td>
                        <a href="{{ route('admin.application.show', $app->id) }}" class="btn btn-sm btn-info text-white">Dettagli</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Nessuna domanda inviata.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
