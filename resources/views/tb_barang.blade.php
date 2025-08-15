<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Barang
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/paper-dashboard.css?v=2.0.1')}}" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('assets/demo/demo.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <style>
        table td, table th {
            padding: 10px 15px; /* Tambahkan padding sesuai kebutuhan */
        }
        .btn:hover {
            transform: scale(1.01);
            transition: transform 0.2s;
        }
    </style>
</head>

<body class="">
    <div class="wrapper ">
        <div class="sidebar" data-color="white" data-active-color="danger">


            @include('sidebar')

        </div>
        <div class="main-panel">
            <!-- Navbar -->
            @include('navbar')
            <!-- End Navbar -->
            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Data Barang <?php echo asset('public');?></h4>
                                <button type="button" class="btn btn-primary mr-4" data-toggle="modal" data-target="#addBarangModal">
                                    <i class="nc-icon nc-simple-add"></i>
                                    &nbsp; Tambah Barang
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                            <th class="text-center">No </th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Gambar</th>
                                            <th class="text-center">Kondisi</th>
                                            <th class="text-center">Jenis</th>
                                            <th class="text-center">Aksi</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($barangs as $index => $barang)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $barang->nama_barang }}</td>
                                                <td class="text-center">
                                                    @if ($barang->gambar)
                                                        <img src="{{ asset('images/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" style="width: 100px; height: auto;">
                                                    @else
                                                        Tidak Ada Gambar
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $barang->kondisi }}</td>
                                                <td class="text-center">{{ $barang->jenis }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-info btn-sm" title="Detail" data-toggle="modal" data-target="#detailBarangModal"
                                                    data-nama="{{ $barang->nama_barang }}"
                                                    data-kondisi="{{ $barang->kondisi }}"
                                                    data-jenis="{{ $barang->jenis }}"
                                                    data-gambar="{{ asset('images/' . $barang->gambar) }}"
                                                    data-deskripsi="{{ $barang->deskripsi ?? 'Tidak ada deskripsi' }}">
                                                        <i class="fas fa-info-circle"></i> <!-- Ikon info -->
                                                    </a>

                                                    <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editBarangModal{{ $barang->id }}" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i> <!-- Ikon pencil -->
                                                    </a>

                                                    <form id="delete-form-{{ $barang->id }}" action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $barang->id }})" title="Delete">
                                                            <i class="fas fa-trash-alt"></i> <!-- Ikon trash-alt -->
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Barang -->
            <div class="modal fade" id="addBarangModal" tabindex="-1" role="dialog" aria-labelledby="addBarangModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="addBarangModalLabel">Tambah Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="addBarangForm" action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_barcode">Kode Barcode</label>
                                    <input type="text" class="form-control" id="kode_barcode" name="kode_barcode" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                                </div>
                                <div class="form-group">
                                    <label for="kondisi">Kondisi</label>
                                    <input type="text" class="form-control" id="kondisi" name="kondisi" required>
                                </div>
                                
                                 <div class="form-group">
                                    <label for="stock">Jumlah Stock</label>
                                    <input type="text" class="form-control" id="jumlah_stock" name="jumlah_stock"  required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis">Jenis</label>
                                    <select class="form-control" id="jenis" name="jenis" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="properti">Properti</option>
                                        <option value="perkakas">Perkakas</option>
                                    </select>
                                </div>
                                
                                <div class="form-group-file">
                                    <label for="gambar">Gambar</label>
                                    <input type="file" class="form-control-file" id="gambar" name="gambar" accept="image/*" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                
                                <input type="hidden" class="form-control" id="id_petugas" name="id_petugas" required>
                                <div class="w-100 mb-2">
                                    <small class="text-muted">ID Petugas: <span id="petugas-display">Belum dipilih</span></small>
                                </div>
                                <button type="button" class="btn btn-warning" id="startScanner">
                                    <i class="fas fa-qrcode"></i> Scan QR/Barcode Petugas
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
           
            <!-- Modal Scanner QR/Barcode -->
            <div class="modal fade" id="scannerModal" tabindex="-1" role="dialog" aria-labelledby="scannerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title text-white" id="scannerModalLabel">
                                <i class="fas fa-qrcode"></i> Scan QR Code / Barcode Petugas
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Scanner Type Selection -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                        <label class="btn btn-outline-primary active">
                                            <input type="radio" name="scanType" id="qrCode" value="qr" checked> 
                                            <i class="fas fa-qrcode"></i> QR Code
                                        </label>
                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="scanType" id="barCode" value="barcode"> 
                                            <i class="fas fa-barcode"></i> Barcode
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Scanner Container -->
                            <div class="scanner-container">
                                <div id="qr-reader"></div>
                                <div class="scanner-overlay"></div>
                            </div>

                            <!-- Manual Input Option -->
                            <div class="mt-3">
                                <div class="form-group">
                                    <label for="manualInput">Atau masukkan ID Petugas secara manual:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="manualInput" placeholder="Masukkan ID Petugas">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success" id="useManualInput">
                                                <i class="fas fa-check"></i> Gunakan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Scan Result -->
                            <div id="scanResult" style="display: none;" class="scanner-result">
                                <h6><i class="fas fa-check-circle text-success"></i> Hasil Scan:</h6>
                                <p class="mb-2"><strong>ID Petugas:</strong> <span id="scannedData"></span></p>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm btn-scanner-action" id="acceptScan">
                                        <i class="fas fa-check"></i> Gunakan ID Ini
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm btn-scanner-action" id="scanAgain">
                                        <i class="fas fa-redo"></i> Scan Lagi
                                    </button>
                                </div>
                            </div>

                            <!-- Camera Selection -->
                            <div class="mt-3">
                                <div class="form-group">
                                    <label for="cameraSelect">Pilih Kamera:</label>
                                    <select class="form-control" id="cameraSelect">
                                        <option value="">Loading cameras...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

           

            <!-- Modal Edit Barang -->
            @foreach ($barangs as $barang)
            <div class="modal fade" id="editBarangModal{{ $barang->id }}" tabindex="-1" role="dialog" aria-labelledby="editBarangModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="editBarangModalLabel">Edit Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_barcode">Kode Barcode</label>
                                    <input type="text" class="form-control" id="kode_barcode" name="kode_barcode" value="{{ $barang->kode_barcode }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barang->nama_barang }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="kondisi">Kondisi</label>
                                    <input type="text" class="form-control" id="kondisi" name="kondisi" value="{{ $barang->kondisi }}" required>
                                </div>
                                 <div class="form-group">
                                    <label for="stock">Jumlah Stock</label>
                                    <input type="text" class="form-control" id="jumlah_stock" name="jumlah_stock" value="{{ $barang->jumlah_stock }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis">Jenis</label>
                                    <select class="form-control" id="jenis" name="jenis" required>
                                        <option value="properti" {{ $barang->jenis == 'properti' ? 'selected' : '' }}>Properti</option>
                                        <option value="perkakas" {{ $barang->jenis == 'perkakas' ? 'selected' : '' }}>Perkakas</option>
                                    </select>
                                </div>
                                <div class="form-group-file">
                                    <label for="gambar">Gambar</label><br>
                                    @if ($barang->gambar)
                                        <img src="{{ asset('images/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" style="width: 100px; height: auto;"><br>
                                    @endif
                                    <input type="file" class="form-control-file mt-2" id="gambar" name="gambar" accept="image/*">
                                    <small>*Unggah jika ingin mengganti gambar</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach



            <!-- Modal Detail Barang -->
            <div class="modal fade" id="detailBarangModal" tabindex="-1" role="dialog" aria-labelledby="detailBarangModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailBarangModalLabel"><strong>Detail Barang</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <img id="barangImage" src="" alt="Gambar Barang" class="img-fluid" style="max-height: 200px;">
                            </div>
                            <div class="mt-3">
                                <p><strong>Nama Barang:</strong> <span id="barangNama"></span></p>
                                <p><strong>Kondisi:</strong> <span id="barangKondisi"></span></p>
                                <p><strong>Jenis:</strong> <span id="barangJenis"></span></p>
                                <p><strong>Deskripsi:</strong> <span id="barangDeskripsi"></span></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>




            {{-- footer --}}
            @include('footer')
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!-- Chart JS -->
    <script src="{{ asset('assets/js/plugins/chartjs.min.js')}}"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('assets/js/plugins/bootstrap-notify.js')}}"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/paper-dashboard.min.js?v=2.0.1')}}" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    <script src="{{ asset('assets/demo/demo.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
        class QRBarcodeScanner {
            constructor() {
                this.html5QrCode = null;
                this.cameras = [];
                this.currentCamera = null;
                this.isScanning = false;
                this.scanType = 'qr';
                
                this.init();
            }
            
            init() {
                // Event listeners
                $('#startScanner').on('click', () => this.openScannerModal());
                $('#scannerModal').on('shown.bs.modal', () => this.startScanner());
                $('#scannerModal').on('hidden.bs.modal', () => this.stopScanner());
                
                // Scan type change
                $('input[name="scanType"]').on('change', (e) => {
                    this.scanType = e.target.value;
                    this.restartScanner();
                });
                
                // Camera selection change
                $('#cameraSelect').on('change', (e) => {
                    this.currentCamera = e.target.value;
                    this.restartScanner();
                });
                
                // Manual input
                $('#useManualInput').on('click', () => this.useManualInput());
                $('#manualInput').on('keypress', (e) => {
                    if (e.which === 13) { // Enter key
                        this.useManualInput();
                    }
                });
                
                // Scan result actions
                $('#acceptScan').on('click', () => this.acceptScanResult());
                $('#scanAgain').on('click', () => this.scanAgain());
                
                // Load cameras on page load
                this.loadCameras();
            }
            
            async loadCameras() {
                try {
                    const devices = await Html5Qrcode.getCameras();
                    this.cameras = devices;
                    
                    const cameraSelect = $('#cameraSelect');
                    cameraSelect.empty();
                    
                    if (devices.length === 0) {
                        cameraSelect.append('<option value="">No cameras found</option>');
                        return;
                    }
                    
                    devices.forEach((device, index) => {
                        const label = device.label || `Camera ${index + 1}`;
                        const selected = index === 0 ? 'selected' : '';
                        cameraSelect.append(`<option value="${device.id}" ${selected}>${label}</option>`);
                    });
                    
                    this.currentCamera = devices[0].id;
                    
                } catch (error) {
                    console.error('Error loading cameras:', error);
                    $('#cameraSelect').html('<option value="">Error loading cameras</option>');
                }
            }
            
            openScannerModal() {
                $('#scannerModal').modal('show');
            }
            
            async startScanner() {
                if (this.isScanning || !this.currentCamera) return;
                
                try {
                    this.html5QrCode = new Html5Qrcode("qr-reader");
                    
                    const config = this.getScanConfig();
                    
                    await this.html5QrCode.start(
                        this.currentCamera,
                        config,
                        (decodedText, decodedResult) => {
                            this.onScanSuccess(decodedText, decodedResult);
                        },
                        (errorMessage) => {
                            // Silent error handling for continuous scanning
                        }
                    );
                    
                    this.isScanning = true;
                    
                } catch (error) {
                    console.error('Error starting scanner:', error);
                    alert('Error starting scanner: ' + error.message);
                }
            }
            
            getScanConfig() {
                const baseConfig = {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };
                
                if (this.scanType === 'barcode') {
                    return {
                        ...baseConfig,
                        formatsToSupport: [
                            Html5QrcodeSupportedFormats.CODE_128,
                            Html5QrcodeSupportedFormats.CODE_39,
                            Html5QrcodeSupportedFormats.EAN_13,
                            Html5QrcodeSupportedFormats.EAN_8,
                            Html5QrcodeSupportedFormats.UPC_A,
                            Html5QrcodeSupportedFormats.UPC_E
                        ]
                    };
                }
                
                return baseConfig; // Default QR code scanning
            }
            
            async stopScanner() {
                if (this.html5QrCode && this.isScanning) {
                    try {
                        await this.html5QrCode.stop();
                        this.html5QrCode.clear();
                        this.html5QrCode = null;
                        this.isScanning = false;
                    } catch (error) {
                        console.error('Error stopping scanner:', error);
                    }
                }
            }
            
            async restartScanner() {
                await this.stopScanner();
                setTimeout(() => {
                    this.startScanner();
                }, 500);
            }
            
            onScanSuccess(decodedText, decodedResult) {
                // Stop scanning temporarily
                this.stopScanner();
                
                // Show scan result
                $('#scannedData').text(decodedText);
                $('#scanResult').show();
                
                // Auto-accept after 3 seconds if not manually handled
                setTimeout(() => {
                    if ($('#scanResult').is(':visible')) {
                        this.acceptScanResult();
                    }
                }, 3000);
            }
            
            acceptScanResult() {
                const scannedData = $('#scannedData').text();
                
                // Set the scanned data to hidden input
                $('#id_petugas').val(scannedData);
                $('#petugas-display').text(scannedData);
                
                // Close scanner modal
                $('#scannerModal').modal('hide');
                
                // Reset scan result display
                $('#scanResult').hide();
                $('#manualInput').val('');
                
                // Show success message
                this.showMessage('ID Petugas berhasil di-scan: ' + scannedData, 'success');
            }
            
            scanAgain() {
                $('#scanResult').hide();
                this.startScanner();
            }
            
            useManualInput() {
                const manualValue = $('#manualInput').val().trim();
                
                if (!manualValue) {
                    alert('Silakan masukkan ID Petugas terlebih dahulu.');
                    return;
                }
                
                // Set manual input to hidden field
                $('#id_petugas').val(manualValue);
                $('#petugas-display').text(manualValue);
                
                // Close scanner modal
                $('#scannerModal').modal('hide');
                
                // Reset inputs
                $('#manualInput').val('');
                $('#scanResult').hide();
                
                // Show success message
                this.showMessage('ID Petugas berhasil dimasukkan: ' + manualValue, 'success');
            }
            
            showMessage(message, type = 'info') {
                // Create toast notification
                const toastHtml = `
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" 
                        style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                        <div class="toast-header bg-${type}">
                            <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle text-white mr-2"></i>
                            <strong class="mr-auto text-white">Scanner</strong>
                            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `;
                
                $('body').append(toastHtml);
                $('.toast').last().toast({ delay: 4000 }).toast('show');
                
                // Remove toast after it's hidden
                $('.toast').last().on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }
        }

        // Initialize scanner when document is ready
        $(document).ready(function() {
            window.qrBarcodeScanner = new QRBarcodeScanner();
        });

        // Demo function for loading
        function showLoading() {
            // Prevent form submission for demo
            event.preventDefault();
            alert('Form akan disubmit dengan ID Petugas: ' + $('#id_petugas').val());
        }
        

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Kirim form setelah menampilkan loading
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    <script>
        function showLoading() {
            Swal.fire({
                title: 'Menambahkan...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }
    </script>

    <script>
        function showLoading() {
            Swal.fire({
                title: 'Mengupdate...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }
    </script>


    <script>
        $(document).on('click', '[data-toggle="modal"][data-target="#detailBarangModal"]', function () {
            $('#barangNama').text($(this).data('nama'));
            $('#barangKondisi').text($(this).data('kondisi'));
            $('#barangJenis').text($(this).data('jenis'));
            $('#barangImage').attr('src', $(this).data('gambar'));
            $('#barangDeskripsi').text($(this).data('deskripsi'));
        });
    </script>

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
    @endif





</body>

</html>
