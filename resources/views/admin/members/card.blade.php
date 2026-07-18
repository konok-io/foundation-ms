<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member ID Card - {{ $member->member_id }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .member-card {
            width: 320px;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            page-break-inside: avoid;
        }
        .member-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .member-card::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -30px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        .logo {
            font-weight: bold;
            font-size: 14px;
        }
        .member-id {
            font-size: 12px;
            background: rgba(255,255,255,0.2);
            padding: 3px 10px;
            border-radius: 20px;
        }
        .card-body {
            display: flex;
            gap: 15px;
            position: relative;
            z-index: 1;
        }
        .photo {
            width: 80px;
            height: 100px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info {
            flex: 1;
        }
        .name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            line-height: 1.2;
        }
        .designation {
            font-size: 11px;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        .details {
            font-size: 10px;
            line-height: 1.5;
            opacity: 0.85;
        }
        .qr-code {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: white;
            padding: 5px;
            border-radius: 5px;
        }
        .qr-code img {
            width: 50px;
            height: 50px;
        }
        .card-footer {
            position: absolute;
            bottom: 15px;
            left: 20px;
            font-size: 8px;
            opacity: 0.7;
        }
        .print-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-btn:hover {
            background: #5568d3;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-btn {
                display: none;
            }
            .member-card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">
        <i class="bi bi-printer"></i> Print ID Card
    </button>
    
    <div class="card-container">
        <div class="member-card">
            <div class="card-header">
                <div class="logo">
                    <div style="font-size: 10px;">FOUNDATION MANAGEMENT</div>
                    <div style="font-size: 8px;">SYSTEM</div>
                </div>
                <div class="member-id">{{ $member->member_id }}</div>
            </div>
            
            <div class="card-body">
                <div class="photo">
                    <img src="{{ $member->photo ? asset('storage/members/' . $member->photo) : asset('images/avatar.png') }}" alt="{{ $member->name }}">
                </div>
                <div class="info">
                    <div class="name">{{ $member->name }}</div>
                    <div class="designation">{{ $memberTypes[$member->member_type] ?? 'Member' }}</div>
                    <div class="details">
                        @if($member->occupation)
                        <div><i class="bi bi-briefcase"></i> {{ $member->occupation }}</div>
                        @endif
                        @if($member->mobile)
                        <div><i class="bi bi-telephone"></i> {{ $member->mobile }}</div>
                        @endif
                        @if($member->blood_group)
                        <div><i class="bi bi-droplet"></i> {{ $member->blood_group }}</div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($member->member_id) }}" alt="QR Code">
            </div>
            
            <div class="card-footer">
                Valid: {{ date('M Y') }} - {{ date('M Y', strtotime('+1 year')) }}
            </div>
        </div>
    </div>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
