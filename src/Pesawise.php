<?php

namespace Santosdave\PesawiseWrapper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Santosdave\PesawiseWrapper\Exceptions\PesawiseException;
use Santosdave\PesawiseWrapper\Traits\ValidationTrait;

class Pesawise
{
    use ValidationTrait;

    public const SANDBOX_HOST = "https://api.pesawise.xyz";
    public const PROD_HOST = "https://api.pesawise.com";

    private string $baseUrl;
    private array $config;
    private Client $client;

    public function __construct(array $config = [], Client $client = null)
    {
        $this->setConfig($config);
        $this->initializeClient($client);
    }

    private function setConfig(array $config): void
    {
        $this->config = [
            'api_key' => $config['api_key'] ?? config('pesawise.api_key') ?? env('PESAWISE_API_KEY'),
            'api_secret' => $config['api_secret'] ?? config('pesawise.api_secret') ?? env('PESAWISE_API_SECRET'),
            'environment' => $config['environment'] ?? config('pesawise.environment') ?? env('PESAWISE_ENVIRONMENT', 'sandbox'),
            'debug' => $config['debug'] ?? config('pesawise.debug') ?? env('PESAWISE_DEBUG', false),
            'default_currency' => $config['default_currency'] ?? config('pesawise.default_currency') ?? env('PESAWISE_DEFAULT_CURRENCY', 'KES'),
            'default_balance_id' => $config['default_balance_id'] ?? config('pesawise.default_balance_id') ?? env('PESAWISE_DEFAULT_BALANCE_ID'),
        ];

        $this->validateConfig();
        $this->baseUrl = $this->config['environment'] === 'sandbox' ? self::SANDBOX_HOST : self::PROD_HOST;
    }

    private function validateConfig(): void
    {
        if (!$this->config['api_key']) {
            throw new InvalidArgumentException('API key is required');
        }

        if (!$this->config['api_secret']) {
            throw new InvalidArgumentException('API secret is required');
        }
    }

    private function initializeClient(Client $client = null): void
    {
        $this->client = $client ?? new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'api-key' => $this->config['api_key'],
                'api-secret' => $this->config['api_secret'],
            ],
            'http_errors' => false,
            'debug' => $this->config['debug'],
        ]);
    }


    public function getAllSupportedBanks(): array
    {
        return $this->makeRequest('GET', '/api/payments/get-bank-info');
    }

    public function validateBankRecipient(array $data): array
    {
        return $this->makeRequest('POST', '/api/payments/bank-recipient', $data);
    }

    public function createBulkPayment(array $data): array
    {
        $this->validateRequired($data, ['balanceId', 'currency', 'bulkPayments']);
        $this->validateNumeric($data, ['balanceId']);
        $this->validateString($data, ['currency']);

        foreach ($data['bulkPayments'] as $payment) {
            $this->validateRequired($payment, ['amount', 'transferType', 'reference', 'recipient']);
            $this->validateNumeric($payment, ['amount']);
            $this->validateString($payment, ['transferType', 'reference', 'recipient']);
            $this->validateEnum($payment, 'transferType', ['BANK', 'B2B', 'B2C', 'BUSINESS_PAY_BILL', 'BUSINESS_BUY_GOODS', 'COUNTER_PARTY_TRANSFER']);
        }

        return $this->makeRequest('POST', '/api/payments/bulk-payment', $data);
    }

    public function createPaymentOrder(array $data): array
    {
        return $this->makeRequest('POST', '/api/e-com/create-order', $data);
    }

    public function completePayment(array $data): array
    {
        return $this->makeRequest('POST', '/api/payments/complete-payment', $data);
    }

    public function createDirectPayment(array $data): array
    {
        $this->validateRequired($data, ['balanceId', 'currency', 'amount', 'transferType', 'reference', 'recipient']);
        $this->validateNumeric($data, ['balanceId', 'amount']);
        $this->validateString($data, ['currency', 'transferType', 'reference', 'recipient']);
        $this->validateEnum($data, 'transferType', ['BANK', 'B2B', 'B2C', 'BUSINESS_PAY_BILL', 'BUSINESS_BUY_GOODS', 'COUNTER_PARTY_TRANSFER']);

        return $this->makeRequest('POST', '/api/payments/create-direct-payment', $data);
    }

    public function createWallet(array $data): array
    {
        return $this->makeRequest('POST', '/api/payments/create-balance', $data);
    }

    public function getWallet(int $walletId, bool $isVirtual = false): array
    {
        return $this->makeRequest('GET', "/api/payments/wallet", [
            'walletId' => $walletId,
            'isVirtual' => $isVirtual
        ]);
    }

    public function getAllWallets(bool $isVirtual = false): array
    {
        return $this->makeRequest('GET', "/api/payments/wallets", ['isVirtual' => $isVirtual]);
    }

    public function getBulkPaymentStatus(string $batchId, int $pageSize = 10, int $pageNumber = 1): array
    {
        return $this->makeRequest('GET', "/api/payments/bulk-payment-status", [
            'batch-id' => $batchId,
            'page-size' => $pageSize,
            'page-number' => $pageNumber
        ]);
    }

    public function getPaymentFee(array $data): array
    {
        return $this->makeRequest('POST', '/api/payments/get-fee', $data);
    }

    public function getPaymentStatus(string $paymentId = null, string $uniqueReference = null): array
    {
        $params = [];
        if ($paymentId) {
            $params['paymentId'] = $paymentId;
        } elseif ($uniqueReference) {
            $params['uniqueReference'] = $uniqueReference;
        } else {
            throw new InvalidArgumentException('Either paymentId or uniqueReference must be provided');
        }

        return $this->makeRequest('GET', "/api/payments/payment-status", $params);
    }

    public function getTransactions(int $walletId, bool $isVirtual, int $pageSize = 10, int $pageNumber = 1): array
    {
        return $this->makeRequest('GET', "/api/payments/transactions", [
            'wallet-id' => $walletId,
            'isVirtual' => $isVirtual,
            'page-size' => $pageSize,
            'page-number' => $pageNumber
        ]);
    }

    public function createMpesaPayment(array $data, bool $isVirtual = false): array
    {
        $this->validateRequired($data, ['amount', 'phoneNumber', 'balanceId', 'reference', 'recipient']);
        $this->validateNumeric($data, ['amount', 'balanceId']);
        $this->validateString($data, ['phoneNumber', 'reference', 'recipient']);

        return $this->makeRequest('POST', "/api/payments/mpesa-payment", array_merge($data, ['is-virtual' => $isVirtual]));
    }

    public function createPesalinkPayment(array $data, bool $isVirtual = false): array
    {
        return $this->makeRequest('POST', "/api/payments/pesalink-payment", array_merge($data, ['is-virtual' => $isVirtual]));
    }

    public function resendOtp(array $data): array
    {
        return $this->makeRequest('POST', '/api/payments/resend-otp', $data);
    }

    public function stkPush(array $data): array
    {
        return $this->makeRequest('POST', '/api/payments/stk-push', $data);
    }

    protected function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = $method === 'GET' ? ['query' => $data] : ['json' => $data];
            $response = $this->client->request($method, $endpoint, $options);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);

            if ($statusCode >= 200 && $statusCode < 300) {
                return $body;
            } else {
                throw new PesawiseException(
                    $body['detail'] ?? 'Unknown error occurred',
                    $statusCode,
                    null,
                    $body
                );
            }
        } catch (GuzzleException $e) {
            Log::error('Pesawise API Error: ' . $e->getMessage());
            throw new PesawiseException('Error communicating with Pesawise: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}