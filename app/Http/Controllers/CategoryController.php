<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    private const ROUTE = 'admin.categories';

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Nama Kategori', 'type' => 'text', 'required' => true],
            ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'required' => false],
        ];
    }

    public function index(Request $request): View
    {
        $items = Category::withCount('reports')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('master.index', [
            'title' => 'Kategori Kerusakan',
            'routePrefix' => self::ROUTE,
            'columns' => ['name' => 'Nama', 'description' => 'Deskripsi', 'reports_count' => 'Jumlah Laporan'],
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('master.form', [
            'title' => 'Tambah Kategori',
            'routePrefix' => self::ROUTE,
            'fields' => $this->fields(),
            'item' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create($data);

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('master.form', [
            'title' => 'Ubah Kategori',
            'routePrefix' => self::ROUTE,
            'fields' => $this->fields(),
            'item' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update($data);

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->reports()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sudah dipakai oleh laporan.');
        }

        $category->delete();

        return redirect()->route(self::ROUTE . '.index')->with('success', 'Kategori dihapus.');
    }
}