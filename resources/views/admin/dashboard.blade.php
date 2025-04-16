@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <!-- Header Dashboard Admin -->
  <div class="bg-navy-gradient text-white rounded-3 p-4 mb-4 shadow">
    <div class="d-flex flex-column gap-3">
        <!-- Judul dan Deskripsi -->
        <div>
            <h4 class="mb-1 fw-semibold">
                <i class="bi bi-person-gear me-2"></i>Dashboard Admin
            </h4>
            <p class="mb-0 small opacity-75">Manajemen Pengajuan Surat</p>
        </div>

        <!-- Informasi Admin -->
        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle fs-5"></i>
                <div>
                    <p class="mb-0 small opacity-75">Nama Admin</p>
                    <p class="mb-0 fw-medium">{{ auth()->user()->name ?? 'Admin' }}</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-building fs-5"></i>
                <div>
                    <p class="mb-0 small opacity-75">Fakultas/Prodi</p>
                    <p class="mb-0 fw-medium">
                        {{ optional($admin->prodi->fakultas)->nama_fakultas ?? 'Belum diatur' }} /
                        {{ $admin->prodi->nama_prodi ?? 'Belum diatur' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
  </div>

    <!-- Tabel Pengajuan Belum di Upload -->
    <div class="card shadow-sm mb-4 rounded-top-4">
      <div class="card-header bg-warning-subtle rounded-top-4">
          <h5 class="mb-0">
              <i class="bi bi-file-earmark-text me-1"></i>Daftar Pengajuan Surat Belum di Upload
          </h5>
      </div>
      <div class="card-body p-0">
          <div class="table-responsive">
              <table class="table table-borderless table-hover align-middle mb-0">
                  <thead class="border-bottom">
                      <tr>
                          <th>
                              <i class=""></i>ID Pengajuan
                          </th>
                          <th>
                              <i class="bi bi-person-fill me-1"></i>Nama Mahasiswa
                          </th>
                          <th>
                              <i class="bi bi-person-badge me-1"></i>NRP
                          </th>
                          <th>
                              <i class="bi bi-envelope me-1"></i>Jenis Surat
                          </th>
                          <th>
                              <i class="bi bi-calendar-event me-1"></i>Tanggal Pengajuan
                          </th>
                          <th class="text-end">
                              <i class="bi bi-gear-fill me-1"></i>Aksi
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse($notUploaded as $pengajuan)
                      <tr>
                          <td>{{ $pengajuan->id_pengajuan }}</td>
                          <td>{{ $pengajuan->mahasiswa->user->name ?? '-' }}</td>
                          <td>{{ $pengajuan->mahasiswa->id ?? '-' }}</td>
                          <td>{{ $pengajuan->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                          <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                          <td class="text-end">
                              <!-- Tombol Detail Surat -->
                              <button class="btn btn-info btn-sm" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#detailModal{{ $pengajuan->id_pengajuan }}">
                                  <i class="bi bi-eye me-1"></i>Tinjau
                              </button>
                              <!-- Tombol Upload Surat -->
                              <button class="btn btn-primary btn-sm" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#uploadModal{{ $pengajuan->id_pengajuan }}">
                                  <i class="bi bi-upload me-1"></i>Upload Surat
                              </button>
                          </td>
                      </tr>

                      <!-- Modal Detail Surat -->
                      <div class="modal fade" id="detailModal{{ $pengajuan->id_pengajuan }}" tabindex="-1" 
                           aria-labelledby="detailModalLabel{{ $pengajuan->id_pengajuan }}" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title" id="detailModalLabel{{ $pengajuan->id_pengajuan }}">
                                          Detail Surat (ID: {{ $pengajuan->id_pengajuan }})
                                      </h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                              aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                      <!-- Informasi Pengajuan -->
                                      <p><strong>Nama Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? '-' }}</p>
                                      <p><strong>NRP:</strong> {{ $pengajuan->mahasiswa->id ?? '-' }}</p>
                                      <p><strong>Jenis Surat:</strong> {{ $pengajuan->jenisSurat->nama_jenis_surat ?? '-' }}</p>
                                      <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->tanggal_pengajuan }}</p>
                                      <hr>
                                      @php
                                          $detail = null;
                                          switch ($pengajuan->id_jenis_surat) {
                                              case 1:
                                                  $detail = DB::table('detail_pengajuan_surat_keterangan_mahasiswa_aktif')
                                                      ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                      ->first();
                                                  break;
                                              case 2:
                                                  $detail = DB::table('detail_pengajuan_surat_pengantar_mahasiswa')
                                                      ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                      ->first();
                                                  break;
                                              case 3:
                                                  $detail = DB::table('detail_pengajuan_surat_rekomendasi_mbkm')
                                                      ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                      ->first();
                                                  break;
                                              case 4:
                                                  $detail = DB::table('detail_pengajuan_surat_laporan_hasil_studi')
                                                      ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                      ->first();
                                                  break;
                                              case 5:
                                                  $detail = DB::table('detail_pengajuan_surat_kelulusan')
                                                      ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                      ->first();
                                                  break;
                                              default:
                                                  $detail = null;
                                                  break;
                                          }
                                      @endphp

                                      @if($detail)
                                          @switch($pengajuan->id_jenis_surat)
                                              @case(1)
                                                  <p><strong>Semester:</strong> {{ $detail->semester ?? '-' }}</p>
                                                  <p><strong>Alamat:</strong> {{ $detail->alamat ?? '-' }}</p>
                                                  <p><strong>Keperluan Pengajuan:</strong> {{ $detail->keperluan_pengajuan ?? '-' }}</p>
                                                  @break

                                              @case(2)
                                                  <p><strong>Tujuan Surat:</strong> {{ $detail->tujuan_surat ?? '-' }}</p>
                                                  <p><strong>Nama PT:</strong> {{ $detail->nama_pt ?? '-' }}</p>
                                                  <p><strong>Alamat PT:</strong> {{ $detail->alamat_pt ?? '-' }}</p>
                                                  <p><strong>Nama Mata Kuliah:</strong> {{ $detail->nama_mata_kuliah ?? '-' }}</p>
                                                  <p><strong>Kode Mata Kuliah:</strong> {{ $detail->kode_mata_kuliah ?? '-' }}</p>
                                                  <p><strong>Semester:</strong> {{ $detail->semester ?? '-' }}</p>
                                                  <p><strong>Data Mahasiswa:</strong> {!! nl2br(e($detail->data_mahasiswa)) !!}</p>
                                                  <p><strong>Tujuan:</strong> {{ $detail->tujuan ?? '-' }}</p>
                                                  <p><strong>Topik:</strong> {{ $detail->topik ?? '-' }}</p>
                                                  @break

                                              @case(3)
                                                  <p><strong>Detail Surat Rekomendasi MBKM:</strong> Data detail belum diimplementasikan.</p>
                                                  @break

                                              @case(4)
                                                  <p><strong>Keperluan LHS:</strong> {{ $detail->keperluan_lhs ?? '-' }}</p>
                                                  @break

                                              @case(5)
                                                  <p><strong>Tanggal Kelulusan:</strong> {{ $detail->tanggal_kelulusan ?? '-' }}</p>
                                                  @break

                                              @default
                                                  <p>Detail tidak tersedia.</p>
                                          @endswitch
                                      @else
                                          <p>Detail surat tidak ditemukan.</p>
                                      @endif
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- End Modal Detail Surat -->

                      <!-- Modal Upload Surat -->
                      <div class="modal fade" id="uploadModal{{ $pengajuan->id_pengajuan }}" tabindex="-1" 
                           aria-labelledby="uploadModalLabel{{ $pengajuan->id_pengajuan }}" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <form action="{{ route('admin.upload.surat', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data">
                                      @csrf
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="uploadModalLabel{{ $pengajuan->id_pengajuan }}">
                                              Upload File Surat
                                          </h5>
                                          <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          <div class="mb-3">
                                              <label for="no_surat_{{ $pengajuan->id_pengajuan }}" class="form-label">Nomor Surat</label>
                                              <input type="text" name="no_surat" id="no_surat_{{ $pengajuan->id_pengajuan }}" class="form-control" required>
                                          </div>
                                          <div class="mb-3">
                                              <label for="file_surat_{{ $pengajuan->id_pengajuan }}" class="form-label">Pilih File (PDF/JPG/JPEG/PNG)</label>
                                              <input type="file" name="file_surat" id="file_surat_{{ $pengajuan->id_pengajuan }}" class="form-control" required>
                                          </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="submit" class="btn btn-primary">
                                              <i class="bi bi-upload me-1"></i>Upload
                                          </button>
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                      <!-- End Modal Upload Surat -->
                      @empty
                      <tr>
                          <td colspan="6" class="text-center">Tidak ada surat yang belum diupload.</td>
                      </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>
    </div>
    <!-- End Tabel Pengajuan Belum di Upload -->

    <!-- Pagination untuk data belum diupload -->
    @if($notUploaded->hasPages())
    <div class="card-footer bg-white">
        <nav>
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item {{ $notUploaded->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $notUploaded->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                @for($i = 1; $i <= $notUploaded->lastPage(); $i++)
                    <li class="page-item {{ $notUploaded->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $notUploaded->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item {{ $notUploaded->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $notUploaded->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    @endif

    <!-- Tabel Pengajuan Sudah di Upload -->
    <div class="card shadow-sm mt-4 rounded-4">
        <div class="card-header bg-success-subtle rounded-top-4">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-check me-1"></i>Daftar Pengajuan Surat Sudah di Upload
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-hover align-middle mb-0">
                    <thead class="border-bottom" style="background-color: #e9ecef;">
                        <tr>
                            <th>
                                <i class=""></i>ID Pengajuan
                            </th>
                            <th>
                                <i class="bi bi-person-fill me-1"></i>Nama Mahasiswa
                            </th>
                            <th>
                                <i class="bi bi-person-badge me-1"></i>NRP
                            </th>
                            <th>
                                <i class="bi bi-envelope me-1"></i>Jenis Surat
                            </th>
                            <th>
                                <i class="bi bi-calendar-event me-1"></i>Tanggal Pengajuan
                            </th>
                            <th class="text-end">
                                <i class="bi bi-gear-fill me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($uploaded as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->id_pengajuan }}</td>
                            <td>{{ $pengajuan->mahasiswa->user->name ?? '-' }}</td>
                            <td>{{ $pengajuan->mahasiswa->id ?? '-' }}</td>
                            <td>{{ $pengajuan->jenisSurat->nama_jenis_surat ?? '-' }}</td>
                            <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                            <td class="text-end">
                                <!-- Tombol Detail Surat -->
                                <button class="btn btn-info btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal{{ $pengajuan->id_pengajuan }}">
                                    <i class="bi bi-eye me-1"></i>Tinjau
                                </button>
                                <a href="{{ $pengajuan->surat_file->full_url }}" class="btn btn-success btn-sm" target="_blank">
                                    <i class="bi bi-file-earmark-text me-1"></i>Lihat Surat
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Detail Surat -->
                        <div class="modal fade" id="detailModal{{ $pengajuan->id_pengajuan }}" tabindex="-1" 
                             aria-labelledby="detailModalLabel{{ $pengajuan->id_pengajuan }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $pengajuan->id_pengajuan }}">
                                            Detail Surat (ID: {{ $pengajuan->id_pengajuan }})
                                        </h5>
                                        <button type="button" class="btn btn-close" data-bs-dismiss="modal" 
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Informasi Pengajuan -->
                                        <p><strong>Nama Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? '-' }}</p>
                                        <p><strong>NRP:</strong> {{ $pengajuan->mahasiswa->id ?? '-' }}</p>
                                        <p><strong>Jenis Surat:</strong> {{ $pengajuan->jenisSurat->nama_jenis_surat ?? '-' }}</p>
                                        <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->tanggal_pengajuan }}</p>
                                        <hr>
                                        @php
                                            $detail = null;
                                            switch ($pengajuan->id_jenis_surat) {
                                                case 1:
                                                    $detail = DB::table('detail_pengajuan_surat_keterangan_mahasiswa_aktif')
                                                        ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                        ->first();
                                                    break;
                                                case 2:
                                                    $detail = DB::table('detail_pengajuan_surat_pengantar_mahasiswa')
                                                        ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                        ->first();
                                                    break;
                                                case 3:
                                                    $detail = DB::table('detail_pengajuan_surat_rekomendasi_mbkm')
                                                        ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                        ->first();
                                                    break;
                                                case 4:
                                                    $detail = DB::table('detail_pengajuan_surat_laporan_hasil_studi')
                                                        ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                        ->first();
                                                    break;
                                                case 5:
                                                    $detail = DB::table('detail_pengajuan_surat_kelulusan')
                                                        ->where('pengajuan_id', $pengajuan->id_pengajuan)
                                                        ->first();
                                                    break;
                                                default:
                                                    $detail = null;
                                                    break;
                                            }
                                        @endphp

                                        @if($detail)
                                            @switch($pengajuan->id_jenis_surat)
                                                @case(1)
                                                    <p><strong>Semester:</strong> {{ $detail->semester ?? '-' }}</p>
                                                    <p><strong>Alamat:</strong> {{ $detail->alamat ?? '-' }}</p>
                                                    <p><strong>Keperluan Pengajuan:</strong> {{ $detail->keperluan_pengajuan ?? '-' }}</p>
                                                    @break

                                                @case(2)
                                                    <p><strong>Tujuan Surat:</strong> {{ $detail->tujuan_surat ?? '-' }}</p>
                                                    <p><strong>Nama PT:</strong> {{ $detail->nama_pt ?? '-' }}</p>
                                                    <p><strong>Alamat PT:</strong> {{ $detail->alamat_pt ?? '-' }}</p>
                                                    <p><strong>Nama Mata Kuliah:</strong> {{ $detail->nama_mata_kuliah ?? '-' }}</p>
                                                    <p><strong>Kode Mata Kuliah:</strong> {{ $detail->kode_mata_kuliah ?? '-' }}</p>
                                                    <p><strong>Semester:</strong> {{ $detail->semester ?? '-' }}</p>
                                                    <p><strong>Data Mahasiswa:</strong> {!! nl2br(e($detail->data_mahasiswa)) !!}</p>
                                                    <p><strong>Tujuan:</strong> {{ $detail->tujuan ?? '-' }}</p>
                                                    <p><strong>Topik:</strong> {{ $detail->topik ?? '-' }}</p>
                                                    @break

                                                @case(3)
                                                    <p><strong>Detail Surat Rekomendasi MBKM:</strong> Data detail belum diimplementasikan.</p>
                                                    @break

                                                @case(4)
                                                    <p><strong>Keperluan LHS:</strong> {{ $detail->keperluan_lhs ?? '-' }}</p>
                                                    @break

                                                @case(5)
                                                    <p><strong>Tanggal Kelulusan:</strong> {{ $detail->tanggal_kelulusan ?? '-' }}</p>
                                                    @break

                                                @default
                                                    <p>Detail tidak tersedia.</p>
                                            @endswitch
                                        @else
                                            <p>Detail surat tidak ditemukan.</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Detail Surat -->

                        <!-- Modal Upload Surat -->
                        <div class="modal fade" id="uploadModal{{ $pengajuan->id_pengajuan }}" tabindex="-1" 
                             aria-labelledby="uploadModalLabel{{ $pengajuan->id_pengajuan }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.upload.surat', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel{{ $pengajuan->id_pengajuan }}">
                                                Upload File Surat
                                            </h5>
                                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="no_surat_{{ $pengajuan->id_pengajuan }}" class="form-label">Nomor Surat</label>
                                                <input type="text" name="no_surat" id="no_surat_{{ $pengajuan->id_pengajuan }}" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="file_surat_{{ $pengajuan->id_pengajuan }}" class="form-label">Pilih File (PDF/JPG/JPEG/PNG)</label>
                                                <input type="file" name="file_surat" id="file_surat_{{ $pengajuan->id_pengajuan }}" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-upload me-1"></i>Upload
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Upload Surat -->
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada surat yang sudah diupload.</td>
                        </tr>
                        @endforelse
                  </tbody>
              </table>
          </div>
      </div>
    </div>
    <!-- End Tabel Pengajuan Sudah di Upload -->

    <!-- Pagination untuk data yang sudah diupload -->
    @if($uploaded->hasPages())
    <nav aria-label="Page navigation example" class="mt-4">
      <ul class="pagination pagination-sm justify-content-center">
        <li class="page-item {{ $uploaded->onFirstPage() ? 'disabled' : '' }}">
          <a class="page-link" href="{{ $uploaded->previousPageUrl() }}" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        @for($i = 1; $i <= $uploaded->lastPage(); $i++)
          <li class="page-item {{ $uploaded->currentPage() == $i ? 'active' : '' }}">
            <a class="page-link" href="{{ $uploaded->url($i) }}">{{ $i }}</a>
          </li>
        @endfor
        <li class="page-item {{ $uploaded->hasMorePages() ? '' : 'disabled' }}">
          <a class="page-link" href="{{ $uploaded->nextPageUrl() }}" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
    @endif
    
</div>

<style>
    :root {
        --navy: #2c3e50;
        --navy-gradient: linear-gradient(45deg, #2c3e50, #34495e);
    }
    
    /* Background dan warna teks */
    .bg-navy-gradient { background: var(--navy-gradient); }
    .text-navy { color: var(--navy); }
    .border-navy { border-color: var(--navy) !important; }
    
    /* Button Custom */
    .btn-navy {
        background: var(--navy);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-navy:hover {
        background: #34495e;
        transform: translateY(-1px);
    }
    .btn-navy-outline {
        border: 1px solid #2c3e50;
        color: #2c3e50;
        transition: all 0.3s ease;
    }
    .btn-navy-outline:hover {
        background: #2c3e50;
        color: white;
    }
    
    /* Hover pada baris tabel */
    .table-hover tbody tr:hover {
        background-color: rgba(44, 62, 80, 0.05);
    }
    
    /* Style header tabel */
    thead tr th {
        font-weight: 600;
    }
    thead tr {
        background-color: #e9ecef;
    }
    
    /* Pagination */
    .pagination .page-link {
        color: var(--navy);
    }
    .pagination .page-item.active .page-link {
        background-color: var(--navy);
        border-color: var(--navy);
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
</style>
@endsection
