@extends('layouts.karyawan')

@section('title', 'Riwayat Pengajuan Saya')
@section('page-title', 'Riwayat Pengajuan Izin/Sakit')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tgl. Diajukan</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Tanggal Izin</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">File Bukti</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayatIzin as $izin)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal_pengajuan)->format('d F Y') }}</td>
                            <td>
                                @if ($izin->jenis == 'Izin')
                                    <span class="badge bg-info">Izin</span>
                                @else
                                    <span class="badge bg-warning text-dark">Sakit</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d F Y') }}</td>
                            <td>{{ $izin->keterangan }}</td>
                            
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
                                @if ($izin->status_approval == 'Pending')
                                    <span class="badge bg-secondary">Pending</span>
                                @elseif ($izin->status_approval == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif ($izin->status_approval == 'Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Anda belum pernah membuat pengajuan izin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection