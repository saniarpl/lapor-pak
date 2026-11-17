<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    private function ensureStudent(User $student): User
    {
        abort_unless($student->role === User::ROLE_SISWA, 404);

        return $student;
    }

    public function index(Request $request): View
    {
        $students = User::where('role', User::ROLE_SISWA)
            ->withCount('reports')
            ->when($request->filled('q'), function ($q) use ($request) {
                $like = '%' . $request->input('q') . '%';
                $q->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                        ->orWhere('username', 'like', $like)
                        ->orWhere('class_name', 'like', $like);
                });
            })
            ->when($request->filled('class_name'), fn ($q) => $q->where('class_name', $request->input('class_name')))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'classes' => User::where('role', User::ROLE_SISWA)->whereNotNull('class_name')
                ->distinct()->orderBy('class_name')->pluck('class_name'),
        ]);
    }

    public function create(): View
    {
        return view('students.form', ['student' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'class_name' => ['required', 'string', 'max:50'],
        ]);

        // Password di-hash otomatis oleh cast 'hashed' pada model User.
        User::create($data + ['role' => User::ROLE_SISWA]);

        return redirect()->route('admin.students.index')->with('success', 'Akun siswa dibuat.');
    }

    public function edit(User $student): View
    {
        return view('students.form', ['student' => $this->ensureStudent($student)]);
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($student->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'class_name' => ['required', 'string', 'max:50'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Akun siswa diperbarui.');
    }

    public function destroy(User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        if ($student->reports()->exists()) {
            return back()->with('error', 'Akun siswa tidak dapat dihapus karena sudah memiliki laporan.');
        }

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Akun siswa dihapus.');
    }
}