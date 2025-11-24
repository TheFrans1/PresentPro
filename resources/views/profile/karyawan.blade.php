@extends('layouts.karyawan') 
@section('title', 'Profil Saya')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h5 class="m-0">Pengaturan Profil</h5>
    </div>

    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Nama --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" 
                        value="{{ old('nama', $user->nama) }}" required>
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                        value="{{ old('email', $user->email) }}" required>
                </div>

                {{-- Nomor HP --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" 
                        value="{{ old('no_hp', $user->no_hp) }}">
                </div>

                {{-- Jabatan / Divisi --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jabatan / Divisi</label>
                    <select name="jabatan" class="form-select" required>
                        @foreach ($divisiList as $div)
                            <option value="{{ $div }}" 
                                {{ $user->jabatan == $div ? 'selected' : '' }}>
                                {{ $div }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Password baru --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label">Password Baru <small>(opsional)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Isi jika ingin mengganti password">
                </div>

            </div>

            <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
        </form>
    </div>
</div>

@endsection
