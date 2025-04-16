@extends('layouts.app')

@section('content')
<div class="container mt-5">
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card shadow rounded-4 border-0">
            <div class="card-header bg-secondary text-white rounded-top-4 d-flex align-items-center">
               <i class="bi bi-mortarboard-fill me-2 fs-4"></i>
               <h5 class="mb-0">Form Pengajuan Surat Keterangan Lulus</h5>
            </div>
            <div class="card-body p-4 bg-light rounded-bottom-4">
               <form action="{{ route('mahasiswa.surat-kelulusan') }}" method="POST">
                  @csrf
                  
                  <div class="mb-4">
                     <label for="tanggal_kelulusan" class="form-label fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> Tanggal Kelulusan
                     </label>
                     <input type="date" class="form-control form-control-lg rounded-3 @error('tanggal_kelulusan') is-invalid @enderror"
                           name="tanggal_kelulusan" id="tanggal_kelulusan"
                           value="{{ old('tanggal_kelulusan') }}" required>
                     @error('tanggal_kelulusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="d-flex justify-content-end">
                     <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary me-2 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                     </a>
                     <button type="submit" class="btn btn-dark rounded-pill">
                        <i class="bi bi-send-check me-1"></i> Ajukan
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
