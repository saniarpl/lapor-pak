<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\Urgency;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $raw = Report::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $statusCounts = [];
            foreach (Report::statuses() as $status) {
                $statusCounts[$status] = (int) ($raw[$status] ?? 0);
            }

            return view('dashboard.admin', [
                'total' => array_sum($statusCounts),
                'statusCounts' => $statusCounts,
                'byCategory' => Category::withCount('reports')->orderByDesc('reports_count')->get(),
                'byUrgency' => Urgency::withCount('reports')->orderBy('level')->get(),
                'recent' => Report::with(['user', 'category', 'urgency'])->latest()->limit(5)->get(),
                'studentCount' => User::where('role', User::ROLE_SISWA)->count(),
                'workerCount' => Worker::count(),
            ]);
        }

        $raw = Report::where('user_id', $user->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts = [];
        foreach (Report::statuses() as $status) {
            $statusCounts[$status] = (int) ($raw[$status] ?? 0);
        }

        return view('dashboard.siswa', [
            'total' => array_sum($statusCounts),
            'statusCounts' => $statusCounts,
            'recent' => Report::with(['category', 'urgency'])
                ->where('user_id', $user->id)->latest()->limit(5)->get(),
        ]);
    }
}