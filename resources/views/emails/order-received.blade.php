<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande #CMD-{{ $order->id }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 24px; color: #18181b; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .header { background: #000000; color: #ffffff; padding: 28px 24px; text-align: center; }
        .header h1 { margin: 0 0 4px; font-size: 20px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 0; font-size: 13px; color: #a1a1aa; }
        .badge { display: inline-block; background: #ec4899; color: #ffffff; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 4px 10px; border-radius: 9999px; margin-top: 8px; }
        .content { padding: 24px; }
        .card { background: #fafafa; border: 1px solid #f4f4f5; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
        .card-title { font-size: 12px; font-weight: 800; text-transform: uppercase; color: #71717a; margin-bottom: 12px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px; }
        .info-label { color: #71717a; font-weight: 500; }
        .info-val { font-weight: 700; color: #18181b; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 13px; }
        .table th { text-align: left; padding: 8px; border-bottom: 2px solid #f4f4f5; color: #71717a; font-size: 11px; text-transform: uppercase; font-weight: 700; }
        .table td { padding: 10px 8px; border-bottom: 1px solid #f4f4f5; vertical-align: middle; }
        .totals { margin-top: 16px; border-top: 2px solid #f4f4f5; padding-top: 12px; }
        .totals-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
        .totals-row.final { font-size: 16px; font-weight: 900; color: #db2777; margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e4e4e7; }
        .footer { background: #fafafa; border-top: 1px solid #f4f4f5; padding: 16px 24px; text-align: center; font-size: 11px; color: #a1a1aa; }
        .cta-btn { display: inline-block; background: #25D366; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 12px; padding: 10px 18px; border-radius: 8px; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>zizo aura &bull; Nouvelle Commande</h1>
            <p>Commande <strong>#CMD-{{ $order->id }}</strong> &bull; Reçue le {{ $order->created_at ? $order->created_at->format('d/m/Y à H:i') : date('d/m/Y à H:i') }}</p>
            <span class="badge">Paiement à la livraison</span>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Customer Info -->
            <div class="card">
                <div class="card-title">Coordonnées du Client</div>
                <div style="font-size: 14px; line-height: 1.6;">
                    <p style="margin: 0 0 4px;"><strong>Nom :</strong> {{ $order->customer_name }}</p>
                    <p style="margin: 0 0 4px;"><strong>Téléphone :</strong> <a href="tel:{{ $order->customer_phone }}" style="color: #0284c7; text-decoration: none; font-weight: bold;">{{ $order->customer_phone }}</a></p>
                    @if(!empty($order->customer_email))
                        <p style="margin: 0 0 4px;"><strong>Email :</strong> {{ $order->customer_email }}</p>
                    @endif
                    <p style="margin: 0 0 4px;"><strong>Ville :</strong> {{ $order->city }}</p>
                    <p style="margin: 0 0 4px;"><strong>Adresse complète :</strong> {{ $order->shipping_address }}</p>
                    @if(!empty($order->notes))
                        <p style="margin: 4px 0 0; background: #fff; padding: 8px; border-radius: 6px; border: 1px solid #e4e4e7;"><strong>Notes :</strong> {{ $order->notes }}</p>
                    @endif
                </div>
                <div style="margin-top: 12px;">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" class="cta-btn" target="_blank">
                        Contacter le client sur WhatsApp &rarr;
                    </a>
                </div>
            </div>

            <!-- Ordered Items -->
            <div class="card">
                <div class="card-title">Articles Commandés ({{ $order->items->count() }})</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th style="text-align: center;">Qté</th>
                            <th style="text-align: right;">Prix unit.</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product_name }}</strong>
                                    @if(!empty($item->variant))
                                        <div style="font-size: 11px; color: #71717a;">{{ $item->variant }}</div>
                                    @endif
                                </td>
                                <td style="text-align: center; font-weight: bold;">x{{ $item->quantity }}</td>
                                <td style="text-align: right;">{{ number_format($item->unit_price, 0) }} DH</td>
                                <td style="text-align: right; font-weight: bold;">{{ number_format($item->subtotal, 0) }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Totals -->
                <div class="totals">
                    <div class="totals-row">
                        <span style="color: #71717a;">Sous-total</span>
                        <span>{{ number_format($order->subtotal, 0) }} DH</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="totals-row" style="color: #10b981; font-weight: bold;">
                            <span>Remise code promo ({{ $order->coupon_code }})</span>
                            <span>-{{ number_format($order->discount_amount, 0) }} DH</span>
                        </div>
                    @endif
                    <div class="totals-row">
                        <span style="color: #71717a;">Frais de livraison</span>
                        <span>{{ number_format($order->shipping_cost, 0) }} DH</span>
                    </div>
                    <div class="totals-row final">
                        <span>Total à encaisser (COD)</span>
                        <span>{{ number_format($order->total, 0) }} DH</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 4px;"><strong>zizo aura Boutique Officielle</strong> &bull; Vente &amp; Distribution Cosmétique Maroc</p>
            <p style="margin: 0;">Ce message a été généré automatiquement lors de la validation d'une commande en ligne.</p>
        </div>
    </div>
</body>
</html>
