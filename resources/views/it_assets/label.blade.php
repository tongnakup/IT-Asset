<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Asset Label - {{ $itAsset->asset_number }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: sans-serif; margin: 1cm; }
        .label-container {
            border: 2px solid black;
            padding: 10px;
            width: 320px; /* ขยายความกว้างเล็กน้อย */
            height: 180px; /* เพิ่มความสูงเล็กน้อย */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .info { text-align: left; font-size: 0.8em; } /* ลดขนาด font เล็กน้อย */
        .info h3 { margin: 0 0 8px; font-size: 1.1em; }
        .info p { margin: 4px 0; }
        .qr-code { text-align: right; }
    </style>
</head>
<body onload="window.print()">

    <div class="label-container">
        <div class="info">
            <h3>ANJI-NYK</h3>
            <p><strong>Asset No:</strong> {{ $itAsset->asset_number }}</p>
            <p><strong>Type:</strong> {{ $itAsset->type }}</p>
            <p><strong>User:</strong> {{ $itAsset->employee?->first_name ?? 'N/A' }}</p>
            <p><strong>Location:</strong> {{ $itAsset->location ?? 'N/A' }}</p> {{-- <-- เพิ่ม --}}
            <p><strong>Start:</strong> {{ \Carbon\Carbon::parse($itAsset->start_date)->format('d/m/Y') }}</p> {{-- <-- เพิ่ม --}}
            <p><strong>End:</strong> {{ $itAsset->end_date ? \Carbon\Carbon::parse($itAsset->end_date)->format('d/m/Y') : 'N/A' }}</p> {{-- <-- เพิ่ม --}}
        </div>
        <div class="qr-code">
            {!! QrCode::size(110)->generate($itAsset->asset_number) !!}
        </div>
    </div>

</body>
</html>