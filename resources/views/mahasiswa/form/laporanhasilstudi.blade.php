@extends('layouts.app')

@section('content')
<div class="container mt-5">
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card shadow rounded-4 border-0">
            <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
               <i class="bi bi-file-earmark-text me-2 fs-4"></i>
               <h5 class="mb-0">Form Pengajuan Laporan Hasil Studi</h5>
            </div>
            <div class="card-body p-4 bg-light rounded-bottom-4">
               <form action="{{ route('mahasiswa.laporan-hasil-studi') }}" method="POST">
                  @csrf
                  <div class="mb-4">
                     <label for="keperluan_lhs" class="form-label fw-semibold">
                        <i class="bi bi-pencil-square me-1"></i>Keperluan Laporan Hasil Studi
                     </label>
                     <input type="text" name="keperluan_lhs" id="keperluan_lhs"
                            class="form-control form-control-lg rounded-3 @error('keperluan_lhs') is-invalid @enderror"
                            value="{{ old('keperluan_lhs') }}" placeholder="Contoh: Beasiswa, Lamaran Kerja, dll." required>
                     @error('keperluan_lhs')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="d-flex justify-content-end">
                     <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary me-2 rounded-pill">
                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                     </a>
                     <button type="submit" class="btn btn-primary rounded-pill">
                        <i class="bi bi-send-fill me-1"></i> Ajukan
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
