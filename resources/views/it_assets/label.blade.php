<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Asset Label - {{ $itAsset->asset_number }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: sans-serif;
            margin: 1cm;
        }

        .label-container {
            border: 2px solid black;
            padding: 10px;
            width: 320px;
            height: 180px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info {
            text-align: left;
            font-size: 0.8em;
        }

        .info h3 {
            margin: 0 0 8px;
            font-size: 1.1em;
        }

        .info p {
            margin: 4px 0;
        }

        .qr-code {
            text-align: right;
        }

        /* ▼▼▼ เพิ่ม CSS นี้เข้าไป ▼▼▼ */
        .qr-code svg {
            border: 1px solid #000000;
            padding: 5px;
            background-color: white;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="label-container">
        <div class="info">
            <h3>ANJI-NYK</h3>
            <p><strong>Asset No:</strong> {{ $itAsset->asset_number }}</p>
            <p><strong>Type:</strong> {{ $itAsset->assetType?->name ?? 'N/A' }}</p>
            <p><strong>User:</strong> {{ $itAsset->employee?->first_name ?? 'N/A' }}</p>
            <p><strong>Location:</strong> {{ $itAsset->location?->name ?? 'N/A' }}</p>
            <p><strong>Start:</strong>
                {{ $itAsset->purchase_date ? \Carbon\Carbon::parse($itAsset->purchase_date)->format('d/m/Y') : 'N/A' }}
            </p>
            <p><strong>End:</strong>
                {{ $itAsset->warranty_end_date ? \Carbon\Carbon::parse($itAsset->warranty_end_date)->format('d/m/Y') : 'N/A' }}
            </p>
        </div>
        <div class="qr-code">
            {{-- ตรวจสอบให้แน่ใจว่าคุณได้ติดตั้ง package `simple-qrcode` แล้ว --}}
            {!! QrCode::size(110)->generate($itAsset->asset_number) !!}
        </div>
    </div>

</body>

</html>
