@extends('layouts.app')

@section('content')
<div class="container mt-5">
   <div class="row justify-content-center">
      <div class="col-md-10">
         <div class="card shadow rounded-4 border-0 bg-body-secondary">
            <div class="card-header bg-secondary text-white rounded-top-4 d-flex align-items-center">
               <i class="bi bi-envelope-paper-fill me-2 fs-4"></i>
               <h5 class="mb-0">Form Pengajuan Surat Pengantar Tugas</h5>
            </div>
            <div class="card-body p-4 bg-body-secondary rounded-bottom-4">
               <form action="{{ route('mahasiswa.surat-pengantar-tugas') }}" method="POST">
                  @csrf

                  <div class="mb-3">
                     <label for="tujuan_surat" class="form-label fw-semibold">
                        <i class="bi bi-geo-alt-fill me-1"></i> Tujuan Surat
                     </label>
                     <input type="text" name="tujuan_surat" id="tujuan_surat"
                            class="form-control @error('tujuan_surat') is-invalid @enderror"
                            value="{{ old('tujuan_surat') }}"
                            placeholder="Contoh: Ibu Siti Mulyani" required>
                     @error('tujuan_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="nama_pt" class="form-label fw-semibold">Nama PT</label>
                     <input type="text" name="nama_pt" id="nama_pt"
                            class="form-control @error('nama_pt') is-invalid @enderror"
                            value="{{ old('nama_pt') }}"
                            placeholder="Contoh: PT Telkom Indonesia">
                     @error('nama_pt')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="alamat_pt" class="form-label fw-semibold">Alamat PT</label>
                     <textarea name="alamat_pt" id="alamat_pt" rows="3"
                               class="form-control @error('alamat_pt') is-invalid @enderror"
                               placeholder="Contoh: Jl. Japati No. 1, Bandung">{{ old('alamat_pt') }}</textarea>
                     @error('alamat_pt')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="nama_mata_kuliah" class="form-label fw-semibold">Nama Mata Kuliah</label>
                     <input type="text" name="nama_mata_kuliah" id="nama_mata_kuliah"
                            class="form-control @error('nama_mata_kuliah') is-invalid @enderror"
                            value="{{ old('nama_mata_kuliah') }}"
                            placeholder="Contoh: Manajemen Proyek TI">
                     @error('nama_mata_kuliah')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="kode_mata_kuliah" class="form-label fw-semibold">Kode Mata Kuliah</label>
                     <input type="text" name="kode_mata_kuliah" id="kode_mata_kuliah"
                            class="form-control @error('kode_mata_kuliah') is-invalid @enderror"
                            value="{{ old('kode_mata_kuliah') }}"
                            placeholder="Contoh: INF321">
                     @error('kode_mata_kuliah')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="semester" class="form-label fw-semibold">Semester</label>
                     <input type="text" name="semester" id="semester"
                            class="form-control @error('semester') is-invalid @enderror"
                            value="{{ old('semester') }}"
                            placeholder="Contoh: 5">
                     @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="data_mahasiswa" class="form-label fw-semibold">Data Mahasiswa</label>
                     <textarea name="data_mahasiswa" id="data_mahasiswa" rows="3"
                               class="form-control @error('data_mahasiswa') is-invalid @enderror"
                               placeholder="Contoh: Evan Kristian Pratama / 2372047 / Teknik Informatika">{{ old('data_mahasiswa') }}</textarea>
                     @error('data_mahasiswa')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="tujuan" class="form-label fw-semibold">Tujuan</label>
                     <input type="text" name="tujuan" id="tujuan"
                            class="form-control @error('tujuan') is-invalid @enderror"
                            value="{{ old('tujuan') }}"
                            placeholder="Contoh: Melaksanakan pengumpulan data untuk tugas akhir">
                     @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="topik" class="form-label fw-semibold">Topik</label>
                     <input type="text" name="topik" id="topik"
                            class="form-control @error('topik') is-invalid @enderror"
                            value="{{ old('topik') }}"
                            placeholder="Contoh: Analisis Proses Bisnis pada Divisi IT PT Telkom">
                     @error('topik')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="d-flex justify-content-end">
                     <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary me-2 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                     </a>
                     <button type="submit" class="btn btn-primary rounded-pill">
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
