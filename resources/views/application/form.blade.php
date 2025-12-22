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
<div class="row mb-5">
    <div class="col-12">
        <h2 class="mb-3">{{ $application ? 'Modifica Domanda' : 'Nuova Domanda' }}</h2>
        
        <form method="POST" action="{{ $application ? route('application.update', $application->id) : route('application.store') }}">
            @csrf
            @if($application) @method('PUT') @endif
            
            <!-- SEZIONE ANAGRAFICA (Precompilata) -->
            <div class="card mb-4">
                <div class="card-header bg-light">ANAGRAFICA</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Cognome:</strong> {{ $application ? $application->user->surname : Auth::user()->surname }}</div>
                        <div class="col-md-6 mb-2"><strong>Nome:</strong> {{ $application ? $application->user->name : Auth::user()->name }}</div>
                        <div class="col-md-4 mb-2"><strong>Matricola:</strong> {{ $application ? $application->user->matricola : Auth::user()->matricola }}</div>
                        <div class="col-md-4 mb-2"><strong>Area:</strong> {{ $application ? $application->user->area_appartenenza : Auth::user()->area_appartenenza }}</div>
                        <div class="col-md-4 mb-2"><strong>Profilo:</strong> {{ $application ? $application->user->profilo_attuale : Auth::user()->profilo_attuale }}</div>
                        <!-- Add other fields as needed for display -->
                    </div>
                </div>
            </div>
            
            <!-- SEZIONE POSIZIONE ATTUALE -->
            <div class="card mb-4">
                <div class="card-header bg-light">POSIZIONE ATTUALE</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Area Appartenenza Differenziale</label>
                            <select name="area_differenziale" class="form-select" required>
                                <option value="">Seleziona Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" {{ old('area_differenziale', $application->area_differenziale ?? '') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Profilo Differenziale</label>
                            <select name="profilo_differenziale" class="form-select" required>
                                <option value="">Seleziona Profilo</option>
                                @foreach($profiles as $profile)
                                    <option value="{{ $profile }}" {{ old('profilo_differenziale', $application->profilo_differenziale ?? '') == $profile ? 'selected' : '' }}>{{ $profile }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="tempo_pieno" id="tp" onchange="toggleWorkTime('tp')" {{ old('tempo_pieno', $application->tempo_pieno ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tp">Tempo Pieno</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="tempo_parziale" id="tpart" onchange="toggleWorkTime('tpart')" {{ old('tempo_parziale', $application->tempo_parziale ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tpart">Tempo Parziale</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>% Tempo Parziale</label>
                            <input type="number" name="percentuale_tempo_parziale" id="percent_part" class="form-control" value="{{ old('percentuale_tempo_parziale', $application->percentuale_tempo_parziale ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SEZIONE PROCEDIMENTI DISCIPLINARI -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span>PROCEDIMENTI DISCIPLINARI</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="addRow('disciplinary')">+ Inserisci riga</button>
                </div>
                <div class="card-body" id="container-disciplinary">
                    @if($application && $application->disciplinaryProceedings)
                        @foreach($application->disciplinaryProceedings as $idx => $item)
                        <div class="row mb-3 border-bottom pb-2 item-row">
                            <div class="col-md-3"><label>Data</label><input type="date" name="disciplinary[{{$idx}}][data]" class="form-control" value="{{ optional($item->data)->format('Y-m-d') }}"></div>
                            <div class="col-md-8"><label>Oggetto</label><input type="text" name="disciplinary[{{$idx}}][oggetto]" class="form-control" value="{{ $item->oggetto }}"></div>
                            <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- SEZIONE ANZIANITÀ -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span>ANZIANITÀ DI SERVIZIO</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="addRow('seniority')">+ Inserisci riga</button>
                </div>
                <div class="card-body" id="container-seniority">
                    @if($application && $application->seniorityRecords)
                        @foreach($application->seniorityRecords as $idx => $item)
                        <div class="row mb-3 border-bottom pb-2 item-row">
                            <div class="col-md-3"><label>Esperienza Come</label>
                                <select name="seniority[{{$idx}}][esperienza_come]" class="form-select">
                                    <option value="">Seleziona</option>
                                    @foreach($profiles as $p)
                                        <option value="{{ $p }}" {{ $item->esperienza_come == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3"><label>Area</label>
                                <select name="seniority[{{$idx}}][nell_area]" class="form-select">
                                    <option value="">Seleziona</option>
                                    @foreach($areas as $a)
                                        <option value="{{ $a }}" {{ $item->nell_area == $a ? 'selected' : '' }}>{{ $a }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2"><label>Dal</label><input type="date" name="seniority[{{$idx}}][dal]" class="form-control" value="{{ optional($item->dal)->format('Y-m-d') }}"></div>
                            <div class="col-md-2"><label>Al</label><input type="date" name="seniority[{{$idx}}][al]" class="form-control" value="{{ optional($item->al)->format('Y-m-d') }}"></div>
                            <div class="col-md-2"><label>Ente</label><input type="text" name="seniority[{{$idx}}][ente]" class="form-control" value="{{ $item->ente }}"></div>
                            <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- SEZIONE VALUTAZIONI -->
            <div class="card mb-4">
                <div class="card-header bg-light">VALUTAZIONI TRIENNIO</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>2022</label><input type="number" step="0.01" name="valutazione_2022" class="form-control" value="{{ old('valutazione_2022', $application->valutazione_2022 ?? '') }}"></div>
                        <div class="col-md-4 mb-3"><label>2023</label><input type="number" step="0.01" name="valutazione_2023" class="form-control" value="{{ old('valutazione_2023', $application->valutazione_2023 ?? '') }}"></div>
                        <div class="col-md-4 mb-3"><label>2024</label><input type="number" step="0.01" name="valutazione_2024" class="form-control" value="{{ old('valutazione_2024', $application->valutazione_2024 ?? '') }}"></div>
                    </div>
                </div>
            </div>

            <!-- SEZIONE TITOLI -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span>TITOLI CULTURALI E PROFESSIONALI</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="addRow('titles')">+ Inserisci riga</button>
                </div>
                <div class="card-body" id="container-titles">
                    @if($application && $application->titles)
                        @foreach($application->titles as $idx => $item)
                        <div class="row mb-3 border-bottom pb-2 item-row">
                            <div class="col-md-4"><label>Descrizione</label><input type="text" name="titles[{{$idx}}][descrizione]" class="form-control" value="{{ $item->descrizione }}"></div>
                            <div class="col-md-2"><label>Durata</label><input type="text" name="titles[{{$idx}}][durata]" class="form-control" value="{{ $item->durata }}"></div>
                            <div class="col-md-3"><label>Istituto</label><input type="text" name="titles[{{$idx}}][istituto]" class="form-control" value="{{ $item->istituto }}"></div>
                            <div class="col-md-2"><label>Data</label><input type="date" name="titles[{{$idx}}][data_conseguimento]" class="form-control" value="{{ optional($item->data_conseguimento)->format('Y-m-d') }}"></div>
                            <div class="col-md-1"><label>Attinente</label><input type="checkbox" name="titles[{{$idx}}][attinente]" class="form-check-input" {{ $item->attinente ? 'checked' : '' }}></div>
                            <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- SEZIONE FORMAZIONE -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span>FORMAZIONE NON OBBLIGATORIA</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="addRow('trainings')">+ Inserisci riga</button>
                </div>
                <div class="card-body" id="container-trainings">
                    @if($application && $application->trainings)
                        @foreach($application->trainings as $idx => $item)
                        <div class="row mb-3 border-bottom pb-2 item-row">
                            <div class="col-md-4"><label>Descrizione</label><input type="text" name="trainings[{{$idx}}][descrizione]" class="form-control" value="{{ $item->descrizione }}"></div>
                            <div class="col-md-2"><label>Durata (ore)</label><input type="number" name="trainings[{{$idx}}][durata_ore]" class="form-control" value="{{ $item->durata_ore }}"></div>
                            <div class="col-md-3"><label>Istituto</label><input type="text" name="trainings[{{$idx}}][istituto]" class="form-control" value="{{ $item->istituto }}"></div>
                            <div class="col-md-2"><label>Data</label><input type="date" name="trainings[{{$idx}}][data_conseguimento]" class="form-control" value="{{ optional($item->data_conseguimento)->format('Y-m-d') }}"></div>
                            <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- SEZIONE DICHIARAZIONI -->
            <div class="card mb-4 border-danger">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="declaration_two_years" id="declaration_two_years" value="1" {{ old('declaration_two_years', $application->declaration_two_years ?? false) ? 'checked' : '' }} required>
                        <label class="form-check-label" for="declaration_two_years">
                             Dichiaro di non aver beneficiato, negli ultimi due anni antecedenti il 01/01/2025, di nessun differenziale stipendiale/progressione economica.
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Salva Bozza</button>
                @if($application)
                <a href="{{ route('application.preview', $application->id) }}" class="btn btn-info text-white">Vai ai Dettagli per Invio</a>
                @endif
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let counters = {
        disciplinary: {{ $application ? $application->disciplinaryProceedings->count() : 0 }},
        seniority: {{ $application ? $application->seniorityRecords->count() : 0 }},
        titles: {{ $application ? $application->titles->count() : 0 }},
        trainings: {{ $application ? $application->trainings->count() : 0 }}
    };

    const areasList = @json($areas);
    const profilesList = @json($profiles);

    function getOptionsHtml(list) {
        return list.map(item => `<option value="${item}">${item}</option>`).join('');
    }

    function addRow(type) {
        let idx = counters[type]++;
        let html = '';
        
        if (type === 'disciplinary') {
            html = `
            <div class="row mb-3 border-bottom pb-2 item-row">
                <div class="col-md-3"><label>Data</label><input type="date" name="disciplinary[${idx}][data]" class="form-control"></div>
                <div class="col-md-8"><label>Oggetto</label><input type="text" name="disciplinary[${idx}][oggetto]" class="form-control"></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
            </div>`;
        } else if (type === 'seniority') {
            html = `
            <div class="row mb-3 border-bottom pb-2 item-row">
                <div class="col-md-3"><label>Esperienza Come</label>
                    <select name="seniority[${idx}][esperienza_come]" class="form-select">
                        <option value="">Seleziona</option>
                        ${getOptionsHtml(profilesList)}
                    </select>
                </div>
                <div class="col-md-3"><label>Area</label>
                    <select name="seniority[${idx}][nell_area]" class="form-select">
                        <option value="">Seleziona</option>
                        ${getOptionsHtml(areasList)}
                    </select>
                </div>
                <div class="col-md-2"><label>Dal</label><input type="date" name="seniority[${idx}][dal]" class="form-control"></div>
                <div class="col-md-2"><label>Al</label><input type="date" name="seniority[${idx}][al]" class="form-control"></div>
                <div class="col-md-2"><label>Ente</label><input type="text" name="seniority[${idx}][ente]" class="form-control"></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
            </div>`;
        } else if (type === 'titles') {
            html = `
            <div class="row mb-3 border-bottom pb-2 item-row">
                <div class="col-md-4"><label>Descrizione</label><input type="text" name="titles[${idx}][descrizione]" class="form-control"></div>
                <div class="col-md-2"><label>Durata</label><input type="text" name="titles[${idx}][durata]" class="form-control"></div>
                <div class="col-md-3"><label>Istituto</label><input type="text" name="titles[${idx}][istituto]" class="form-control"></div>
                <div class="col-md-2"><label>Data</label><input type="date" name="titles[${idx}][data_conseguimento]" class="form-control"></div>
                <div class="col-md-1"><label>Attinente</label><div class="mt-4"><input type="checkbox" name="titles[${idx}][attinente]" class="form-check-input"></div></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
            </div>`;
        } else if (type === 'trainings') {
            html = `
            <div class="row mb-3 border-bottom pb-2 item-row">
                <div class="col-md-4"><label>Descrizione</label><input type="text" name="trainings[${idx}][descrizione]" class="form-control"></div>
                <div class="col-md-2"><label>Durata (ore)</label><input type="number" name="trainings[${idx}][durata_ore]" class="form-control"></div>
                <div class="col-md-3"><label>Istituto</label><input type="text" name="trainings[${idx}][istituto]" class="form-control"></div>
                <div class="col-md-2"><label>Data</label><input type="date" name="trainings[${idx}][data_conseguimento]" class="form-control"></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></div>
            </div>`;
        }
        
        document.getElementById('container-' + type).insertAdjacentHTML('beforeend', html);
    }

    function removeRow(btn) {
        btn.closest('.item-row').remove();
    }

    function toggleWorkTime(id) {
        const tp = document.getElementById('tp');
        const tpart = document.getElementById('tpart');
        const percent = document.getElementById('percent_part');

        if (id === 'tp') {
            if (tp.checked) {
                tpart.checked = false;
                percent.value = '';
                percent.required = false;
                percent.disabled = true;
            }
        } else if (id === 'tpart') {
            if (tpart.checked) {
                tp.checked = false;
                percent.disabled = false;
                percent.required = true;
            } else {
                percent.value = '';
                percent.required = false;
                percent.disabled = true;
            }
        }
    }
    
    // Init state
    document.addEventListener('DOMContentLoaded', function() {
        const tpart = document.getElementById('tpart');
        const percent = document.getElementById('percent_part');
        if (tpart.checked) {
            percent.disabled = false;
            percent.required = true;
        } else {
            percent.disabled = true;
        }
    });
</script>
@endpush
@endsection
