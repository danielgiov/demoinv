<!DOCTYPE html>
<html>
<head>
    <title>QR Code - {{ $petugas->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .qr-container {
            text-align: center;
            padding: 30px;
        }
        .print-btn {
            margin: 20px 0;
        }
        @media print {
            .print-btn, .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>QR Code</h4>
                    </div>
                    <div class="card-body qr-container">
                        <h5>{{ $petugas->name }}</h5>
                        <p class="text-muted">{{ $petugas->email }}</p>
                        <p class="text-muted">Role: {{ $petugas->role }}</p>
                        
                        <div class="my-4">
                            {!! $qrCode !!}
                        </div>
                        
                        <!-- <small class="text-muted">Hash: {{ $hash }}</small> -->
                        
                        <div class="print-btn no-print">
                            <button class="btn btn-primary me-2" onclick="window.print()">
                                <i class="fa fa-print"></i> Print
                            </button>
                            <button class="btn btn-secondary" onclick="window.close()">
                                <i class="fa fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>