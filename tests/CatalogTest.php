<?php

use PHPUnit\Framework\TestCase;
use App\Catalog;

require_once __DIR__ . '/../src/Catalog.php';

class CatalogTest extends TestCase
{
    private $katalog;

    private $testFile = __DIR__ . '/test_products.json';

    // Setup data dummy sebelum test
    protected function setUp(): void
    {
        $dummyData = [
            "PRD-1" => [
                "nama" => "Kemeja Flanel",
                "harga" => 150000,
                "stok" => 10
            ],

            "PRD-2" => [
                "nama" => "Celana Jeans",
                "harga" => 250000,
                "stok" => 5
            ]
        ];

        file_put_contents(
            $this->testFile,
            json_encode($dummyData)
        );

        $this->katalog = new Catalog($this->testFile);
    }

    // Test pencarian produk
    public function testSearchProductFound()
    {
        $result = $this->katalog->searchProduct("Kemeja");

        $this->assertCount(1, $result);
    }

    // Test keyword kosong
    public function testSearchEmptyKeyword()
    {
        $result = $this->katalog->searchProduct("");

        $this->assertNotEmpty($result);
    }

    // Hapus file dummy setelah test
    protected function tearDown(): void
    {
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }
    }
}