@extends('layouts.admin')

@section('title', 'Dashboard Monitoring')
@section('page-title', 'Dashboard Monitoring Harian')

@push('styles')
<style>
    
    .simple-card {
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        background: #fff;
        transition: transform 0.2s;
    }
    .simple-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.05) !important;
    }
    
    
    .icon-box {
        width: 48px; height: 48px; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-right: 1rem;
    }
    
    .bg-light-primary { background-color: #eef2ff; color: #4e73df; }
    .bg-light-success { background-color: #e6fffa; color: #1cc88a; }
    .bg-light-warning { background-color: #fffbea; color: #f6c23e; }
    .bg-light-info    { background-color: #e0f7fa; color: #36b9cc; }
    .bg-light-secondary { background-color: #f8f9fa; color: #858796; }
    .bg-light-danger  { background-color: #ffeef0; color: #e74a3b; }
</style>
@endpush

@section('content')

{{-- 6 KARTU STATISTIK --}}
<div class="row g-3 mb-4">
    {{-- Kartu 1: Total --}}
    <div class="col-md-4 col-lg-2">
        <div class="card simple-card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box bg-light-primary"><i class="bi bi-people-fill"></i></div>
                <div><div class="text-muted small fw-bold text-uppercase">Karyawan</div><div class="h4 mb-0 fw-bold text-dark">{{ $totalKaryawan }}</div></div>
            </div>
        </div>
    </div>
    {{-- Kartu 2: Hadir --}}
    <div class="col-md-4 col-lg-2">
        <div class="card simple-card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box bg-light-success"><i class="bi bi-check-lg"></i></div>
                <div><div class="text-muted small fw-bold text-uppercase">Hadir</div><div class="h4 mb-0 fw-bold text-dark">{{ $hadir }}</div></div>
            </div>
        </div>
    </div>
    {{-- Kartu 3: Terlambat --}}
    <div class="col-md-4 col-lg-2">
        <div class="card simple-card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box bg-light-warning"><i class="bi bi-clock-history"></i></div>
                <div><div class="text-muted small fw-bold text-uppercase">Terlambat</div><div class="h4 mb-0 fw-bold text-dark">{{ $terlambat }}</div></div>
            </div>
        </div>
    </div>
    {{-- Kartu 4: Izin/Sakit --}}
    <div class="col-md-4 col-lg-2">
        <div class="card simple-card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box bg-light-info"><i class="bi bi-file-medical"></i></div>
                <div><div class="text-muted small fw-bold text-uppercase">Izin/Sakit</div><div class="h4 mb-0 fw-bold text-dark">{{ $izinSakit }}</div></div>
            </div>
        </div>
    </div>
    {{-- Kartu 5: Alpha --}}
    <div class="col-md-4 col-lg-2">
        <div class="card simple-card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box bg-light-danger"><i class="bi bi-x-circle-fill"></i></div>
                <div><div class="text-muted small fw-bold text-uppercase">Alpha</div><div class="h4 mb-0 fw-bold text-dark">{{ $alpha }}</div></div>
            </div>
        </div>
    </div>
    {{-- Kartu 6: Surat --}}
    <div class="col-md-4 col-lg-2">
        <div class="card simple-card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box bg-light-secondary position-relative">
                    <i class="bi bi-envelope-paper"></i>
                    @if($pengajuanBaru > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    @endif
                </div>
                <div><div class="text-muted small fw-bold text-uppercase">Surat Baru</div><div class="h4 mb-0 fw-bold text-dark">{{ $pengajuanBaru }}</div></div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL MONITORING HARIAN (TANPA FOTO, ADA STATUS PULANG) --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h6 class="m-0 fw-bold text-primary">
            <i class=""></i><span class="text-dark">{{ $hariIni->translatedFormat('l, d F Y') }}</span>
        </h6>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-arrow-clockwise"></i> Refresh Data
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center" style="width: 50px;">#</th>
                        <th scope="col">Nama Karyawan</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col" class="text-center">Status Masuk</th>
                        <th scope="col" class="text-center">Status Pulang</th> {{-- KOLOM BARU --}}
                        <th scope="col" class="text-center">Jam Masuk</th>
                        <th scope="col" class="text-center">Jam Pulang</th>
                        <th scope="col">Keterangan / Detail</th>
                        {{-- KOLOM FOTO DIHAPUS --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($monitoringHarian as $index => $data)
                        <tr>
                            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                            <td class="fw-bold text-dark">{{ $data['nama'] }}</td>
                            <td class="text-muted small">{{ $data['jabatan'] }}</td>

                            {{-- Kolom Status Masuk --}}
                            <td class="text-center">
                                <span class="badge bg-{{ $data['badge'] }} px-3 py-2 rounded-pill fw-normal border border-{{ $data['badge'] }}">
                                    {{ $data['status'] }}
                                </span>
                            </td>

                            {{-- Kolom Status Pulang (Baru) --}}
                            <td class="text-center">
                                @php
                                    $statusPulang = $data['status_pulang'] ?? '-';
                                    $badgePulang = 'secondary'; // Default abu-abu

                                    if ($statusPulang == 'Tepat Waktu') $badgePulang = 'success';
                                    elseif ($statusPulang == 'Pulang Cepat') $badgePulang = 'warning text-dark';
                                    elseif ($statusPulang == 'Diabsenkan Sistem') $badgePulang = 'danger';
                                @endphp
                                
                                @if($statusPulang != '-')
                                    <span class="badge bg-{{ $badgePulang }} px-3 py-2 rounded-pill fw-normal border border-{{ $badgePulang }}">
                                        {{ $statusPulang }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td class="text-center fw-bold text-dark">{{ $data['jam_masuk'] }}</td>
                            <td class="text-center fw-bold text-dark">{{ $data['jam_keluar'] }}</td>
                            <td class="small text-muted">{{ \Illuminate\Support\Str::limit($data['keterangan'], 50) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data karyawan aktif hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection