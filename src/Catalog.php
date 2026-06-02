<?php

namespace App;

class Catalog
{
    private $fileProduk;

    public function __construct($fileProduk)
    {
        $this->fileProduk = $fileProduk;
    }

    public function getAllProducts()
    {
        if (!file_exists($this->fileProduk)) {
            return [];
        }

        return json_decode(
            file_get_contents($this->fileProduk),
            true
        ) ?? [];
    }

    // SEARCH PRODUK
    public function searchProduct($keyword)
    {
        $products = $this->getAllProducts();

        if (empty($keyword)) {
            return $products;
        }

        $results = [];

        foreach ($products as $kode => $item) {

            if (stripos($item['nama'], $keyword) !== false) {

                $results[$kode] = $item;
            }
        }

        return $results;
    }

    // TAMBAH / EDIT PRODUK
    public function saveProduct($kode, $nama, $harga, $stok)
    {
        $products = $this->getAllProducts();

        $products[$kode] = [
            'nama'  => htmlspecialchars($nama),
            'harga' => (int)$harga,
            'stok'  => (int)$stok
        ];

        file_put_contents(
            $this->fileProduk,
            json_encode($products, JSON_PRETTY_PRINT)
        );

        return true;
    }

    // HAPUS PRODUK
    public function deleteProduct($kode)
    {
        $products = $this->getAllProducts();

        if (isset($products[$kode])) {

            unset($products[$kode]);
        }

        file_put_contents(
            $this->fileProduk,
            json_encode($products, JSON_PRETTY_PRINT)
        );

        return true;
    }
}