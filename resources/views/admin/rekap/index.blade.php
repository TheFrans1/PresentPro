@extends('layouts.admin')

@section('title', 'Rekap Laporan Absensi')

@push('styles')
<style>
    
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: visible;
        padding-bottom: 10px;
    }


    .table-laporan thead th {
        position: sticky;
        top: 0;
        background: #212529; 
        color: white;
        z-index: 10;
        white-space: nowrap;
    }

    .table-laporan th,
    .table-laporan td {
        padding: 10px 14px !important;
        vertical-align: middle;
        white-space: nowrap;
    }


    .col-izin {
        min-width: 140px;
    }

    .col-file {
        min-width: 110px;
    }

    .col-foto {
        min-width: 95px;
    }

    .truncate {
        max-width: 160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@section('content')
<div class="card shadow mb-4" style="width: 100%; overflow: hidden;">
    <div class="none">
       
    </div>

    {{-- ======================= FILTER ======================= --}}
    <div class="card-body">

        <form action="{{ route('admin.laporan.index') }}" method="GET">
            <div class="row g-3">

                {{-- Filter Tanggal Mulai --}}
                <div class="col-md-3">
                    
                    <input type="date" class="form-control" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                </div>

                
                <div class="col-md-3">
                
                    <input type="date" class="form-control" placeholder="" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                </div>

                {{-- Filter Nama --}}
                <div class="col-md-2">
                    <input type="text" class="form-control" name="search_nama" placeholder="Cari Nama..."
                           value="{{ request('search_nama') }}">
                </div>

                {{-- Filter NIK --}}
                <div class="col-md-2">
                   
                    <input type="text" class="form-control" name="search_nik" placeholder="Cari NIK..."
                           value="{{ request('search_nik') }}">
                </div>

                {{-- Filter Status --}}
                <div class="col-md-2">
                   
                    <select class="form-select" name="filter_status">
                        <option value="">Pilih Status</option>
                        <option value="Hadir"      {{ request('filter_status')=='Hadir'?'selected':'' }}>Hadir</option>
                        <option value="Terlambat"  {{ request('filter_status')=='Terlambat'?'selected':'' }}>Terlambat</option>
                        <option value="Izin"       {{ request('filter_status')=='Izin'?'selected':'' }}>Izin</option>
                        <option value="Sakit"      {{ request('filter_status')=='Sakit'?'selected':'' }}>Sakit</option>
                        <option value="Alpha"      {{ request('filter_status')=='Alpha'?'selected':'' }}>Alpha</option>
                    </select>
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-between">

                    <div>
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-filter"></i> Terapkan Filter
                        </button>

                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>

                    <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Export ke CSV
                    </a>

                </div>
            </div>

        </form>

    </div> {{-- card-body END --}}
</div>

    {{-- ======================= AKHIR FILTER ======================= --}}

    <div class="card-body">
        <div class="table-wrapper">
            <table class="table table-bordered table-hover table-laporan">
                <thead class="table-dark">
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Tanggal</th>
                        <th>Status Masuk</th>
                        <th>Jam Masuk</th>
                        <th>Keterangan</th>
                        <th>Status Pulang</th>
                        <th>Jam Pulang</th>
                        <th>Durasi</th>
                        <th>Foto</th>

                        {{-- ===================== KOLUM IZIN ===================== --}}
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Selesai</th>
                        <th>File Bukti</th>
                        <th>Status Approval</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dataLaporan as $absen)
                    <tr>
                        <td>{{ $absen->user->nik ?? '-' }}</td>
                        <td class="fw-bold">{{ $absen->user->nama ?? 'User Dihapus' }}</td>
                        <td>{{ $absen->user->jabatan ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>

                        <td>
                            @if ($absen->status_absensi == 'Hadir')
                                <span class="badge bg-success">Hadir</span>
                            @elseif ($absen->status_absensi == 'Terlambat')
                                <span class="badge bg-warning text-dark">Terlambat</span>
                            @elseif ($absen->status_absensi == 'Izin')
                                <span class="badge bg-info text-dark">Izin</span>
                            @elseif ($absen->status_absensi == 'Sakit')
                                <span class="badge bg-danger">Sakit</span>
                            @elseif ($absen->status_absensi == 'Alpha')
                                <span class="badge bg-secondary">Alpha</span>
                            @else
                                <span class="badge bg-light text-dark">{{ $absen->status_absensi }}</span>
                            @endif
                        </td>

                        <td>{{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') : '-' }}</td>
                        <td>{{ $absen->ket_status_msk ?? '-' }}</td>

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
                                <a href="{{ asset('storage/' . $absen->foto_masuk) }}" target="_blank" class="btn btn-outline-primary btn-sm">Masuk</a>
                            @endif
                            @if($absen->foto_pulang)
                                <a href="{{ asset('storage/' . $absen->foto_pulang) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Pulang</a>
                            @endif
                        </td>

                        {{-- ======================= DATA IZIN ======================= --}}
                        <td>{{ $absen->izin->tanggal_pengajuan ?? '-' }}</td>
                        <td>{{ $absen->izin->tanggal_mulai ?? '-' }}</td>
                        <td>{{ $absen->izin->tanggal_selesai ?? '-' }}</td>

                        <td class="text-center">
                            @if(isset($absen->izin) && $absen->izin->file_bukti)
                                <a href="{{ asset('storage/bukti/' . $absen->izin->file_bukti) }}" target="_blank" class="btn btn-sm btn-info">
                                    View
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $absen->izin->status_approval ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="16" class="text-center">
                            Tidak ada data absensi ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $dataLaporan->appends($request)->links() }}
        </div>
    </div>
</div>
@endsection
