@extends('layouts.app')

@section('content')
<div class="container mt-5">
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card shadow rounded-4 border-0">
            <div class="card-header bg-secondary text-white rounded-top-4 d-flex align-items-center">
               <i class="bi bi-journal-check me-2 fs-4"></i>
               <h5 class="mb-0">Form Pengajuan Surat Keterangan Aktif</h5>
            </div>
            <div class="card-body p-4 bg-light rounded-bottom-4">
               <form action="{{ route('mahasiswa.surat-keterangan-aktif') }}" method="POST">
                  @csrf
                  
                  <div class="mb-4">
                     <label for="semester" class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-1"></i> Semester
                     </label>
                     <select name="semester" id="semester"
                             class="form-select form-select-lg rounded-3 @error('semester') is-invalid @enderror" required>
                        <option value="">Pilih Semester</option>
                        @for($i = 1; $i <= 14; $i++)
                           <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                     </select>
                     @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>
                  
                  <div class="mb-4">
                     <label for="keperluan_pengajuan" class="form-label fw-semibold">
                        <i class="bi bi-pen-fill me-1"></i> Keperluan Pengajuan
                     </label>
                     <input type="text" name="keperluan_pengajuan" id="keperluan_pengajuan"
                            class="form-control form-control-lg rounded-3 @error('keperluan_pengajuan') is-invalid @enderror"
                            value="{{ old('keperluan_pengajuan') }}" placeholder="Contoh: Beasiswa, Syarat Magang" required>
                     @error('keperluan_pengajuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>
                  
                  <div class="d-flex justify-content-end">
                     <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary me-2 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                     </a>
                     <button type="submit" class="btn btn-dark rounded-pill">
                        <i class="bi bi-send me-1"></i> Ajukan
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
