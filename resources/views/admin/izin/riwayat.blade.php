@extends('layouts.admin')

@section('title', 'Riwayat Pengajuan Surat')

@section('content')

<div class="card shadow">
    
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="m-0">Riwayat Pengajuan</h5>

            <form action="{{ route('admin.izin.riwayat') }}" method="GET">
                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6 col-12">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama Karyawan..." value="{{ request('search') }}">
                    </div>

                    <div class="col-lg-2 col-md-6 col-12">
                        <input type="text" name="filter_nik" class="form-control form-control-sm" placeholder="NIK..." value="{{ request('filter_nik') }}">
                    </div>

                    <div class="col-lg-2 col-md-6 col-12">
                        <input type="text" name="filter_tanggal" class="form-control form-control-sm datepicker-dmy" placeholder="Pilih tanggal..." value="{{ request('filter_tanggal') }}">
                    </div>

                    <div class="col-lg-2 col-md-6 col-12">
                        <select name="filter_status" class="form-select form-select-sm">
                            <option value="Disetujui" {{ request('filter_status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ request('filter_status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

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
                        <th>#</th>
                        <th>Nama Karyawan</th>
                        <th>NIK</th>
                        <th>Tgl. Diajukan</th>
                        <th>Jenis</th>
                        <th>Tanggal Izin</th>
                        <th>Keterangan</th>
                        <th>File Bukti</th>
                        <th>Status</th>
                        <th>Alasan Penolakan</th> {{-- 🔥 kolom baru --}}
                    </tr>
                </thead>

                <tbody>
                    @forelse ($izinRiwayat as $izin)
                        <tr>
                            <td>{{ $izinRiwayat->firstItem() + $loop->index }}</td>

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

                            <td>
                                {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d F Y') }}
                                -
                                {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d F Y') }}
                            </td>

                            <td>{{ \Illuminate\Support\Str::limit($izin->keterangan, 50, '...') }}</td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ asset('storage/surat_izin/' . $izin->file_bukti) }}" 
                                       class="btn btn-primary btn-sm"
                                       target="_blank">
                                        <i class="bi bi-eye-fill"></i> Lihat
                                    </a>
                                    <a href="{{ asset('storage/surat_izin/' . $izin->file_bukti) }}"
                                       class="btn btn-primary btn-sm"
                                       download>
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

                            {{-- 🔥 KOLUMN ALASAN PENOLAKAN --}}
                            <td>
                                @if ($izin->status_approval == 'Ditolak')
                                    {{ $izin->alasan_penolakan ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                Tidak ada data riwayat pengajuan.
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
