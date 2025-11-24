@extends('layouts.admin')

@section('title', 'Approval Pengajuan Izin')
@section('page-title', 'Daftar Pengajuan Izin Karyawan')

@section('content')

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Karyawan</th>
                        <th>Jenis</th>
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Izin</th>
                        <th>Keterangan</th>
                        <th>File Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                   @forelse ($izinPending as $izin)

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $izin->user->name }}</td>

                        <td>
                            @if ($izin->jenis == 'Izin')
                                <span class="badge bg-info">Izin</span>
                            @else
                                <span class="badge bg-warning text-dark">Sakit</span>
                            @endif
                        </td>

                        <td>{{ \Carbon\Carbon::parse($izin->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                        </td>

                        <td>{{ $izin->keterangan }}</td>

                        <td>
                          <div class="btn-group btn-group-sm">
                                    <a href="{{ asset('storage/surat_izin/' . $izin->file_bukti) }}" 
                                       class="btn btn-outline-primary"
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
                            @if ($izin->status_approval == 'Pending')
                                <span class="badge bg-secondary">Pending</span>
                            @elseif ($izin->status_approval == 'Disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>

                        <td>
                            <div class="btn-group" role="group">

                                {{-- Tombol Setujui --}}
                                <form action="{{ route('admin.izin.setujui', $izin->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Setujui</button>
                                </form>

                                {{-- Tombol Tolak (Membuka Modal) --}}
                                <button 
                                    class="btn btn-danger btn-sm ms-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTolak{{ $izin->id }}">
                                    Tolak
                                </button>

                            </div>
                        </td>
                    </tr>

                    {{-- ============================= --}}
                    {{-- MODAL TOLAK PER IZIN --}}
                    {{-- ============================= --}}
                    <div class="modal fade" id="modalTolak{{ $izin->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Tolak Pengajuan Izin</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('admin.izin.tolak', $izin->id) }}" method="POST">
                                    @csrf

                                    <div class="modal-body">
                                        <label class="form-label">Alasan Penolakan</label>

                                        <textarea 
                                            name="alasan_penolakan" 
                                            rows="4" 
                                            class="form-control"
                                            placeholder="Tuliskan alasan kenapa pengajuan ini ditolak..."
                                            required></textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" 
                                                class="btn btn-secondary" 
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-danger">
                                            Kirim Penolakan
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    {{-- END MODAL --}}

                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-3">
                            Tidak ada pengajuan izin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

@endsection
