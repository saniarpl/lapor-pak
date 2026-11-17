<?php

namespace App\Http\Controllers;

use App\Models\Urgency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UrgencyController extends Controller
{
    private const ROUTE = 'admin.urgencies';

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Nama Urgensi', 'type' => 'text', 'required' => true],
            ['name' => 'level', 'label' => 'Tingkat (1 = terendah, makin besar makin mendesak)', 'type' => 'number', 'required' => true],
        ];
    }

    public function index(Request $request): View
    {
        $items = Urgency::withCount('reports')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->orderBy('level')
            ->paginate(10)
            ->withQueryString();

        return view('master.index', [
            'title' => 'Tingkat Urgensi',
            'routePrefix' => self::ROUTE,
            'columns' => ['name' => 'Nama', 'level' => 'Tingkat', 'reports_count' => 'Jumlah Laporan'],
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('master.form', [
            'title' => 'Tambah Urgensi',
            'routePrefix' => self::ROUTE,
            'fields' => $this->fields(),
            'item' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:urgencies,name'],
            'level' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        Urgency::create($data);

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Urgensi ditambahkan.');
    }

    public function edit(Urgency $urgency): View
    {
        return view('master.form', [
            'title' => 'Ubah Urgensi',
            'routePrefix' => self::ROUTE,
            'fields' => $this->fields(),
            'item' => $urgency,
        ]);
    }

    public function update(Request $request, Urgency $urgency): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('urgencies', 'name')->ignore($urgency->id)],
            'level' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $urgency->update($data);

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Urgensi diperbarui.');
    }

    public function destroy(Urgency $urgency): RedirectResponse
    {
        if ($urgency->reports()->exists()) {
            return back()->with('error', 'Urgensi tidak dapat dihapus karena sudah dipakai oleh laporan.');
        }

        $urgency->delete();

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Urgensi dihapus.');
    }
}