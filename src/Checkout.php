<?php
namespace App;

use Exception;

class Checkout
{
    private $fileProduk;
    private $filePesanan;

    public function __construct($fileProduk, $filePesanan)
    {
        $this->fileProduk = $fileProduk;
        $this->filePesanan = $filePesanan;
    }

    public function prosesCheckout($email, $alamat, $keranjang)
    {
        if (empty($keranjang)) {
            throw new Exception('Keranjang kosong!');
        }

        $products = json_decode(file_get_contents($this->fileProduk), true);

        $total = 0;

        foreach ($keranjang as $kode => $qty) {
            if ($products[$kode]['stok'] < $qty) {
                throw new Exception('Stok tidak cukup!');
            }

            $total += $products[$kode]['harga'] * $qty;
            $products[$kode]['stok'] -= $qty;
        }

        $ongkir = 20000;

        if ($total > 500000) {
            $ongkir = 0;
        }

        $totalBayar = $total + $ongkir;

        $pesanan = [
            'id_pesanan' => uniqid('ORD-'),
            'email' => htmlspecialchars($email),
            'alamat' => htmlspecialchars($alamat),
            'items' => $keranjang,
            'total_bayar' => $totalBayar,
            'status' => 'Menunggu Pembayaran'
        ];

        file_put_contents($this->fileProduk, json_encode($products, JSON_PRETTY_PRINT));

        $orders = json_decode(file_get_contents($this->filePesanan), true) ?? [];
        $orders[] = $pesanan;

        file_put_contents($this->filePesanan, json_encode($orders, JSON_PRETTY_PRINT));

        return $pesanan;
    }
}
