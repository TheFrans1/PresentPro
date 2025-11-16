@extends('layouts.admin')

@section('title', 'Tambah Akun Karyawan')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h5 class="m-0">Tambah Akun Karyawan</h5>
    </div>
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

        <form action="{{ route('admin.akun.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK (4 Digit) - *Sekaligus Username*</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan (Divisi)</label>
                        <select name="jabatan" id="jabatan" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($jabatanOptions as $jabatan)
                                @if($jabatan != 'Administrator') 
                                    <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" minlength="11" maxlength="12" pattern="[0-9]+" inputmode="numeric" placeholder="Contoh: 081234567890" required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Default</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Akun</button>
            </div>
        </form>

    </div>
</div>
@endsection