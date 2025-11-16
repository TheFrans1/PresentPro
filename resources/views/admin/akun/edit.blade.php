@extends('layouts.admin')

@section('title', 'Edit Akun Karyawan')
@section('page-title')
    Edit Akun: {{ $user->nama }}
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.akun.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK (4 Digit) - *Sekaligus Username*</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $user->nik) }}" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan (Divisi)</label>
                        <select name="jabatan" id="jabatan" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($jabatanOptions as $jabatan)
                                @if($jabatan != 'Administrator')
                                    <option value="{{ $jabatan }}" {{ old('jabatan', $user->jabatan) == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" class="form-control" value="********" disabled readonly>
                <small class="form-text text-muted">Password hanya bisa diubah melalui tombol "Reset Password".</small>
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update Akun</button>
            </div>
        </form>

    </div>
</div>
@endsection