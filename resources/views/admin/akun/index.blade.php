@extends('layouts.admin')

@section('title', 'Kelola Akun Karyawan')
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


    .table-scroll-wrapper {
      position: relative;
      max-height: 70vh; 
      overflow-y: auto;
    }

    .table-scroll-wrapper thead th {
      position: sticky;
      top: 0;
      z-index: 10;
      background-color: #212529; 
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
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="m-0">Daftar Akun Karyawan</h5>
            <form action="{{ route('admin.akun.index') }}" method="GET" class="d-flex g-2">
                
                 <input type="text" name="filter_nik" class="form-control form-control-sm me-2" placeholder="Cari NIK" value="{{ request('filter_nik') }}" style="width: 120px;">

                 <button type="submit" class="btn btn-primary btn-sm me-2">Cari</button>
                 
                <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary btn-sm me-2">Reset</a>

                <a href="{{ route('admin.akun.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Karyawan Baru
                </a>
            </form>
        </div>
    </div>
    <div class="card-body">

        <div class="table-scroll-wrapper"> <div class="table-responsive">
                <table class="table table-striped table-hover">
                    
                    <thead class="table-dark" position: sticky;>
                        <tr>
                            <th scope="col" class="text-center" style="width: 50px;">#</th>
                            <th scope="col" class="text-center">Nama</th>
                            <th scope="col" class="text-center" style="width: 100px;">NIK</th>
                            <th scope="col" class="text-center">Email</th>
                            <th scope="col" class="text-center">Jabatan</th>
                            <th scope="col" class="text-center">No. HP</th>
                            <th scope="col" class="text-center" style="width: 110px;">Status</th>
                            <th scope="col" class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                
                                <td>{{ $user->nama }}</td>
                                <td class="text-center">{{ $user->nik }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->jabatan }}</td>
                                <td>{{ $user->no_hp }}</td>
                                <td class="text-center">
                                    @if ($user->status == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.akun.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.akun.reset', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin me-reset password karyawan ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" title="Reset Password">
                                            <i class="bi bi-key-fill"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.akun.toggle', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mengubah status akun ini?');">
                                        @csrf
                                        <button type="submit" 
                                                class="btn {{ $user->status == 'aktif' ? 'btn-danger' : 'btn-success' }} btn-sm" 
                                                title="{{ $user->status == 'aktif' ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            
                                            <i class="bi {{ $user->status == 'aktif' ? 'bi-slash-circle-fill' : 'bi-check-circle-fill' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> </div> </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        let alert = document.querySelector('.alert-floating');
        
        if (alert) {
            let closeButton = alert.querySelector('.btn-close');
            
            if (closeButton) {
                setTimeout(() => {
                    closeButton.click();
                }, 4000);
            }
        }
    });
</script>

@endsection