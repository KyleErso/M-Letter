@extends('layouts.app')

@section('content')
<div class="container mt-4">

<div class="container mt-4">
  <!-- Header Dashboard Kaprodi -->
  <div class="bg-navy-gradient text-white rounded-3 p-4 mb-4 shadow">
    <div class="d-flex flex-column gap-3">
        <!-- Judul dan Deskripsi -->
        <div>
            <h4 class="mb-1 fw-semibold">
                <i class="bi bi-person-gear me-2"></i>Dashboard Kaprodi
            </h4>
            <p class="mb-0 small opacity-75">Manajemen Persetujuan Surat</p>
        </div>

        <!-- Informasi Kaprodi -->
        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle fs-5"></i>
                <div>
                    <p class="mb-0 small opacity-75">Nama Kaprodi</p>
                    <p class="mb-0 fw-medium">{{ auth()->user()->name ?? 'Kaprodi' }}</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-building fs-5"></i>
                <div>
                    <p class="mb-0 small opacity-75">Fakultas/Prodi</p>
                    <p class="mb-0 fw-medium">
                        {{ optional($kaprodi->prodi->fakultas)->nama_fakultas ?? 'Belum diatur' }} /
                        {{ $kaprodi->prodi->nama_prodi ?? 'Belum diatur' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>




    <!-- Section: Pengajuan Menunggu Persetujuan -->
<div class="card shadow-sm rounded-4 mb-4">
    <div class="card-header bg-warning-subtle py-3 rounded-top-4">
        <h5 class="card-title mb-0 fw-bold">
            <i class="bi bi-clock-history me-2"></i>Daftar Pengajuan Surat Menunggu Persetujuan
        </h5>
    </div>
    <div class="card-body p-0">
        @if($pendingPengajuans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">Tidak ada pengajuan menunggu persetujuan</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID Pengajuan</th>
                            <th>Nama Mahasiswa</th>
                            <th>NRP</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal Pengajuan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingPengajuans as $pengajuan)
                        <tr>
                            <td class="ps-4">{{ $pengajuan->id_pengajuan }}</td>
                            <td>{{ $pengajuan->mahasiswa->user->name ?? '-' }}</td>
                            <td>{{ $pengajuan->mahasiswa->id ?? '-' }}</td>
                            <td>{{ $pengajuan->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                            <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill me-2"
                                    data-bs-toggle="modal" data-bs-target="#detailModal{{ $pengajuan->id_pengajuan }}">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </button>

                                <!-- Tombol Approve -->
                                <form action="{{ route('kaprodi.approve', $pengajuan->id_pengajuan) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-success btn-sm rounded-pill me-2" onclick="return confirm('Setujui pengajuan ini?')">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </button>
                                </form>

                                <!-- Tombol Reject: Trigger Modal -->
                                <button class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pengajuan->id_pengajuan }}">
                                    <i class="bi bi-x-circle me-1"></i>Tolak
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Reject -->
                        <div class="modal fade" id="rejectModal{{ $pengajuan->id_pengajuan }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $pengajuan->id_pengajuan }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('kaprodi.reject', $pengajuan->id_pengajuan) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel{{ $pengajuan->id_pengajuan }}">
                                                Alasan Penolakan
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="alasan_penolakan_{{ $pengajuan->id_pengajuan }}" class="form-label">
                                                    Alasan Penolakan
                                                </label>
                                                <textarea name="alasan_penolakan" id="alasan_penolakan_{{ $pengajuan->id_pengajuan }}" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                Tolak Pengajuan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Reject -->
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination untuk Pending -->
           
            <nav aria-label="Page navigation for pending" class="mt-3">
                <ul class="pagination pagination-sm justify-content-center">
                    {{-- Tombol Sebelumnya --}}
                    <li class="page-item {{ $pendingPengajuans->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link bg-transparent" href="{{ $pendingPengajuans->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    {{-- Nomor Halaman --}}
                    @for ($i = 1; $i <= $pendingPengajuans->lastPage(); $i++)
                        <li class="page-item {{ $pendingPengajuans->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link text-white bg-light{{ $pendingPengajuans->currentPage() == $i ? 'fw-bold' : '' }}"
                            href="{{ $pendingPengajuans->url($i) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor

                    {{-- Tombol Selanjutnya --}}
                    <li class="page-item {{ $pendingPengajuans->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link bg-transparent" href="{{ $pendingPengajuans->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        @endif
    </div>
</div>


    <!-- Section: Pengajuan Disetujui -->
    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-header bg-success-subtle py-3 rounded-top-4">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-check-circle me-2"></i>Daftar Pengajuan Surat Disetujui</h5>
        </div>
        <div class="card-body p-0">
            @if($approvedPengajuans->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Tidak ada pengajuan disetujui</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">ID Pengajuan</th>
                                <th>Nama Mahasiswa</th>
                                <th>NRP</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Disetujui Pada</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedPengajuans as $pengajuan)
                            <tr>
                                <td class="ps-4">{{ $pengajuan->id_pengajuan }}</td>
                                <td>{{ $pengajuan->mahasiswa->user->name ?? '-' }}</td>
                                <td>{{ $pengajuan->mahasiswa->id ?? '-' }}</td>
                                <td>{{ $pengajuan->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                                <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                                <td>{{ $pengajuan->tanggal_persetujuan ? \Carbon\Carbon::parse($pengajuan->tanggal_persetujuan)->format('d/m/Y H:i') : '-' }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill"
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $pengajuan->id_pengajuan }}">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </button>
                                        @if ($pengajuan->surat_file)
                                            <a href="{{ asset('storage/' . $pengajuan->surat_file->file_path) }}"
                                               class="btn btn-sm btn-success rounded-pill" target="_blank">
                                                <i class="bi bi-download me-1"></i>Unduh
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination untuk Approved -->
                <nav aria-label="Page navigation for approved" class="mt-3">
                <ul class="pagination pagination-sm justify-content-center">
                    {{-- Tombol Sebelumnya --}}
                    <li class="page-item {{ $approvedPengajuans->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link bg-transparent" href="{{ $approvedPengajuans->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    {{-- Nomor Halaman --}}
                    @for ($i = 1; $i <= $approvedPengajuans->lastPage(); $i++)
                        <li class="page-item {{ $approvedPengajuans->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link text-white bg-light{{ $approvedPengajuans->currentPage() == $i ? 'fw-bold' : '' }}"
                            href="{{ $approvedPengajuans->url($i) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor

                    {{-- Tombol Selanjutnya --}}
                    <li class="page-item {{ $approvedPengajuans->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link bg-transparent" href="{{ $approvedPengajuans->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

            @endif
        </div>
    </div>

    <!-- Section: Pengajuan Ditolak -->
    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-header bg-danger-subtle py-3 rounded-top-4">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-x-circle me-2"></i>Daftar Pengajuan Surat Ditolak</h5>
        </div>
        <div class="card-body p-0">
            @if($rejectedPengajuans->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Tidak ada pengajuan ditolak</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">ID Pengajuan</th>
                                <th>Nama Mahasiswa</th>
                                <th>NRP</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Pengajuan</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedPengajuans as $pengajuan)
                            <tr>
                                <td class="ps-4">{{ $pengajuan->id_pengajuan }}</td>
                                <td>{{ $pengajuan->mahasiswa->user->name ?? '-' }}</td>
                                <td>{{ $pengajuan->mahasiswa->id ?? '-' }}</td>
                                <td>{{ $pengajuan->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                                <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#detailModal{{ $pengajuan->id_pengajuan }}">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination untuk Rejected -->
                <nav aria-label="Page navigation for rejected" class="mt-3">
                  <ul class="pagination justify-content-center">
                    <li class="page-item {{ $rejectedPengajuans->onFirstPage() ? 'disabled' : '' }}">
                      <a class="page-link bg-transparent" href="{{ $rejectedPengajuans->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    @for($i = 1; $i <= $rejectedPengajuans->lastPage(); $i++)
                      <li class="page-item {{ $rejectedPengajuans->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $rejectedPengajuans->url($i) }}">{{ $i }}</a>
                      </li>
                    @endfor
                    <li class="page-item {{ $rejectedPengajuans->hasMorePages() ? '' : 'disabled' }}">
                      <a class="page-link bg-transparent" href="{{ $rejectedPengajuans->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
            @endif
        </div>
    </div>

{{-- Loop semua status --}}
@foreach (['pendingPengajuans', 'approvedPengajuans', 'rejectedPengajuans'] as $statusKey)
    @foreach ($$statusKey as $item)
        <div class="modal fade" id="detailModal{{ $item->id_pengajuan }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border: none; box-shadow: 0 4px 24px rgba(0,0,0,0.1);">
                    <div class="modal-header" style="border: none; padding: 1.5rem 1.5rem 0.5rem;">
                        <div style="width: 100%;">
                            <h5 class="modal-title" style="display: flex; align-items: center; gap: 0.75rem; font-weight: 700; margin-bottom: 0.5rem;">
                                <i class="bi bi-file-earmark-text-fill" style="color: #4a90e2; font-size: 1.5rem;"></i>
                                <span>Detail Pengajuan #{{ $item->id_pengajuan }}</span>
                            </h5>
                            <div style="display: flex; gap: 1rem; color: #6c757d; font-size: 0.875rem;">
                                <span style="display: flex; align-items: center; gap: 0.25rem;">
                                    <i class="bi bi-calendar" style="font-size: 0.9rem;"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d F Y H:i') }}
                                </span>
                                <span style="display: flex; align-items: center; gap: 0.25rem;">
                                    <i class="bi bi-tag" style="font-size: 0.9rem;"></i>
                                    {{ $item->jenisSurat->nama_jenis_surat ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body" style="padding: 1.5rem;">
                        @if($item->detail)
                            <div>
                                @switch($item->id_jenis_surat)
                                    @case(1)
                                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                                <i class="bi bi-123" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                                <div>
                                                    <div style="font-size: 0.875rem; color: #6c757d;">Semester</div>
                                                    <div style="font-weight: 500;">{{ $item->detail->semester ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                                <i class="bi bi-geo-alt" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                                <div>
                                                    <div style="font-size: 0.875rem; color: #6c757d;">Alamat</div>
                                                    <div style="font-weight: 500;">{{ $item->detail->alamat ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div style="grid-column: span 2; display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                                <i class="bi bi-card-text" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                                <div>
                                                    <div style="font-size: 0.875rem; color: #6c757d;">Keperluan</div>
                                                    <div style="font-weight: 500;">{{ $item->detail->keperluan_pengajuan ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @break

                                    @case(2)
                                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                                <i class="bi bi-building" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                                <div>
                                                    <div style="font-size: 0.875rem; color: #6c757d;">Nama PT</div>
                                                    <div style="font-weight: 500;">{{ $item->detail->nama_pt ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                                <i class="bi bi-geo-alt" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                                <div>
                                                    <div style="font-size: 0.875rem; color: #6c757d;">Alamat PT</div>
                                                    <div style="font-weight: 500;">{{ $item->detail->alamat_pt ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                                <i class="bi bi-journal-text" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                                <div>
                                                    <div style="font-size: 0.875rem; color: #6c757d;">Mata Kuliah</div>
                                                    <div style="font-weight: 500;">
                                                        {{ $item->detail->nama_mata_kuliah ?? '-' }} 
                                                        ({{ $item->detail->kode_mata_kuliah ?? '-' }})
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="grid-column: span 2; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                                                    <i class="bi bi-people" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                                    <div style="width: 100%;">
                                                        <div style="font-size: 0.875rem; color: #6c757d; margin-bottom: 0.5rem;">Data Mahasiswa</div>
                                                        <pre style="margin: 0; white-space: pre-wrap; background: transparent; padding: 0.5rem; border-radius: 0.25rem; border: 1px solid #dee2e6;">
{{ $item->detail->data_mahasiswa ?? '-' }}
                                                        </pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @break

                                    @case(3)
                                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #fff3cd; color: #856404; border-radius: 0.5rem;">
                                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.25rem;"></i>
                                            <div style="font-size: 0.875rem;">Detail surat rekomendasi MBKM sedang dalam pengembangan</div>
                                        </div>
                                        @break

                                    @case(4)
                                        <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                            <i class="bi bi-journal-check" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                            <div>
                                                <div style="font-size: 0.875rem; color: #6c757d;">Keperluan LHS</div>
                                                <div style="font-weight: 500;">{{ $item->detail->keperluan_lhs ?? '-' }}</div>
                                            </div>
                                        </div>
                                        @break

                                    @case(5)
                                        <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 0.5rem;">
                                            <i class="bi bi-mortarboard" style="color: #4a90e2; font-size: 1.25rem; padding-top: 2px;"></i>
                                            <div>
                                                <div style="font-size: 0.875rem; color: #6c757d;">Tanggal Kelulusan</div>
                                                <div style="font-weight: 500;">
                                                    {{ $item->detail->tanggal_kelulusan ? \Carbon\Carbon::parse($item->detail->tanggal_kelulusan)->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        @break

                                    @default
                                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #fff3cd; color: #856404; border-radius: 0.5rem;">
                                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.25rem;"></i>
                                            <div style="font-size: 0.875rem;">Format detail tidak dikenali</div>
                                        </div>
                                @endswitch
                            </div>
                        @else
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.5rem;">
                                <i class="bi bi-file-earmark-excel-fill" style="font-size: 1.25rem;"></i>
                                <div style="font-size: 0.875rem;">Detail surat tidak ditemukan</div>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer" style="border: none; padding: 0 1.5rem 1.5rem;">
                        <button type="button" class="btn" style="border: 1px solid #dee2e6; color: #6c757d; padding: 0.5rem 1rem; border-radius: 0.75rem;" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach
   
<!-- Custom Styles -->
<style>
    :root {
        --primary: #4a90e2;
        --success: #28a745;
        --danger: #dc3545;
        --soft-bg: #f8f9fa;
        --navy: #2c3e50;
        --navy-gradient: linear-gradient(45deg, #2c3e50, #34495e);
    }
    
    /* Background dan warna teks */
    .bg-navy-gradient { background: var(--navy-gradient); }
    .text-navy { color: var(--navy); }
    .border-navy { border-color: var(--navy) !important; }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    }
 /* Custom warna subtle yang lebih terang */
 .bg-warning-subtle {
        background-color: #ffecb3 !important;
    }
    .bg-success-subtle {
        background-color: #c7f0c8 !important;
    }
    .bg-danger-subtle {
        background-color: #f1b0b7 !important;
    }
    .bg-info-subtle {
        background-color: #b3f2ff !important;
    }
    .avatar-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hover-list:hover {
        background-color: rgba(var(--primary), 0.03);
        transition: background-color 0.2s ease;
    }

    .pagination {
        --bs-pagination-padding-x: 0.75rem;
        --bs-pagination-padding-y: 0.375rem;
        --bs-pagination-font-size: 0.875rem;
        --bs-pagination-border-radius: 8px;
    }

    .page-link {
        border: none;
        margin: 0 2px;
    }

    .page-item.active .page-link {
        background-color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .rounded-4 { border-radius: 1rem !important; }
    .rounded-top-4 { border-top-left-radius: 1rem !important; border-top-right-radius: 1rem !important; }

    .table-hover tbody tr {
        border-bottom: 2px solid var(--soft-bg);
    }

    .btn {
        transition: all 0.2s ease;
    }

    .badge.rounded-pill {
        padding: 0.35em 0.65em;
    }
</style>
@endsection