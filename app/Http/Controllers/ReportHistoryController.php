<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportHistoryController extends Controller
{
    /** Admin: seluruh riwayat perubahan status. */
    public function index(Request $request): View
    {
        $histories = ReportHistory::with(['report', 'admin'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $like = '%' . $request->input('q') . '%';
                $query->where('note', 'like', $like)
                    ->orWhereHas('report', fn ($r) => $r->where('ticket_number', 'like', $like));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('new_status', $request->input('status')))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('histories.index', [
            'histories' => $histories,
            'statuses' => Report::statuses(),
        ]);
    }
}