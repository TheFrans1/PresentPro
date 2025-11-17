@extends('layouts.karyawan')
@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Notifikasi (WAJIB ADA untuk pesan success/error) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Akhir Notifikasi --}}


    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Absensi Hari Ini</h5>
                    <p class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>

                    {{-- ================================================== --}}
                    {{--         LOGIKA DINAMIS DIMULAI DI SINI           --}}
                    {{-- ================================================== --}}

                    @if ($isHariLibur)
                        {{-- CASE 1: HARI LIBUR --}}
                        <div class="alert alert-info mt-3">
                            <strong>Hari Libur</strong>
                            <p class="mb-0">Tidak ada jadwal kerja hari ini. Selamat beristirahat!</p>
                        </div>

                    @elseif ($izinHariIni)
                         {{-- CASE 2: SEDANG IZIN/SAKIT --}}
                        <div class="alert alert-warning mt-3">
                            <strong>Anda Sedang {{ $izinHariIni->jenis }}</strong>
                            <p class="mb-0">Status pengajuan Anda telah disetujui. Tidak perlu absen hari ini.</p>
                        </div>

                    @elseif (is_null($absenHariIni))
                        {{-- CASE 3: BELUM ABSEN MASUK --}}
                        <button class="btn btn-primary btn-lg" 
                                data-bs-toggle="modal" 
                                data-bs-target="#absenModal" 
                                data-mode="masuk"
                                data-title="Ambil Foto Selfie (Absen Masuk)"
                                data-action="{{ route('karyawan.absen.masuk') }}">
                            <i class="bi bi-camera-fill"></i> Ambil Absen Masuk
                        </button>
                        <br><br>
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="bi bi-box-arrow-right"></i> Ambil Absen Keluar
                        </button>

                    @elseif (is_null($absenHariIni->jam_keluar))
                        {{-- CASE 4: SUDAH MASUK, BELUM PULANG --}}
                        <div class="alert alert-success">
                            <strong>Sudah Absen Masuk</strong>
                            <p class="mb-0">Jam: <strong>{{ \Carbon\Carbon::parse($absenHariIni->jam_masuk)->format('H:i') }} WIB</strong></p>
                            
                            {{-- ================== PERUBAHAN DI SINI ================== --}}
                            <p class="mb-0">Status: <strong>{{ $absenHariIni->status_absensi }}</strong></p>
                            {{-- ======================================================= --}}

                        </div>
                        
                        <button class="btn btn-primary btn-lg" 
                                data-bs-toggle="modal" 
                                data-bs-target="#absenModal" 
                                data-mode="keluar"
                                data-title="Ambil Foto Selfie (Absen Keluar)"
                                data-action="{{ route('karyawan.absen.keluar') }}">
                            <i class="bi bi-box-arrow-right"></i> Ambil Absen Keluar
                        </button>
                    
                    @else
                        {{-- CASE 5: SUDAH MASUK DAN PULANG --}}
                        <div class="alert alert-success">
                            <strong>Absensi Selesai</strong>
                            <p class="mb-0">Masuk: <strong>{{ \Carbon\Carbon::parse($absenHariIni->jam_masuk)->format('H:i') }} WIB</strong></p>
                            <p class="mb-0">Pulang: <strong>{{ \Carbon\Carbon::parse($absenHariIni->jam_keluar)->format('H:i') }} WIB</strong></p>
                            <p class="mb-0">Sampai jumpa besok!</p>
                        </div>
                    @endif
                    
                    {{-- ================================================== --}}
                    {{--          LOGIKA DINAMIS SELESAI DI SINI          --}}
                    {{-- ================================================== --}}

                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            {{-- KODE AKSI CEPAT ANDA (TIDAK DIUBAH) --}}
            <div class="card shadow-sm h-100">
                <div class="card-header">Aksi Cepat</div>
                <div class="card-body text-center">
                    <p>Tidak bisa hadir hari ini?</p>
                    <a href="{{ route('karyawan.izin.create') }}" class="btn btn-warning btn-lg">
                        <i class="bi bi-journal-plus"></i> Buat Pengajuan Izin/Sakit
                    </a>
                </div>
            </div>
            {{-- AKHIR DARI KODE AKSI CEPAT --}}
        </div>
    </div>

{{-- ================================================== --}}
{{--        MODAL BARU HTML5 MURNI                    --}}
{{-- ================================================== --}}
<div class="modal fade" id="absenModal" tabindex="-1" aria-labelledby="absenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="absenModalLabel">Ambil Foto Selfie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="absenForm" method="POST"> 
                @csrf
                <input type="hidden" name="image" id="image_data">
                <div class="modal-body">
                    <div id="kamera-container">
                        <p class="text-center">Posisikan wajah Anda di depan kamera.</p>
                        {{-- ELEMEN VIDEO UNTUK LIVE STREAM (PENGGANTI WEBCAM.JS) --}}
                        <video id="webcam-stream" autoplay playsinline class="mx-auto d-block" style="width:320px; height:240px; border: 1px solid #ddd;"></video>
                        {{-- CANVAS UNTUK MENGAMBIL SNAPSHOT --}}
                        <canvas id="webcam-canvas" width="320" height="240" style="display: none;"></canvas>
                    </div>
                    
                    <div id="preview-container" style="display: none;">
                        <p class="text-center">Hasil Foto Anda:</p>
                        <img id="snapshot_result" class="mx-auto d-block" style="width:320px; height:240px; border: 1px solid #ddd;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="kamera-buttons">
                        <button type="button" class="btn btn-primary" id="take_snapshot">Ambil Foto</button>
                    </div>
                    <div id="preview-buttons" style="display: none;">
                        <button type="button" class="btn btn-secondary" id="retake_snapshot">Foto Ulang</button>
                        <button type="submit" class="btn btn-primary" id="submit_absen">Kirim Absen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    // --- SKRIP UNTUK MODAL DINAMIS (Tidak diubah) ---
    document.addEventListener('DOMContentLoaded', (event) => {
        const absenModal = document.getElementById('absenModal');
        if (absenModal) {
            absenModal.addEventListener('show.bs.modal', function (e) {
                const button = e.relatedTarget;
                const title = button.getAttribute('data-title');
                const action = button.getAttribute('data-action');
                
                const modalTitle = absenModal.querySelector('.modal-title');
                modalTitle.textContent = title;

                const modalForm = absenModal.querySelector('#absenForm');
                modalForm.setAttribute('action', action);
            });
        }
    });
</script>

{{-- ================================================== --}}
{{--      SKRIP KAMERA HTML5 MURNI (Webcam.js Dihapus)  --}}
{{-- ================================================== --}}
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        
        const absenModal = document.getElementById('absenModal');
        const video = document.getElementById('webcam-stream');
        const canvas = document.getElementById('webcam-canvas');
        const snapshotResult = document.getElementById('snapshot_result');
        const hiddenImageData = document.getElementById('image_data');
        const takeSnapshotBtn = document.getElementById('take_snapshot');
        const retakeSnapshotBtn = document.getElementById('retake_snapshot');
        const kameraContainer = document.getElementById('kamera-container');
        const previewContainer = document.getElementById('preview-container');
        const kameraButtons = document.getElementById('kamera-buttons');
        const previewButtons = document.getElementById('preview-buttons');
        
        let stream = null; // Variabel untuk menyimpan stream kamera

        // Fungsi untuk menyalakan kamera (dipanggil saat modal dibuka)
        function startCamera() {
            // Tampilkan container video
            video.style.display = 'block';

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } })
                    .then((s) => {
                        stream = s;
                        video.srcObject = s;
                        video.play();
                        console.log('DEBUG: Kamera HTML5 berhasil dinyalakan.');
                    })
                    .catch((err) => {
                        console.error("DEBUG: Gagal mengakses kamera:", err);
                        alert("Error: Gagal mengakses kamera. Pastikan browser Anda memiliki izin.");
                    });
            } else {
                alert('Browser Anda tidak mendukung fitur kamera.');
            }
        }

        // Fungsi untuk mematikan kamera (dipanggil saat foto diambil atau modal ditutup)
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
                video.srcObject = null; // Hapus stream dari elemen video
                video.style.display = 'none'; // Sembunyikan elemen video
                console.log('DEBUG: Kamera dimatikan.');
            }
        }

        // --- ATUR EVENT MODAL ---
        absenModal.addEventListener('shown.bs.modal', function () {
            // Mulai kamera saat modal terbuka
            startCamera();

            // Reset tampilan ke mode kamera
            kameraContainer.style.display = 'block';
            kameraButtons.style.display = 'block';
            previewContainer.style.display = 'none';
            previewButtons.style.display = 'none';
        });

        absenModal.addEventListener('hidden.bs.modal', function () {
            // Matikan kamera saat modal tertutup
            stopCamera();
        });

        // --- ATUR EVENT TOMBOL ---

        // Saat tombol "Ambil Foto" diklik:
        takeSnapshotBtn.addEventListener('click', function() {
            console.log('DEBUG: Tombol "Ambil Foto" DIKLIK. Mengambil snapshot...');
            
            // Pindahkan frame dari video ke canvas
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, 320, 240);
            
            // Hentikan stream video setelah foto diambil
            stopCamera(); 

            // Ambil data gambar dalam format Base64 (siap dikirim ke Controller)
            const dataURL = canvas.toDataURL('image/jpeg', 0.9);
            
            // Kirim data dan ubah tampilan
            snapshotResult.src = dataURL;
            hiddenImageData.value = dataURL; 

            kameraContainer.style.display = 'none';
            previewContainer.style.display = 'block';
            kameraButtons.style.display = 'none';
            previewButtons.style.display = 'block';
        });

        // Saat tombol "Foto Ulang" diklik:
        retakeSnapshotBtn.addEventListener('click', function() {
            console.log('DEBUG: Tombol "Foto Ulang" diklik. Mengaktifkan kembali kamera.');
            
            // Mulai kamera lagi
            startCamera();

            hiddenImageData.value = '';
            snapshotResult.src = '';
            
            kameraContainer.style.display = 'block';
            previewContainer.style.display = 'none';
            kameraButtons.style.display = 'block';
            previewButtons.style.display = 'none';
        });
        
    });
</script>
@endpush