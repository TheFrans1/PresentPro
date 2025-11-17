@extends('layouts.admin')

@section('title', 'Riwayat Pengajuan Surat')

@section('content')

<div class="card shadow">
    
    <div class="card-header">
        
        <div class="d-flex justify-content-between align-items-center">
            
            <h5 class="m-0">Riwayat Pengajuan</h5>
            
 <form action="{{ route('admin.izin.riwayat') }}" method="GET">
            {{-- Gunakan Grid System Bootstrap --}}
            <div class="row g-3 align-items-end">
                
                {{-- Filter Nama --}}
                <div class="col-lg-3 col-md-6 col-12">
                    <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Nama Karyawan..." value="{{ request('search') }}">
                </div>
                
                {{-- Filter NIK --}}
                <div class="col-lg-2 col-md-6 col-12">
                    <input type="text" name="filter_nik" id="filter_nik" class="form-control form-control-sm" placeholder="NIK..." value="{{ request('filter_nik') }}">
                </div>
                
                {{-- Filter Tanggal --}}
                <div class="col-lg-2 col-md-6 col-12">
                    <input type="text" name="filter_tanggal" id="filter_tanggal" class="form-control form-control-sm datepicker-dmy" placeholder="Pilih tanggal..." value="{{ request('filter_tanggal') }}">
                </div>
                
                {{-- Filter Status --}}
                <div class="col-lg-2 col-md-6 col-12">
                    <select name="filter_status" id="filter_status" class="form-select form-select-sm">
                        <option value="Disetujui" {{ request('filter_status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('filter_status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                
                {{-- Tombol (Akan pindah ke bawah di HP) --}}
                <div class="col-lg-3 col-md-12 col-12 d-flex justify-content-start justify-content-lg-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="bi bi-filter"></i> Cari
                    </button>
                    <a href="{{ route('admin.izin.riwayat') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>

            </div>
        </form>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Karyawan</th>
                        <th scope="col">NIK</th>
                        <th scope="col">Tgl. Diajukan</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Tanggal Izin</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">File Bukti</th>
                        <th scope="col">Status</th> 
                    </tr>
                </thead>
                <tbody>
                    @forelse ($izinRiwayat as $izin)
                        <tr>
                            <th scope="row">{{ $izinRiwayat->firstItem() + $loop->index }}</th>
                            <td>{{ $izin->user->nama ?? 'User Dihapus' }}</td>
                            <td>{{ $izin->user->nik ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal_pengajuan)->format('d F Y') }}</td>
                            <td>
                                @if ($izin->jenis == 'Izin')
                                    <span class="badge bg-info">Izin</span>
                                @else
                                    <span class="badge bg-warning text-dark">Sakit</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d F Y') }}</td>
                            <td>
                                {{-- Kita batasi 50 karakter agar tabel tidak rusak --}}
                                {{ \Illuminate\Support\Str::limit($izin->keterangan, 50, '...') }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ asset('storage/surat_izin/' . $izin->file_bukti) }}" class="btn btn-outline-primary" target="_blank" title="Lihat File">
                                        <i class="bi bi-eye-fill"></i> Lihat
                                    </a>
                                     <a href="{{ asset('storage/surat_izin/' . $izin->file_bukti) }}" class="btn btn-outline-secondary" title="Unduh File" download>
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                @if ($izin->status_approval == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif ($izin->status_approval == 'Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                           <td colspan="9" class="text-center">
                                Tidak ada data riwayat pengajuan (atau tidak ditemukan).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end">
            {{ $izinRiwayat->links() }}
        </div>
    </div>
</div>

@endsection