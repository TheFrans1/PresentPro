@extends('layouts.admin')

@section('title', 'Monitoring Absensi Harian')
@section('page-title', 'Monitoring Absensi Harian')

@push('styles')
<style>
    /* Style untuk "Checklist Box" */
    .table-monitoring th, .table-monitoring td {
        vertical-align: middle;
        text-align: center;
    }
    .table-monitoring td:nth-child(2), /* Kolom Nama */
    .table-monitoring th:nth-child(2) {
        text-align: left;
    }
    .status-check {
        font-size: 1.5rem; /* Ukuran ikon checklist */
        line-height: 1;
    }
    .text-hadir { color: #198754; }
    .text-terlambat { color: #ffc107; }
    .text-izin { color: #0dcaf0; }
    .text-sakit { color: #dc3545; }
    .text-alpha { color: #6c757d; }
    .text-libur { color: #adb5bd; }
</style>
@endpush

@section('content')
<div class="card shadow">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="m-0">
                Monitoring Karyawan Hari Ini: 
                <strong>{{ $tanggalHariIni->translatedFormat('l, d F Y') }}</strong>
            </h5>
            
            {{-- Tombol Refresh (untuk memuat ulang data) --}}
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-clockwise"></i> Refresh Data
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            {{-- INI ADALAH TABEL CHECKLIST BARU --}}
            <table class="table table-bordered table-hover table-monitoring">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="width: 150px;">NIK</th>
                        <th scope="col">Nama Karyawan</th>
                        <th scope="col" style="width: 150px;">Jabatan</th>
                        <th scope="col" style="width: 100px;">Hadir</th>
                        <th scope="col" style="width: 100px;">Terlambat</th>
                        <th scope="col" style="width: 100px;">Izin</th>
                        <th scope="col" style="width: 100px;">Sakit</th>
                        <th scope="col" style="width: 100px;">Alpha</th>
                        <th scope="col" style="width: 100px;">Libur</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($monitoringData as $data)
                        <tr>
                            <td>{{ $data['nik'] ?? '-' }}</td>
                            <td class="fw-bold">{{ $data['nama'] }}</td>
                            <td>{{ $data['jabatan'] ?? '-' }}</td>
                            
                            {{-- Ini adalah "Checklist Box" Anda --}}
                            <td>
                                @if ($data['status'] == 'Hadir')
                                    <i class="bi bi-check-square-fill text-hadir status-check" title="Hadir"></i>
                                @else
                                    <i class="bi bi-square text-muted status-check"></i>
                                @endif
                            </td>
                            <td>
                                @if ($data['status'] == 'Terlambat')
                                    <i class="bi bi-check-square-fill text-terlambat status-check" title="Terlambat"></i>
                                @else
                                    <i class="bi bi-square text-muted status-check"></i>
                                @endif
                            </td>
                            <td>
                                @if ($data['status'] == 'Izin')
                                    <i class="bi bi-check-square-fill text-izin status-check" title="Izin"></i>
                                @else
                                    <i class="bi bi-square text-muted status-check"></i>
                                @endif
                            </td>
                            <td>
                                @if ($data['status'] == 'Sakit')
                                    <i class="bi bi-check-square-fill text-sakit status-check" title="Sakit"></i>
                                @else
                                    <i class="bi bi-square text-muted status-check"></i>
                                @endif
                            </td>
                            <td>
                                @if ($data['status'] == 'Alpha')
                                    <i class="bi bi-check-square-fill text-alpha status-check" title="Alpha"></i>
                                @else
                                    <i class="bi bi-square text-muted status-check"></i>
                                @endif
                            </td>
                            <td>
                                @if ($data['status'] == 'Libur')
                                    <i class="bi bi-check-square-fill text-libur status-check" title="Libur"></i>
                                @else
                                    <i class="bi bi-square text-muted status-check"></i>
                                @endif
                            </td>
                            <td class="text-start">{{ $data['detail'] }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            Tidak ada data karyawan aktif yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Ini adalah Total Hitungan (Summary Box) Anda --}}
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3">Rekap Total Hari Ini</h5>
                <div class="row g-3">
                    <div class="col">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4 class="card-title">{{ $totals['Hadir'] }}</h4>
                                <p class="card-text">Hadir</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <h4 class="card-title">{{ $totals['Terlambat'] }}</h4>
                                <p class="card-text">Terlambat</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-info text-dark">
                            <div class="card-body text-center">
                                <h4 class="card-title">{{ $totals['Izin'] }}</h4>
                                <p class="card-text">Izin</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4 class="card-title">{{ $totals['Sakit'] }}</h4>
                                <p class="card-text">Sakit</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center">
                                <h4 class="card-title">{{ $totals['Alpha'] }}</h4>
                                <p class="card-text">Alpha</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light text-dark">
                            <div class="card-body text-center">
                                <h4 class="card-title">{{ $totals['Libur'] }}</h4>
                                <p class="card-text">Libur</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection