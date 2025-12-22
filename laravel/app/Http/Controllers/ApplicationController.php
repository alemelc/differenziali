<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Setting;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    private function checkWindow()
    {
        // If admin, always allowed (logic might vary, but request says Admin defines window for standard users)
        // Actually, requirement says "standard users can insert/edit in specific window".
        $start = Setting::where('key', 'start_date')->value('value');
        $end = Setting::where('key', 'end_date')->value('value');
        
        if (!$start && !$end) return true; // No restriction if not set
        
        $now = Carbon::now();
        $startDate = $start ? Carbon::parse($start)->startOfDay() : Carbon::minValue();
        $endDate = $end ? Carbon::parse($end)->endOfDay() : Carbon::maxValue(); // 12-31?
        
        return $now->between($startDate, $endDate);
    }

    public function dashboard()
    {
        $application = Application::where('user_id', Auth::id())->first();
        $canCreate = !$application && $this->checkWindow();
        $canEdit = $application && $application->status == 'draft' && $this->checkWindow();
        
        return view('dashboard', compact('application', 'canCreate', 'canEdit'));
    }

    public function create()
    {
        if (Application::where('user_id', Auth::id())->exists()) {
            return redirect()->route('dashboard');
        }
        if (!$this->checkWindow()) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Fuori periodo consentito.']);
        }
        
        return view('application.form', ['application' => null]);
    }

    public function store(Request $request)
    {
        if (!$this->checkWindow()) abort(403, 'Fuori periodo');
        
        $this->saveApplication($request, new Application());
        
        return redirect()->route('dashboard')->with('success', 'Domanda salvata in bozza.');
    }

    public function edit(Application $application)
    {
        if ($application->user_id !== Auth::id() && !Auth::user()->can('admin')) abort(403);
        if ($application->status == 'submitted') return redirect()->route('dashboard');
        // Admin bypasses window check? For now, let's keep it or allow admin. 
        // Assuming admin can edit anytime.
        if (!Auth::user()->can('admin') && !$this->checkWindow()) return redirect()->route('dashboard')->withErrors(['error' => 'Tempo scaduto.']);
        
        $application->load('disciplinaryProceedings', 'seniorityRecords', 'titles', 'trainings');
        
        return view('application.form', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        if ($application->user_id !== Auth::id() && !Auth::user()->can('admin')) abort(403);
        if ($application->status == 'submitted') abort(403);
        
        if (!Auth::user()->can('admin') && !$this->checkWindow()) abort(403, 'Tempo scaduto.');
        
        $this->saveApplication($request, $application);
        
        // If admin is editing, where should they go? Back to admin dashboard probably is better, 
        // but user dashboard is where the route points. 
        // Let's rely on standard redirect for now, maybe admin sees the user dashboard?
        // Actually the dashboard route shows "my" application. Admin might see their own (empty) app.
        // We might need to redirect differently for admin.
        if (Auth::user()->can('admin')) {
             return redirect()->route('admin.dashboard')->with('success', 'Domanda aggiornata (Admin).');
        }
        
        return redirect()->route('dashboard')->with('success', 'Domanda aggiornata.');
    }

    private function saveApplication(Request $request, Application $application)
    {
        $data = $request->validate([
            'area_differenziale' => 'required',
            'profilo_differenziale' => 'required',
            'percentuale_tempo_parziale' => 'required_if:tempo_parziale,on',
            'declaration_two_years' => 'required',
        ]);
        
        // Only set user_id if creating a NEW application.
        // If updating (Admin or User), user_id stays the same.
        if (!$application->exists) {
            $application->user_id = Auth::id();
        }
        
        $application->area_differenziale = $request->area_differenziale;
        $application->profilo_differenziale = $request->profilo_differenziale;
        $application->tempo_pieno = $request->has('tempo_pieno');
        $application->tempo_parziale = $request->has('tempo_parziale');
        $application->percentuale_tempo_parziale = $request->percentuale_tempo_parziale;
        $application->declaration_two_years = $request->has('declaration_two_years');
        $application->valutazione_2022 = $request->valutazione_2022;
        $application->valutazione_2023 = $request->valutazione_2023;
        $application->valutazione_2024 = $request->valutazione_2024;
        
        $sum = ($request->valutazione_2022 ?? 0) + ($request->valutazione_2023 ?? 0) + ($request->valutazione_2024 ?? 0);
        $count = 3; 
        $application->media_valutazioni = $sum / $count;
        
        $application->save();
        
        // Helper to clean dates
        $clean = function($data) {
            foreach ($data as $k => $v) {
                 if (in_array($k, ['dal', 'al', 'data', 'data_conseguimento']) && $v === null) continue;
                 if (in_array($k, ['dal', 'al', 'data', 'data_conseguimento']) && $v === '') $data[$k] = null;
            }
            return $data;
        };
        
        $application->disciplinaryProceedings()->delete();
        if ($request->disciplinary) {
            $items = array_map($clean, $request->disciplinary);
            $application->disciplinaryProceedings()->createMany($items);
        }
        
        $application->seniorityRecords()->delete();
        if ($request->seniority) {
            $items = array_map($clean, $request->seniority);
            $application->seniorityRecords()->createMany($items);
        }
        
        $application->titles()->delete();
        if ($request->titles) {
            $titles = $request->titles;
            foreach($titles as &$t) {
                $t['attinente'] = isset($t['attinente']);
                $t = $clean($t);
            }
            $application->titles()->createMany($titles);
        }
        
        $application->trainings()->delete();
        if ($request->trainings) {
            $items = array_map($clean, $request->trainings);
            $application->trainings()->createMany($items);
        }
    }

    public function preview(Application $application)
    {
        if ($application->user_id !== Auth::id() && !Auth::user()->can('admin')) abort(403);
        $application->load('user', 'disciplinaryProceedings', 'seniorityRecords', 'titles', 'trainings');
        return view('application.preview', compact('application'));
    }

    public function submit(Application $application)
    {
        if ($application->user_id !== Auth::id() && !Auth::user()->can('admin')) abort(403);
        $application->update([
            'status' => 'submitted',
            'submission_date' => now()
        ]);
        
        if (Auth::user()->can('admin')) {
             return redirect()->route('admin.dashboard')->with('success', 'Domanda inviata con successo (Admin)!');
        }
        return redirect()->route('dashboard')->with('success', 'Domanda inviata con successo!');
    }
}
