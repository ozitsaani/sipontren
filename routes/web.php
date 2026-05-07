<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('orang-tua.dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('kelas-madrasah', \App\Http\Controllers\Admin\KelasMadrasahController::class);
        Route::resource('santri', \App\Http\Controllers\Admin\SantriController::class);
        Route::resource('wali', \App\Http\Controllers\Admin\WaliController::class);
        
        Route::get('absensi', [\App\Http\Controllers\Admin\AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('absensi', [\App\Http\Controllers\Admin\AbsensiController::class, 'store'])->name('absensi.store');

        Route::get('rekap-absensi', [\App\Http\Controllers\Admin\RekapAbsensiController::class, 'index'])->name('rekap-absensi.index');

        Route::get('pengaturan-spp', [\App\Http\Controllers\Admin\PengaturanSppController::class, 'index'])->name('pengaturan-spp.index');
        Route::post('pengaturan-spp', [\App\Http\Controllers\Admin\PengaturanSppController::class, 'store'])->name('pengaturan-spp.store');

        Route::get('tagihan-spp', [\App\Http\Controllers\Admin\TagihanSppController::class, 'index'])->name('tagihan-spp.index');
        Route::post('tagihan-spp/generate', [\App\Http\Controllers\Admin\TagihanSppController::class, 'generate'])->name('tagihan-spp.generate');

        Route::get('verifikasi-pembayaran', [\App\Http\Controllers\Admin\VerifikasiPembayaranController::class, 'index'])->name('verifikasi-pembayaran.index');

        Route::post('verifikasi-pembayaran/{pembayaran}/terima', [\App\Http\Controllers\Admin\VerifikasiPembayaranController::class, 'terima'])->name('verifikasi-pembayaran.terima');

        Route::post('verifikasi-pembayaran/{pembayaran}/tolak', [\App\Http\Controllers\Admin\VerifikasiPembayaranController::class, 'tolak'])->name('verifikasi-pembayaran.tolak');

        Route::get('notifikasi', [\App\Http\Controllers\Admin\NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::resource('pelanggaran', \App\Http\Controllers\Admin\PelanggaranController::class);

    });

Route::middleware(['auth', 'verified', 'role:orang_tua'])
    ->prefix('orang-tua')
    ->name('orang-tua.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('orang-tua.dashboard');
        })->name('dashboard');

        Route::get('rekap-absensi', [\App\Http\Controllers\OrangTua\RekapAbsensiController::class, 'index'])->name('rekap-absensi.index');
        Route::get('pembayaran-spp', [\App\Http\Controllers\OrangTua\PembayaranSppController::class, 'index'])->name('pembayaran-spp.index');
        Route::post('pembayaran-spp', [\App\Http\Controllers\OrangTua\PembayaranSppController::class, 'store'])->name('pembayaran-spp.store');

        Route::get('riwayat-pembayaran', [\App\Http\Controllers\OrangTua\RiwayatPembayaranController::class, 'index'])->name('riwayat-pembayaran.index');
        Route::get('notifikasi', [\App\Http\Controllers\OrangTua\NotifikasiController::class, 'index'])
        ->name('notifikasi.index');

        Route::post('notifikasi/tandai-semua-dibaca', [\App\Http\Controllers\OrangTua\NotifikasiController::class, 'tandaiSemuaDibaca'])
         ->name('notifikasi.tandai-semua-dibaca');

        Route::post('notifikasi/{notifikasi}/dibaca', [\App\Http\Controllers\OrangTua\NotifikasiController::class, 'tandaiDibaca'])
        ->name('notifikasi.dibaca');
        Route::get('pelanggaran-anak', [\App\Http\Controllers\OrangTua\PelanggaranAnakController::class, 'index'])
        ->name('pelanggaran-anak.index');

        });

        

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';