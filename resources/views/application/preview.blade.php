@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3>Riepilogo Domanda</h3>
        <button onclick="window.print()" class="btn btn-secondary"><i class="bi bi-printer"></i> Stampa</button>
    </div>
    <div class="card-body">
        <h4>Anagrafica</h4>
        <p><strong>Nome:</strong> {{ $application->user->name }} {{ $application->user->surname }}</p>
        <p><strong>Matricola:</strong> {{ $application->user->matricola }}</p>
        <!-- ... add info ... -->

        <h4 class="mt-4">Posizione</h4>
        <p><strong>Differenziale:</strong> {{ $application->area_differenziale }} / {{ $application->profilo_differenziale }}</p>
        <p><strong>Valutazione Media:</strong> {{Number_format($application->media_valutazioni, 2) }}</p>

        <h4 class="mt-4">Anzianità</h4>
        <table class="table table-bordered">
            <thead><tr><th>Esperienza</th><th>Dal</th><th>Al</th><th>Ente</th></tr></thead>
            <tbody>
                @foreach($application->seniorityRecords as $rec)
                <tr>
                    <td>{{ $rec->esperienza_come }}</td>
                    <td>{{ optional($rec->dal)->format('d/m/Y') }}</td>
                    <td>{{ optional($rec->al)->format('d/m/Y') }}</td>
                    <td>{{ $rec->ente }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($application->disciplinaryProceedings->isNotEmpty())
        <h4 class="mt-4">Procedimenti Disciplinari</h4>
        <table class="table table-bordered">
            <thead><tr><th>Data</th><th>Oggetto</th></tr></thead>
            <tbody>
                @foreach($application->disciplinaryProceedings as $proc)
                <tr>
                    <td>{{ optional($proc->data)->format('d/m/Y') }}</td>
                    <td>{{ $proc->oggetto }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <h4 class="mt-4">Titoli Culturali e Professionali</h4>
        <table class="table table-bordered">
            <thead><tr><th>Descrizione</th><th>Istituto</th><th>Data Conseguimento</th><th>Durata</th><th>Attinente</th></tr></thead>
            <tbody>
                @forelse($application->titles as $title)
                <tr>
                    <td>{{ $title->descrizione }}</td>
                    <td>{{ $title->istituto }}</td>
                    <td>{{ optional($title->data_conseguimento)->format('d/m/Y') }}</td>
                    <td>{{ $title->durata }}</td>
                    <td>{{ $title->attinente ? 'Si' : 'No' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Nessun titolo inserito</td></tr>
                @endforelse
            </tbody>
        </table>

        <h4 class="mt-4">Formazione Non Obbligatoria</h4>
        <table class="table table-bordered">
            <thead><tr><th>Descrizione</th><th>Istituto</th><th>Data</th><th>Durata (Ore)</th><th>Esito</th></tr></thead>
            <tbody>
                @forelse($application->trainings as $training)
                <tr>
                    <td>{{ $training->descrizione }}</td>
                    <td>{{ $training->istituto }}</td>
                    <td>{{ optional($training->data_conseguimento)->format('d/m/Y') }}</td>
                    <td>{{ $training->durata_ore }}</td>
                    <td>{{ $training->esito }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Nessuna formazione inserita</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($application->declaration_two_years)
        <div class="alert alert-info mt-4">
            <i class="bi bi-check-circle-fill"></i> Dichiaro di non aver beneficiato, negli ultimi due anni antecedenti il 01/01/2025, di nessun differenziale stipendiale/progressione economica.
        </div>
        @endif

        <!-- ... other tables ... -->

        @if($application->status == 'draft')
        <div class="mt-5 text-center section-hide-print">
            <div class="alert alert-warning">
                ATTENZIONE: Verifica attentamente i dati prima di inviare. L'operazione non è reversibile.
            </div>
            <a href="{{ route('application.edit', $application->id) }}" class="btn btn-secondary">Torna alla Modifica</a>
            <form action="{{ route('application.submit', $application->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Confermi l\'invio definitivo?')">INVIA DOMANDA DEFINITIVA</button>
            </form>
        </div>
        @else
        <div class="alert alert-success mt-4">Domanda inviata il {{ optional($application->submission_date)->format('d/m/Y H:i') }}</div>
        @endif
    </div>
</div>

<style>
@media print {
    .section-hide-print, .navbar { display: none !important; }
}
</style>
@endsection
