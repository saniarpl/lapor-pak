<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerController extends Controller
{
    private const ROUTE = 'admin.workers';

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Nama Petugas', 'type' => 'text', 'required' => true],
            ['name' => 'whatsapp_number', 'label' => 'Nomor WhatsApp (contoh: 6281234567890 atau 081234567890)', 'type' => 'text', 'required' => true],
        ];
    }

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:20', 'regex:/^(\+?62|0)[0-9]{8,13}$/'],
        ];
    }

    private function messages(): array
    {
        return ['whatsapp_number.regex' => 'Format nomor WhatsApp tidak valid. Gunakan awalan 62 atau 0.'];
    }

    public function index(Request $request): View
    {
        $items = Worker::withCount('reports')
            ->when($request->filled('q'), function ($q) use ($request) {
                $like = '%' . $request->input('q') . '%';
                $q->where('name', 'like', $like)->orWhere('whatsapp_number', 'like', $like);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('master.index', [
            'title' => 'Petugas',
            'routePrefix' => self::ROUTE,
            'columns' => ['name' => 'Nama', 'whatsapp_number' => 'Nomor WhatsApp', 'reports_count' => 'Jumlah Tugas'],
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('master.form', [
            'title' => 'Tambah Petugas',
            'routePrefix' => self::ROUTE,
            'fields' => $this->fields(),
            'item' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Worker::create($request->validate($this->rules(), $this->messages()));

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Petugas ditambahkan.');
    }

    public function edit(Worker $worker): View
    {
        return view('master.form', [
            'title' => 'Ubah Petugas',
            'routePrefix' => self::ROUTE,
            'fields' => $this->fields(),
            'item' => $worker,
        ]);
    }

    public function update(Request $request, Worker $worker): RedirectResponse
    {
        $worker->update($request->validate($this->rules(), $this->messages()));

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Petugas diperbarui.');
    }

    public function destroy(Worker $worker): RedirectResponse
    {
        // FK reports.worker_id memakai nullOnDelete: laporan lama tetap ada, petugas dikosongkan.
        $worker->delete();

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Petugas dihapus.');
    }
}