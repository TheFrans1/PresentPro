@extends('layouts.admin')

@section('title', 'Kelola Jadwal Kerja')
@section('content')

{{-- Notifikasi Sukses (Sudah Benar) --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Notifikasi Error (Sudah Benar) --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Notifikasi Validasi (Sudah Benar) --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Data tidak valid:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow">
    <div class="card-header">
        <h5 class="m-0">Pengaturan Jam Kerja</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.jadwal.update') }}" method="POST">
            @csrf
            @method('PUT') 

            <p class="text-muted">Atur jam masuk, jam pulang, dan toleransi keterlambatan (dalam menit). Jika jam diisi 00:00, hari tersebut dianggap libur.</p>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam Masuk</th>
                            <th scope="col">Jam Pulang</th>
                            <th scope="col" style="width: 150px;">Toleransi (Menit)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalKerja as $jadwal)
                            <tr>
                                <td class="fw-bold">{{ $jadwal->hari }}</td>
                                <td>
                                    <input type="time" class="form-control" 
                                           name="jam_masuk[{{ $jadwal->id }}]" 
                                           value="{{ $jadwal->jam_masuk ? \Carbon\Carbon::parse($jadwal->jam_masuk)->format('H:i') : '' }}">
                                </td>
                                <td>
                                    <input type="time" class="form-control" 
                                           name="jam_keluar[{{ $jadwal->id }}]" 
                                           value="{{ $jadwal->jam_keluar ? \Carbon\Carbon::parse($jadwal->jam_keluar)->format('H:i') : '' }}">
                                </td>
                                <td>
                                    <input type="number" class="form-control" 
                                           name="toleransi[{{ $jadwal->id }}]" 
                                           value="{{ $jadwal->toleransi }}" min="0">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data jadwal tidak ditemukan.</td>
                            </tr>
                        {{-- ================ PERBAIKAN 1 ================ --}}
                        @endforelse {{-- <-- Ini diperbaiki dari @endfelse --}}
                        {{-- =============================================== --}}
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan Perubahan Jadwal</button>
            </div>
        </form>
    </div>
</div>

{{-- ================ PERBAIKAN 2 ================ --}}
{{-- Baris @endforelse tambahan di sini SUDAH DIHAPUS --}}
{{-- =============================================== --}}

@endsection