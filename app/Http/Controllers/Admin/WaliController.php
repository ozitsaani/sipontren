<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class WaliController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'orang_tua')
            ->with('santris')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        $walis = $query->paginate(10)->withQueryString();

        return view('admin.wali.index', compact('walis'));
    }

    public function create()
    {
        $santris = Santri::with('kelasMadrasah')
            ->orderBy('nama_santri')
            ->get();

        return view('admin.wali.create', compact('santris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'hubungan' => ['required', 'in:ayah,ibu,wali'],
            'santri_ids' => ['required', 'array'],
            'santri_ids.*' => ['exists:santris,id'],
        ]);

        $wali = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'orang_tua',
        ]);

        $syncData = [];

        foreach ($request->santri_ids as $santriId) {
            $syncData[$santriId] = [
                'hubungan' => $request->hubungan,
            ];
        }

        $wali->santris()->sync($syncData);

        return redirect()
            ->route('admin.wali.index')
            ->with('success', 'Data wali berhasil ditambahkan.');
    }

    public function edit(User $wali)
    {
        if ($wali->role !== 'orang_tua') {
            abort(404);
        }

        $santris = Santri::with('kelasMadrasah')
            ->orderBy('nama_santri')
            ->get();

        $selectedSantriIds = $wali->santris()->pluck('santris.id')->toArray();

        $hubungan = optional($wali->santris()->first()?->pivot)->hubungan ?? 'wali';

        return view('admin.wali.edit', compact(
            'wali',
            'santris',
            'selectedSantriIds',
            'hubungan'
        ));
    }

    public function update(Request $request, User $wali)
    {
        if ($wali->role !== 'orang_tua') {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($wali->id),
            ],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
            'hubungan' => ['required', 'in:ayah,ibu,wali'],
            'santri_ids' => ['required', 'array'],
            'santri_ids.*' => ['exists:santris,id'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => 'orang_tua',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $wali->update($data);

        $syncData = [];

        foreach ($request->santri_ids as $santriId) {
            $syncData[$santriId] = [
                'hubungan' => $request->hubungan,
            ];
        }

        $wali->santris()->sync($syncData);

        return redirect()
            ->route('admin.wali.index')
            ->with('success', 'Data wali berhasil diperbarui.');
    }

    public function destroy(User $wali)
    {
        if ($wali->role !== 'orang_tua') {
            abort(404);
        }

        $wali->delete();

        return redirect()
            ->route('admin.wali.index')
            ->with('success', 'Data wali berhasil dihapus.');
    }
}