@extends('layouts.app')

@section('content')
<div class="container mt-4">
<div class="bg-navy-gradient text-white rounded-3 p-4 mb-4 shadow position-relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="position-absolute top-0 end-0 w-50 h-100 bg-white opacity-05" 
         style="clip-path: polygon(100% 0, 100% 100%, 25% 0)"></div>
    
    <div class="position-relative z-1">
        <div class="d-flex justify-content-between align-items-center">
            <div class="flex-grow-1">
                <h2 class="mb-1 fw-bold display-6">
                    <i class="bi bi-file-text me-3"></i>Daftar Pengajuan Surat
                </h2>
                <p class="mb-0 lead opacity-75">Riwayat Lengkap Status Pengajuan Surat Mahasiswa</p>
            </div>
            
            <!-- Floating Action Button -->
            <button class="btn btn-xl rounded-pill px-4 py-3 shadow-lg bg-white text-navy btn-hover-glow"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalPengajuan">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill fs-4 me-2 text-navy"></i>
                    <span class="fw-semibold text-navy">Buat Pengajuan</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Decorative Line -->
    <div class="position-absolute bottom-0 start-0 w-100">
        <div class="border-bottom border-white border-2 opacity-25" style="width: 200px"></div>
    </div>
</div>

    @php
        $pending  = $pengajuan->where('status', 'menunggu');
        $approved = $pengajuan->where('status', 'disetujui');
        $rejected = $pengajuan->where('status', 'ditolak');
    @endphp

    <!-- Pengajuan Menunggu -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-warning-subtle py-3 border-0 rounded-top-4">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-clock-history me-2"></i>Pengajuan Menunggu
                    </h5>
                </div>
                <!-- ... (isi tabel tetap sama) ... -->
                <div class="card-body p-0">
                    @if($pending->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Tidak ada pengajuan menunggu</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4"></th>
                                        <th>Tanggal</th>
                                        <th>Jenis Surat</th>
                                        <th>Tahun Ajaran</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pending as $item)
                                        <tr>
                                            <td class="ps-4">{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                            <td>{{ $item->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                                            <td>{{ $item->tahunAjaran->kode_tahun ?? '-' }}</td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal{{ $item->id_pengajuan }}">
                                                    <i class="bi bi-eye me-1"></i>Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Disetujui -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-success-subtle py-3 border-0 rounded-top-4">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-check-circle me-2"></i>Pengajuan Disetujui
                    </h5>
                </div>
                <!-- ... (isi tabel tetap sama) ... -->
                <div class="card-body p-0">
                    @if($approved->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Tidak ada pengajuan disetujui</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4"></th>
                                        <th>Tanggal</th>
                                        <th>Jenis Surat</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Disetujui Pada</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approved as $item)
                                        <tr>
                                            <td class="ps-4">{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                            <td>{{ $item->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                                            <td>{{ $item->tahunAjaran->kode_tahun ?? '-' }}</td>
                                            <td>{{ $item->tanggal_persetujuan ? \Carbon\Carbon::parse($item->tanggal_persetujuan)->format('d/m/Y H:i') : '-' }}</td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <button class="btn btn-sm btn-outline-secondary rounded-pill" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailModal{{ $item->id_pengajuan }}">
                                                        <i class="bi bi-eye me-1"></i>Detail
                                                    </button>
                                                    @if ($item->surat_file)
                                                        <a href="{{ asset('storage/' . $item->surat_file->file_path) }}" 
                                                           class="btn btn-sm btn-success rounded-pill"
                                                           target="_blank">
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Ditolak -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-danger-subtle py-3 border-0 rounded-top-4">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-x-circle me-2"></i>Pengajuan Ditolak
                    </h5>
                </div>
                <!-- ... (isi tabel tetap sama) ... -->
                <div class="card-body p-0">
                    @if($rejected->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Tidak ada pengajuan ditolak</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4"></th>
                                        <th>Tanggal</th>
                                        <th>Jenis Surat</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Alasan</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejected as $item)
                                        <tr>
                                            <td class="ps-4">{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                            <td>{{ $item->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                                            <td>{{ $item->tahunAjaran->kode_tahun ?? '-' }}</td>
                                            <td class="text-truncate" style="max-width: 200px;">{{ $item->alasan_penolakan ?? '-' }}</td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal{{ $item->id_pengajuan }}">
                                                    <i class="bi bi-eye me-1"></i>Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Modal -->
<div class="modal fade" id="modalPengajuan" tabindex="-1" aria-labelledby="modalPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('mahasiswa.pengajuan.redirect') }}" method="GET">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="modalPengajuanLabel">
                        <i class="bi bi-file-earmark-plus me-2"></i>Jenis Pengajuan
                    </h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Pilih jenis surat yang ingin diajukan</label>
                        <select class="form-select rounded-3" name="jenis_surat" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            <option value="1">Surat Keterangan Aktif</option>
                            <option value="2">Surat Pengantar Tugas Kuliah</option>
                            <option value="4">Laporan Hasil Studi</option>
                            <option value="5">Surat Keterangan Lulus</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3">Lanjutkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Detail Modals -->
@foreach($pengajuan as $item)
<div class="modal fade" id="detailModal{{ $item->id_pengajuan }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Detail Pengajuan {{ $item->id_pengajuan }}</h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <!-- Informasi Mahasiswa -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Nama Mahasiswa</label>
                        <p class="fw-normal">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">NRP</label>
                        <p class="fw-normal">{{ Auth::user()->id }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Program Studi</label>
                        <p class="fw-normal">{{ $item->mahasiswa->prodi->nama_prodi ?? 'Belum diatur' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Fakultas</label>
                        <p class="fw-normal">{{ optional($item->mahasiswa->prodi->fakultas)->nama_fakultas ?? 'Belum diatur' }}</p>
                    </div>
                </div>

                <hr>

                <!-- Informasi Pengajuan -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Tanggal Pengajuan</label>
                        <p class="fw-normal">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d F Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Jenis Surat</label>
                        <p class="fw-normal">{{ $item->jenisSurat->nama_jenis_surat ?? '-' }}</p>
                    </div>
                </div>

                <hr>

                <!-- Tampilkan detail surat berdasarkan jenisnya -->
                @if($item->detail)
                    @switch($item->id_jenis_surat)
                        @case(1)
                            <p><strong>Semester:</strong> {{ $item->detail->semester ?? '-' }}</p>
                            <p><strong>Alamat:</strong> {{ $item->detail->alamat ?? '-' }}</p>
                            <p><strong>Keperluan Pengajuan:</strong> {{ $item->detail->keperluan_pengajuan ?? '-' }}</p>
                            @break

                        @case(2)
                            <p><strong>Tujuan Surat:</strong> {{ $item->detail->tujuan_surat ?? '-' }}</p>
                            <p><strong>Nama PT:</strong> {{ $item->detail->nama_pt ?? '-' }}</p>
                            <p><strong>Alamat PT:</strong> {{ $item->detail->alamat_pt ?? '-' }}</p>
                            <p><strong>Nama Mata Kuliah:</strong> {{ $item->detail->nama_mata_kuliah ?? '-' }}</p>
                            <p><strong>Kode Mata Kuliah:</strong> {{ $item->detail->kode_mata_kuliah ?? '-' }}</p>
                            <p><strong>Semester:</strong> {{ $item->detail->semester ?? '-' }}</p>
                            <p><strong>Data Mahasiswa:</strong> {!! nl2br(e($item->detail->data_mahasiswa)) !!}</p>
                            <p><strong>Tujuan:</strong> {{ $item->detail->tujuan ?? '-' }}</p>
                            <p><strong>Topik:</strong> {{ $item->detail->topik ?? '-' }}</p>
                            @break

                        @case(3)
                            <p><strong>Detail Surat Rekomendasi MBKM:</strong> Data detail belum diimplementasikan.</p>
                            @break

                        @case(4)
                            <p><strong>Keperluan LHS:</strong> {{ $item->detail->keperluan_lhs ?? '-' }}</p>
                            @break

                        @case(5)
                            <p><strong>Tanggal Kelulusan:</strong> {{ $item->detail->tanggal_kelulusan ?? '-' }}</p>
                            @break

                        @default
                            <p>Detail tidak tersedia.</p>
                    @endswitch
                @else
                    <p>Detail surat tidak ditemukan.</p>
                @endif
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
   :root {
        --navy: #2c3e50;
        --navy-gradient: linear-gradient(45deg, #2c3e50, #34495e);
    }

    .bg-navy-gradient { background: var(--navy-gradient); }
    .text-navy { color: var(--navy); }
    .border-navy { border-color: var(--navy) !important; }
    
    .btn-navy {
        background: var(--navy);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-navy:hover {
        background: #34495e;
        transform: translateY(-1px);
    }
    
    .card-header.bg-navy-gradient {
        border-bottom: 2px solid rgba(255,255,255,0.2);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(44, 62, 80, 0.05);
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

    .btn-hover-glow {
        transition: all 0.3s ease;
        border: 2px solid rgba(255,255,255,0.2);
        background: linear-gradient(45deg, #ffffff, #f8f9fa);
    }

    .btn-hover-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,255,255,0.2) !important;
        background: linear-gradient(45deg, #f8f9fa, #ffffff);
    }

    .display-6 {
        font-size: 2.25rem;
        letter-spacing: -0.5px;
    }

    .opacity-05 {
        opacity: 0.05 !important;
    }
</style>
@endsection
