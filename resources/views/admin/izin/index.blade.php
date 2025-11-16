@extends('layouts.admin')

@section('title', 'Kelola Surat Izin & Sakit')

@section('content')

<style>
    .alert-floating {
        position: absolute;
        top: 0; 
        right: 0;
        z-index: 1050;
        width: auto;
        min-width: auto;
    }
</style>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show alert-floating position-absolute" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow">
    <div class="card-header">
        <h5 class="m-0">Daftar Pengajuan Surat</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center" style="width: 50px;">#</th>
                        <th scope="col" class="text-center">Nama</th>
                        <th scope="col" class="text-center" style="width: 100px;">NIK</th>
                        <th scope="col" class="text-center">Tgl. Diajukan</th>
                        <th scope="col" class="text-center" style="width: 100px;">Jenis</th>
                        <th scope="col" class="text-center">Tanggal Izin</th>
                        
                        {{-- ================ KOLOM BARU DITAMBAHKAN ================ --}}
                        <th scope="col">Keterangan</th>
                        {{-- ======================================================== --}}
                        
                        <th scope="col" class="text-center" style="width: 150px;">File Bukti</th>
                        <th scope="col" class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse ($izinPending as $izin)
                        <tr>
                            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                            <td>{{ $izin->user->nama ?? 'User Dihapus' }}</td>
                            <td class="text-center">{{ $izin->user->nik ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($izin->tanggal_pengajuan)->format('d F Y') }}</td>
                            <td class="text-center">
                                @if ($izin->jenis == 'Izin')
                                    <span class="badge bg-info">Izin</span>
                                @else
                                    <span class="badge bg-warning text-dark">Sakit</span>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d F Y') }}</td>
                            
                            {{-- ================ DATA BARU DITAMBAHKAN ================ --}}
                            <td>
                                {{-- Menampilkan keterangan, dibatasi 50 karakter --}}
                                {{ \Illuminate\Support\Str::limit($izin->keterangan, 50, '...') }}
                            </td>
                            {{-- ======================================================== --}}

                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ asset('storage/surat_izin/' . $izin->file_bukti) }}" class="btn btn-outline-primary" target="_blank" title="Lihat File">
                                        <i class="bi bi-eye-fill"></i> Lihat
                                    </a>
                                    <a href="{{ asset('storage/surat_izin/' . $izin->file_bukti) }}" class="btn btn-outline-secondary" title="Unduh File" download>
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.izin.setujui', $izin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin MENYETUJUI pengajuan ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                        <i class="bi bi-check-lg"></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.izin.tolak', $izin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin MENOLAK pengajuan ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Colspan diubah dari 9 menjadi 10 --}}
                            <td colspan="10" class="text-center">
                                Belum ada pengajuan izin yang menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        let alert = document.querySelector('.alert-floating');
        
        if (alert) {
            let closeButton = alert.querySelector('.btn-close');
            
            if (closeButton) {
                setTimeout(() => {
                    closeButton.click();
                }, 4000); // 4 detik
            }
        }
    });
</script>

@endsection