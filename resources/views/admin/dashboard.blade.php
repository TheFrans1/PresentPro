@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('content')

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4 stats-card-animate">
        <div class="card border-start-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Total Karyawan (Aktif)</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4 stats-card-animate">
        <div class="card border-start-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Hadir (Hari Ini)</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clipboard-check-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4 stats-card-animate">
        <div class="card border-start-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Terlambat (Hari Ini)</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4 stats-card-animate">
        <div class="card border-start-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                            Menunggu Approval</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-exclamation-triangle-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-primary">Grafik Statistik Absensi Bulanan</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 320px;">
                    <p class="text-center text-muted pt-5">... (Fitur Grafik akan diimplementasikan) ...</p>
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Pengajuan Izin/Sakit Terbaru</h6>
            </div>
            <div class="card-body">
                <p class="text-center text-muted">... (Belum ada data pengajuan) ...</p>
            </div>
        </div>
    </div>
</div>

<style>
.card .border-start-primary { border-left: 4px solid #4e73df !important; }
.card .border-start-success { border-left: 4px solid #1cc88a !important; }
.card .border-start-warning { border-left: 4px solid #f6c23e !important; }
.card .border-start-danger  { border-left: 4px solid #e74a3b !important; }
.card .text-xs { font-size: 0.8rem; }
.card .text-gray-300 { color: #dddfeb !important; }
</style>

@endsection