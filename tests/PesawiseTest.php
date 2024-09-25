<?php

namespace Santosdave\PesawiseWrapper\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use Santosdave\PesawiseWrapper\Exceptions\PesawiseException;
use Santosdave\PesawiseWrapper\Pesawise;

class PesawiseTest extends TestCase
{
    protected $pesawise;
    protected $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $this->pesawise = new Pesawise([
            'apiKey' => 'test_api_key',
            'apiSecret' => 'test_api_secret',
            'env' => 'sandbox',
        ], $client);
    }

    public function testGetAllSupportedBanks()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            ['id' => 1, 'bankCode' => '001', 'bankName' => 'Test Bank']
        ])));

        $result = $this->pesawise->getAllSupportedBanks();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Test Bank', $result[0]['bankName']);
    }

    public function testCreateDirectPayment()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'paymentId' => 'test123',
            'status' => 'SUCCESS',
        ])));

        $result = $this->pesawise->createDirectPayment([
            'amount' => 1000,
            'currency' => 'KES',
            'reference' => 'TEST001',
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('test123', $result['paymentId']);
        $this->assertEquals('SUCCESS', $result['status']);
    }

    public function testCreateBulkPayment()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'batchId' => 'batch123',
            'success' => true,
            'bulkResponseStatus' => 'SUCCESS',
        ])));

        $result = $this->pesawise->createBulkPayment([
            'balanceId' => 1001002,
            'currency' => 'KES',
            'bulkPayments' => [
                [
                    'amount' => 1000,
                    'transferType' => 'BANK',
                    'reference' => 'REF123',
                ]
            ]
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('batch123', $result['batchId']);
        $this->assertTrue($result['success']);
    }

    public function testGetPaymentStatus()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'paymentStatus' => 'SUCCESS',
            'paymentId' => 'payment123',
            'amount' => 1000,
        ])));

        $result = $this->pesawise->getPaymentStatus('payment123');

        $this->assertIsArray($result);
        $this->assertEquals('SUCCESS', $result['paymentStatus']);
        $this->assertEquals('payment123', $result['paymentId']);
    }

    public function testErrorHandling()
    {
        $this->mockHandler->append(new Response(400, [], json_encode([
            'detail' => 'Invalid API key',
            'status' => 'API_KEYS_INVALID',
        ])));

        $this->expectException(PesawiseException::class);
        $this->expectExceptionMessage('Invalid API key');

        $this->pesawise->getAllSupportedBanks();
    }

    // Add more test methods for other Pesawise functions
}