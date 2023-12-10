{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Terimakasih telah belanja di toko kami #{{ $data->order_id }}</h1>
    <p class="">Berikut adalah pesanan anda</p>
    <table>
        <tr>
            <th>Nama Barang</th>
            <td>{{ $data->product->name }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $data->qty }}</td>
        </tr>
        <tr>
            <th>Harga</th>
            <td>{{ $data->product->price }}</td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td>{{ $data->total_price }}</td>
        </tr>
    </table>
</body>

</html>
