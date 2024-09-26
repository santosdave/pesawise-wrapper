<?php

namespace Santosdave\PesawiseWrapper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Santosdave\PesawiseWrapper\DataTypes\PaymentStatus;
use Santosdave\PesawiseWrapper\Exceptions\PesawiseException;
use Santosdave\PesawiseWrapper\Requests\BankRecipientValidationRequest;
use Santosdave\PesawiseWrapper\Requests\BulkPaymentRequest;
use Santosdave\PesawiseWrapper\Requests\CompletePaymentRequest;
use Santosdave\PesawiseWrapper\Requests\CreatePaymentOrderRequest;
use Santosdave\PesawiseWrapper\Requests\CreateWalletRequest;
use Santosdave\PesawiseWrapper\Requests\DirectPaymentRequest;
use Santosdave\PesawiseWrapper\Requests\GetPaymentFeeRequest;
use Santosdave\PesawiseWrapper\Requests\MpesaPaymentRequest;
use Santosdave\PesawiseWrapper\Requests\PesalinkPaymentRequest;
use Santosdave\PesawiseWrapper\Requests\ResendOtpRequest;
use Santosdave\PesawiseWrapper\Requests\StkPushRequest;
use Santosdave\PesawiseWrapper\Traits\ValidationTrait;

class Pesawise
{
    use ValidationTrait;

    public const SANDBOX_HOST = "https://api.pesawise.xyz";
    public const PROD_HOST = "https://api.pesawise.com";

    private string $baseUrl;
    private array $config;
    private Client $client;

    public function __construct(array $config = [])
    {
        $this->setConfig($config);
        $this->initializeClient();
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

    private function initializeClient(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'api-key' => $this->config['api_key'],
                'api-secret' => $this->config['api_secret'],
            ],
            'http_errors' => true,
            'debug' => $this->config['debug'],
        ]);
    }


    public function getAllSupportedBanks(): array
    {
        return $this->makeRequest('POST', '/api/payments/get-bank-info');
    }

    /**
     * Validate a bank recipient
     *
     * @param BankRecipientValidationRequest $request
     * @return array
     * @throws PesawiseException
     */

    public function validateBankRecipient(BankRecipientValidationRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/bank-recipient', $request->toArray());
    }

    /**
     * Create a bulk payment
     *
     * @param BulkPaymentRequest $request
     * @return array
     * @throws PesawiseException
     */
    public function createBulkPayment(BulkPaymentRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/bulk-payment', $request->toArray());
    }

    /**
     * Create a payment order
     *
     * @param CreatePaymentOrderRequest $request
     * @return array
     * @throws PesawiseException
     */
    public function createPaymentOrder(CreatePaymentOrderRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/e-com/create-order', $request->toArray());
    }


    /**
     * Complete a payment
     *
     * @param CompletePaymentRequest $request
     * @return array
     * @throws PesawiseException
     */
    public function completePayment(CompletePaymentRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/complete-payment', $request->toArray());
    }

    /**
     * Create a direct payment
     *
     * @param DirectPaymentRequest $request
     * @return array
     * @throws PesawiseException
     */

    public function createDirectPayment(DirectPaymentRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/create-direct-payment', $request->toArray());
    }

    /**
     * Create a new wallet
     *
     * @param CreateWalletRequest $request
     * @return array
     * @throws PesawiseException
     */
    public function createWallet(CreateWalletRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/create-balance', $request->toArray());
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

    /**
     * Get the fee for a payment
     *
     * @param GetPaymentFeeRequest $request
     * @return array
     * @throws PesawiseException
     */

    public function getPaymentFee(GetPaymentFeeRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/get-fee', $request->toArray());
    }

    public function getPaymentStatus(string $paymentId = null, string $uniqueReference = null): PaymentStatus
    {
        $params = [];
        if ($paymentId) {
            $params['paymentId'] = $paymentId;
        } elseif ($uniqueReference) {
            $params['uniqueReference'] = $uniqueReference;
        } else {
            throw new InvalidArgumentException('Either paymentId or uniqueReference must be provided');
        }

        $response = $this->makeRequest('GET', "/api/payments/payment-status", $params);
        return new PaymentStatus($response['paymentStatus']);
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

    /**
     * Create an M-PESA payment
     *
     * @param MpesaPaymentRequest $request
     * @param bool $isVirtual
     * @return array
     * @throws PesawiseException
     */
    public function createMpesaPayment(MpesaPaymentRequest $request, bool $isVirtual = false): array
    {
        $request->validate();
        $data = array_merge($request->toArray(), ['is-virtual' => $isVirtual]);
        return $this->makeRequest('POST', '/api/payments/mpesa-payment', $data);
    }

    /**
     * Create a Pesalink payment
     *
     * @param PesalinkPaymentRequest $request
     * @param bool $isVirtual
     * @return array
     * @throws PesawiseException
     */
    public function createPesalinkPayment(PesalinkPaymentRequest $request, bool $isVirtual = false): array
    {
        $request->validate();
        $data = array_merge($request->toArray(), ['is-virtual' => $isVirtual]);
        return $this->makeRequest('POST', '/api/payments/pesalink-payment', $data);
    }

    /**
     * Resend OTP for a payment
     *
     * @param ResendOtpRequest $request
     * @return array
     * @throws PesawiseException
     */
    public function resendOtp(ResendOtpRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/resend-otp', $request->toArray());
    }

    /**
     * Initiate an STK push
     *
     * @param StkPushRequest $request
     * @return array
     * @throws PesawiseException
     */
    public function stkPush(StkPushRequest $request): array
    {
        $request->validate();
        return $this->makeRequest('POST', '/api/payments/stk-push', $request->toArray());
    }

    protected function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = $method === 'GET' ? ['query' => $data] : ['json' => $data];
            $fullUrl = $this->baseUrl . $endpoint;
            $response = $this->client->request($method, $fullUrl, $options);

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