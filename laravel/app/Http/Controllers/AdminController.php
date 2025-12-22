<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Setting;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        // Sent applications (invio definitivo)
        $sentApplications = Application::with('user')
            ->where('status', 'submitted')
            ->orderBy('submission_date', 'desc')
            ->get();

        // Draft applications: get all drafts ordered by update time
        $allDrafts = Application::with('user')
            ->where('status', '!=', 'submitted')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Filter to keep only the latest draft per user
        $draftApplications = $allDrafts->unique('user_id');

        $start = Setting::where('key', 'start_date')->value('value');
        $end = Setting::where('key', 'end_date')->value('value');
        
        return view('admin.dashboard', compact('sentApplications', 'draftApplications', 'start', 'end'));
    }

    public function updateSettings(Request $request)
    {
        Setting::updateOrCreate(['key' => 'start_date'], ['value' => $request->start_date]);
        Setting::updateOrCreate(['key' => 'end_date'], ['value' => $request->end_date]);
        
        return back()->with('success', 'Date aggiornate.');
    }

    public function show(Application $application)
    {
        // Reuse preview for simplicity
        return app(ApplicationController::class)->preview($application);
    }

    public function export()
    {
        $apps = Application::with('user', 'seniorityRecords', 'titles')->get();
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=export.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($apps) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Matricola', 'Cognome', 'Nome', 'Status', 'Data Invio', 'Media Valutazioni']);
            
            foreach ($apps as $app) {
                fputcsv($file, [
                    $app->user->matricola,
                    $app->user->surname,
                    $app->user->name,
                    $app->status,
                    $app->submission_date,
                    $app->media_valutazioni
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
