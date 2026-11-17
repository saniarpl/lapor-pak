<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\Urgency;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    /** Query laporan + filter. Siswa selalu dibatasi pada laporan miliknya sendiri. */
    private function filteredQuery(Request $request): Builder
    {
        $user = $request->user();
        $query = Report::with(['user', 'category', 'urgency', 'worker'])->latest();

        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $query->when($request->filled('q'), function (Builder $q) use ($request, $user) {
            $like = '%' . $request->input('q') . '%';
            $q->where(function (Builder $w) use ($like, $user) {
                $w->where('ticket_number', 'like', $like)
                    ->orWhere('location', 'like', $like)
                    ->orWhere('description', 'like', $like);

                if ($user->isAdmin()) {
                    $w->orWhereHas('user', function (Builder $u) use ($like) {
                        $u->where('name', 'like', $like)->orWhere('class_name', 'like', $like);
                    });
                }
            });
        });

        $query->when($request->filled('status'), fn (Builder $q) => $q->where('status', $request->input('status')));
        $query->when($request->filled('category_id'), fn (Builder $q) => $q->where('category_id', $request->input('category_id')));
        $query->when($request->filled('urgency_id'), fn (Builder $q) => $q->where('urgency_id', $request->input('urgency_id')));
        $query->when($request->filled('date_from'), fn (Builder $q) => $q->whereDate('created_at', '>=', $request->input('date_from')));
        $query->when($request->filled('date_to'), fn (Builder $q) => $q->whereDate('created_at', '<=', $request->input('date_to')));

        if ($user->isAdmin()) {
            $query->when($request->filled('worker_id'), fn (Builder $q) => $q->where('worker_id', $request->input('worker_id')));
        }

        return $query;
    }

    public function index(Request $request): View
    {
        return view('reports.index', [
            'reports' => $this->filteredQuery($request)->paginate(10)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'urgencies' => Urgency::orderBy('level')->get(),
            'workers' => $request->user()->isAdmin() ? Worker::orderBy('name')->get() : collect(),
            'statuses' => Report::statuses(),
        ]);
    }

    public function print(Request $request): View
    {
        return view('reports.print', [
            'reports' => $this->filteredQuery($request)->get(),
            'filters' => $request->only(['q', 'status', 'category_id', 'urgency_id', 'worker_id', 'date_from', 'date_to']),
        ]);
    }

    public function create(): View
    {
        return view('reports.create', [
            'categories' => Category::orderBy('name')->get(),
            'urgencies' => Urgency::orderBy('level')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'urgency_id' => ['required', 'exists:urgencies,id'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $path = $request->file('photo')->store('reports', 'public');

        $report = DB::transaction(function () use ($request, $data, $path) {
            $report = Report::create([
                'ticket_number' => Report::generateTicketNumber(),
                'user_id' => $request->user()->id,
                'category_id' => $data['category_id'],
                'urgency_id' => $data['urgency_id'],
                'location' => $data['location'],
                'description' => $data['description'],
                'photo' => $path,
                'status' => Report::STATUS_PENDING,
            ]);

            ReportHistory::create([
                'report_id' => $report->id,
                'admin_id' => null,
                'old_status' => null,
                'new_status' => Report::STATUS_PENDING,
                'note' => 'Laporan dibuat oleh siswa.',
            ]);

            return $report;
        });

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim. Nomor tiket: ' . $report->ticket_number);
    }

    public function show(Request $request, Report $report): View
    {
        $user = $request->user();

        // Siswa hanya boleh melihat laporan miliknya sendiri.
        abort_unless($user->isAdmin() || $report->user_id === $user->id, 403, 'Anda tidak dapat melihat laporan ini.');

        $report->load(['user', 'category', 'urgency', 'worker', 'histories.admin']);

        return view('reports.show', [
            'report' => $report,
            'workers' => $user->isAdmin() ? Worker::orderBy('name')->get() : collect(),
        ]);
    }

    /** Admin: ubah status sesuai alur (verifikasi/tolak/proses/selesai). */
    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'new_status' => ['required', Rule::in(Report::statuses())],
            'rejection_reason' => ['required_if:new_status,' . Report::STATUS_REJECTED, 'nullable', 'string', 'max:1000'],
            'repair_note' => ['nullable', 'string', 'max:2000'],
            'note' => ['nullable', 'string', 'max:1000'],
        ], [
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi.',
        ]);

        if (! in_array($data['new_status'], $report->allowedTransitions(), true)) {
            return back()->with('error', "Status tidak dapat diubah dari \"{$report->status}\" ke \"{$data['new_status']}\".");
        }

        DB::transaction(function () use ($request, $report, $data) {
            $old = $report->status;

            $update = ['status' => $data['new_status']];

            if ($data['new_status'] === Report::STATUS_REJECTED) {
                $update['rejection_reason'] = $data['rejection_reason'];
            }

            if (! empty($data['repair_note'])) {
                $update['repair_note'] = $data['repair_note'];
            }

            $report->update($update);

            $note = $data['new_status'] === Report::STATUS_REJECTED
                ? $data['rejection_reason']
                : ($data['note'] ?? $data['repair_note'] ?? null);

            ReportHistory::create([
                'report_id' => $report->id,
                'admin_id' => $request->user()->id,
                'old_status' => $old,
                'new_status' => $data['new_status'],
                'note' => $note,
            ]);
        });

        return back()->with('success', 'Status laporan diperbarui menjadi "' . $data['new_status'] . '".');
    }

    /** Admin: pilih petugas untuk laporan. */
    public function assignWorker(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'worker_id' => ['required', 'exists:workers,id'],
        ]);

        $worker = Worker::findOrFail($data['worker_id']);
        $report->update(['worker_id' => $worker->id]);

        ReportHistory::create([
            'report_id' => $report->id,
            'admin_id' => $request->user()->id,
            'old_status' => $report->status,
            'new_status' => $report->status,
            'note' => 'Tugas diberikan kepada petugas: ' . $worker->name . '.',
        ]);

        return back()->with('success', 'Petugas ditetapkan. Gunakan tombol WhatsApp untuk membagikan tugas.');
    }

    /** Admin: tambah/ubah catatan perbaikan. */
    public function updateRepairNote(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'repair_note' => ['required', 'string', 'max:2000'],
        ]);

        $report->update(['repair_note' => $data['repair_note']]);

        ReportHistory::create([
            'report_id' => $report->id,
            'admin_id' => $request->user()->id,
            'old_status' => $report->status,
            'new_status' => $report->status,
            'note' => 'Catatan perbaikan: ' . $data['repair_note'],
        ]);

        return back()->with('success', 'Catatan perbaikan disimpan.');
    }
}