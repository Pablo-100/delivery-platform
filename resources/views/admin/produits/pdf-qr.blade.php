<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>QR Code - {{ $produit->nom }}</title>
    <style>
        body { font-family: sans-serif; text-align: center; }
        .container { border: 2px dashed #333; padding: 20px; display: inline-block; margin-top: 50px; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .qr { margin: 20px 0; }
        .details { font-size: 14px; text-align: left; margin-top: 20px; }
        .footer { font-size: 10px; margin-top: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">COLIS À LIVRER</div>
        <div class="subtitle">{{ $produit->nom }}</div>
        
        <div class="qr">
            <!-- SVG Inline (Ne dépend pas d'Imagick) -->
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($produit->qr_code) !!}
        </div>
        
        <div class="details">
            <strong>Destinataire:</strong> {{ $produit->destinataire_nom }} {{ $produit->destinataire_prenom }}<br>
            <strong>Destination:</strong> {{ $produit->destination }}<br>
            <strong>Poids:</strong> {{ $produit->poids }} kg<br>
            <strong>ID Colis:</strong> {{ $produit->qr_code }}
        </div>

        <div class="footer">
            Delivery Platform Inc.
        </div>
    </div>
</body>
</html>
