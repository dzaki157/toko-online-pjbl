<?php

use PHPUnit\Framework\TestCase;
use App\Checkout;

require_once __DIR__ . '/../src/Checkout.php';

class CheckoutTest extends TestCase
{
    private $seedFile = __DIR__ . '/../data/products_seed.json';

    private $testFile = __DIR__ . '/../data/products_test.json';

    private $orderFile = __DIR__ . '/../data/orders_test.json';

    private $checkout;

    // Setup sebelum testing
    protected function setUp(): void
    {
        // Copy seed data
        copy($this->seedFile, $this->testFile);

        // Reset orders
        file_put_contents(
            $this->orderFile,
            json_encode([])
        );

        // Inisialisasi Checkout
        $this->checkout = new Checkout(
            $this->testFile,
            $this->orderFile
        );
    }

    // Test pengurangan stok setelah checkout
    public function testCheckoutReducesStock()
    {
        $keranjang = [
            'PRD-002' => 1
        ];

        // Checkout
        $this->checkout->prosesCheckout(
            'test@mail.com',
            'Jl. Sudirman',
            $keranjang
        );

        // Ambil isi file products_test.json
        $products = json_decode(
            file_get_contents($this->testFile),
            true
        );

        // Pastikan stok berkurang
        $this->assertEquals(
            4,
            $products['PRD-002']['stok']
        );
    }

    // Hapus file sementara setelah test
    protected function tearDown(): void
    {
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }

        if (file_exists($this->orderFile)) {
            unlink($this->orderFile);
        }
    }
}