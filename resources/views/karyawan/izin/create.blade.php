@extends('layouts.karyawan')

@section('title', 'Buat Pengajuan Izin/Sakit')

@section('content')

<div class="row justify-content-center"> 
    <div class="col-md-8"> <h1 class="h3 mb-4 text-center">Form Pengajuan Izin/Sakit</h1>

        <div class="card shadow">
            <div class="card-body">
                
                <form action="{{ route('karyawan.izin.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf 

                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Pengajuan</label>
                        <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Izin" {{ old('jenis') == 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Sakit" {{ old('jenis') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                        @error('jenis')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="text" class="form-control datepicker-dmy @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" placeholder="dd/mm/yyyy" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="text" class="form-control datepicker-dmy @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" placeholder="dd/mm/yyyy" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan (Alasan)</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file_bukti" class="form-label">Upload File Bukti</label>
                        <input class="form-control @error('file_bukti') is-invalid @enderror" type="file" id="file_bukti" name="file_bukti" required>
                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Maksimal 2MB.</small>
                        @error('file_bukti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('karyawan.dashboard') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection