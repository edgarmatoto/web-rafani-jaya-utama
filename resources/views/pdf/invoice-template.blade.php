<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header, .footer {
            width: 100%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-table-header {
            font-weight: bold;
            border-top: 1px solid black;
            border-bottom: 2px solid black;
            text-align: left;
        }

        .logo {
            height: 120px;
        }

        .bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

    </style>
</head>
<body>
<table class="header">
    <tbody>
    <tr>
        <td>
            <img src="{{ public_path('images/logo.jpg') }}" class="logo" alt="logo">
        </td>
        <div style="position: absolute; top: 100px; left: 115px; text-align: center;">
            Makassar
            <div style="height: 5px"></div>
            No. WA : 081244597198
        </div>
        <td style="width: 64%;">
            <table class="no-border">
                <tbody>
                <div style="height: 37px"></div>
                <tr>
                    <td>No. Transaksi</td>
                    <td>: {{$order->receipt_number}}</td>
                    <td style="padding-left: 100px;">User</td>
                    <td>: ADMIN</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $order->created_at->format('d/m/Y') }}</td>
                    <td style="padding-left: 100px;">PPN</td>
                    <td>: Include</td>
                </tr>
                <tr>
                    <td>Kode Sales</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td>: {{$order->customer_name}}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>: {{$order->customer_address}}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<br>

<table style="text-align: left; border-bottom: 1px solid black" class="table">
    <thead>
    <tr class="item-table-header">
        <th style="width: 5%; text-align: left;">No.</th>
        <th style="width: 10%; text-align: left;">Kode Item</th>
        <th style="text-align: left;">Nama Item</th>
        <th style="width: 12%; text-align: left;">Jumlah</th>
        <th style="width: 14%; text-align: left;">Harga</th>
        <th style="width: 10px;"></th>
        <th style="width: 14%; text-align: left;">Potongan</th>
        <th style="width: 30px;"></th>
        <th style="width: 14%; text-align: left;">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items as $index => $orderItem)
        <tr style="text-align: left;">
            <td>{{ $index + 1 }}</td>
            <td>{{ $orderItem->item->code }}</td>
            <td>
                {{ $orderItem->item->name }}
            </td>
            <td>{{ $orderItem->qty }}</td>
            <td>Rp <span style="float: right">{{ number_format($orderItem->item->price, 0, ',', '.') }}</span></td>
            <td></td>
            <td>Rp <span style="float: right">{{ number_format($orderItem->discount_amount, 0, ',', '.') }}</span></td>
            <td></td>
            <td>Rp <span style="float: right">{{ number_format($orderItem->subtotal, 0, ',', '.') }}</span></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="9" style="height: 16px"></td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 30%;">
            <table>
                <tbody>
                <tr>
                    <td>Keterangan:</td>
                </tr>
                <br>
                <tr>
                    <td class="text-center"> Hormat Kami:<br><br><br><br>(.....................)</td>
                    <td class="text-center padding-left" style="padding: 0 40px 0 40px;">Penerima:<br><br><br><br>(.....................)
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td style="width: 30%;">
            <table style="width: 80%;">
                <tbody>
                <tr>
                    @php
                        $sumItem = $order->items->sum(fn($item) => $item->qty);
                    @endphp
                    <td>Jumlah Item</td>
                    <td class="text-right">{{ $sumItem }}</td>
                </tr>
                <tr>
                    <td>Potongan</td>
                    <td>Rp <span style="float: right">{{ number_format($order->items->sum('discount_amount'), 0, ',', '.') }}</span></td>
                </tr>
                <tr>
                    <td>Pajak</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td>Biaya Lain</td>
                    <td class="text-right">-</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td style="width: 30%;">
            <table style="float: right">
                <tbody>
                <tr class="bold">
                    <td>Sub Total :</td>
                </tr>
                <tr>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<br><br>
@php
    use App\Helpers\Helper;

    $terbilang = Helper::terbilang((int) $order->total_price)
@endphp
<p>Terbilang: {{ $terbilang }} rupiah</p>

<br>

<table class="no-border">
    <tbody>
    <tr>
        <td>
            <strong>
                BRI<br>
                0343-01-002458-30-0<br>
                CV. RAFANI JAYA UTAMA
            </strong>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
