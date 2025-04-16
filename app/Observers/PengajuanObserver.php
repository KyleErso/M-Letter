<?php

namespace App\Observers;

use App\Models\Pengajuan;
use App\Models\User;
use App\Mail\PengajuanBaruMail;
use Illuminate\Support\Facades\Mail;

class PengajuanObserver
{
    /**
     * Handle the Pengajuan "created" event.
     */
    public function created(Pengajuan $pengajuan): void
    {
        // Ambil semua pengguna dengan peran 'kaprodi'
        $kaprodis = User::where('role', 'kaprodi')->get();

        foreach ($kaprodis as $kaprodi) {
            // Kirim email ke setiap Kaprodi
            Mail::to($kaprodi->email)->send(new PengajuanBaruMail($pengajuan));
        }
    }

    // Metode lainnya seperti updated, deleted, dll.
}
