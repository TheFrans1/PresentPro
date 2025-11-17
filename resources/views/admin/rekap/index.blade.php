@extends('layouts.admin')

@section('title', 'Rekap Laporan Absensi')
@section('page-title', 'Rekap Laporan Absensi')

@push('styles')
<style>
    /* Sedikit style agar rapi */
    .table-laporan th {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h5 class="m-0">Rekap Laporan Absensi (Data Mentah)</h5>
    </div>
    
    {{-- ======================= FILTER BARU ======================= --}}
    <div class="card-body border-bottom">
        <form action="{{ route('admin.laporan.index') }}" method="GET">
            <div class="row g-3">
                {{-- Filter Tanggal Mulai --}}
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type-="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                </div>
                {{-- Filter Tanggal Selesai --}}
                <div class="col-md-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type-="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                </div>
                {{-- Filter Nama --}}
                <div class="col-md-2">
                    <label for="search_nama" class="form-label">Nama</label>
                    <input type-="text" class="form-control" id="search_nama" name="search_nama" placeholder="Cari Nama..." value="{{ request('search_nama') }}">
                </div>
                 {{-- Filter NIK --}}
                 <div class="col-md-2">
                    <label for="search_nik" class="form-label">NIK</label>
                    <input type-="text" class="form-control" id="search_nik" name="search_nik" placeholder="Cari NIK..." value="{{ request('search_nik') }}">
                </div>
                {{-- Filter Status --}}
                <div class="col-md-2">
                    <label for="filter_status" class="form-label">Status Masuk</label>
                    <select id="filter_status" name="filter_status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Hadir" {{ request('filter_status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Terlambat" {{ request('filter_status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="Izin" {{ request('filter_status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ request('filter_status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Alpha" {{ request('filter_status') == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-filter"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                    {{-- Tombol Ekspor CSV --}}
                    <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn btn-success ms-auto">
                        <i class="bi bi-file-earmark-excel"></i> Export ke CSV (Excel)
                    </a>
                </div>
            </div>
        </form>
    </div>
    {{-- ======================= AKHIR FILTER ======================= --}}

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-laporan">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">NIK</th>
                        <th scope="col">Nama Karyawan</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Status Masuk</th>
                        <th scope="col">Jam Masuk</th>
                        <th scope="col">Ket. Masuk</th>
                        <th scope="col">Status Pulang</th>
                        <th scope="col">Jam Pulang</th>
                        <th scope="col">Durasi Bekerja</th>
                        <th scope="col">Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataLaporan as $absen)
                        <tr>
                            <td>{{ $absen->user->nik ?? '-' }}</td>
                            <td class="fw-bold">{{ $absen->user->nama ?? 'User Dihapus' }}</td>
                            <td>{{ $absen->user->jabatan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
                            
                            {{-- ================== PERBAIKAN DI SINI ================== --}}
                            {{-- Status Masuk (Membaca dari kolom 'status_absen') --}}
                            <td>
                                @if ($absen->status_absensi == 'Hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif ($absen->status_absensi == 'Terlambat')
                                    <span class="badge bg-warning text-dark">Terlambat</span>
                                @elseif ($absen->status_absen == 'Izin')
                                    <span class="badge bg-info">Izin</span>
                                @elseif ($absen->status_absen == 'Sakit')
                                    <span class="badge bg-danger">Sakit</span>
                                @elseif ($absen->status_absen == 'Alpha')
                                    <span class="badge bg-secondary">Alpha</span>
                                @else
                                    {{-- Menampilkan status jika ada nilai lain --}}
                                    <span class="badge bg-light text-dark">{{ $absen->status_absen }}</span>
                                @endif
                            </td>
                            {{-- ======================================================= --}}
                            
                            <td>{{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') : '-' }}</td>
                            <td>{{ $absen->ket_status_msk ?? '-' }}</td>

                            {{-- Status Pulang --}}
                            <td>
                                @if ($absen->status_pulang == 'Tepat Waktu')
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @elseif ($absen->status_pulang == 'Pulang Cepat')
                                    <span class="badge bg-warning text-dark">Pulang Cepat</span>
                                @elseif ($absen->status_pulang == 'Diabsenkan Sistem')
                                    <span class="badge bg-secondary">Diabsenkan Sistem</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $absen->jam_keluar ? \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i:s') : '-' }}</td>
                            <td>{{ $absen->durasi_bekerja ?? '-' }}</td>
                            
                            {{-- Foto --}}
                            <td class="text-center">
                                @if($absen->foto_masuk)
                                    <a href="{{ asset('storage/' . $absen->foto_masuk) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Lihat Foto Masuk">Masuk</a>
                                @endif
                                @if($absen->foto_pulang)
                                    <a href="{{ asset('storage/' . $absen->foto_pulang) }}" target="_blank" class="btn btn-outline-secondary btn-sm" title="Liih Foto Pulang">Pulang</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">
                            Tidak ada data absensi yang ditemukan untuk rentang tanggal dan filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Link Paginasi --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $dataLaporan->appends($request)->links() }}
        </div>
    </div>
</div>
@endsection