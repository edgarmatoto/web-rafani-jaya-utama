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
            margin-bottom: 10px;
        }

        .header td {
            vertical-align: top;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid black;
            padding: 4px;
        }

        .no-border td {
            border: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .logo {
            height: 100px;
        }
    </style>
</head>
<body>
<table class="header">
    <tr>
        <td style="width: 40%;">
            <table class="no-border">
                <tr>
                    <td><img src="{{ public_path('images/logo.jpg') }}" class="logo" alt="logo"></td>
                </tr>
                <tr>
                    <td class="text-center">Makassar</td>
                </tr>
                <tr>
                    <td>No. WA : 081244597198</td>
                </tr>
            </table>
        </td>
        <td style="width: 60%;">
            <table class="no-border">
                <br/><br/><br/>
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
            </table>
        </td>
    </tr>
</table>

<br>

<table class="table">
    <thead>
    <tr class="bold">
        <th style="width: 5%;">No.</th>
        <th style="width: 15%;">Kode Item</th>
        <th>Nama Item</th>
        <th style="width: 10%;">Jumlah</th>
        <th style="width: 15%;">Harga</th>
        <th style="width: 15%;">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items as $index => $orderItem)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ $orderItem->item->code }}</td>
            <td>
                {{ $orderItem->item->name }}
            </td>
            <td class="text-center">{{ $orderItem->qty }}</td>
            <td class="text-right">Rp {{ number_format($orderItem->item->price, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="no-border" style="width: 100%;">
    <tr>
        <td style="width: 40%;">
            <table>
                <tr>
                    <td><strong>Keterangan:</strong></td>
                </tr>
                <br/>
                <tr>
                    <td class="text-center"> Hormat Kami:<br><br><br><br>(...............................)</td>
                    <td class="text-center padding-left" style="padding: 0 40px 0 40px;">Penerima:<br><br><br><br>(...............................)
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 30%;">
            <table class="no-border" style="width: 100%;">
                <tr>
                    @php
                        $sumItem = $order->items->sum(fn($item) => $item->qty);
                    @endphp
                    <td>Jumlah Item</td>
                    <td class="text-right">{{ $sumItem }}</td>
                </tr>
                <tr>
                    <td>Potongan</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td>Pajak</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td>Biaya Lain</td>
                    <td class="text-right">-</td>
                </tr>
            </table>
        </td>
        <td style="width: 30%;">
            <table style="float: right">
                <tr class="bold">
                    <td>Sub Total</td>
                </tr>
                <tr>
                    <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br><br>
@php
    use App\Helpers\Helper;

    $terbilang = Helper::terbilang((int) $order->total_price)
@endphp
<p>Terbilang: {{ $terbilang }} rupiah</p>

<br>

<table class="no-border">
    <tr>
        <td>
            <strong>
                BRI<br>
                0343-01-002458-30-0<br>
                CV. RAFANI JAYA UTAMA
            </strong>
        </td>
    </tr>
</table>

</body>
</html>
