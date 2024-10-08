# Pesawise Laravel Wrapper

A Laravel package for integrating the Pesawise payment gateway into your application.

## Installation

You can install the package via composer:

```bash
composer require santosdave/pesawise-wrapper
```

## Configuration

After installation, publish the config file:

```bash
php artisan vendor:publish --provider="Santosdave\PesawiseWrapper\PesawiseProvider"
```

This will create a `config/pesawise.php` file. Update your `.env` file with your Pesawise credentials:

```
PESAWISE_API_KEY=your_api_key
PESAWISE_API_SECRET=your_api_secret
PESAWISE_ENVIRONMENT=sandbox
PESAWISE_DEBUG=false
PESAWISE_DEFAULT_CURRENCY=KES
PESAWISE_DEFAULT_BALANCE_ID=your_default_balance_id
```

## Usage

You can use the Pesawise facade or inject the Pesawise class into your controllers/services.

```php
use Santosdave\PesawiseWrapper\Facades\Pesawise;

// Or inject in your constructor
use Santosdave\PesawiseWrapper\Pesawise;

public function __construct(Pesawise $pesawise)
{
    $this->pesawise = $pesawise;
}
```

### Using Dependency Injection

```php
use Santosdave\PesawiseWrapper\Pesawise;

class PaymentService
{
    protected $pesawise;

    public function __construct(Pesawise $pesawise)
    {
        $this->pesawise = $pesawise;
    }

    public function processPayment(array $paymentData)
    {
        return $this->pesawise->createDirectPayment($paymentData);
    }
}
```


### Available Methods

#### Get All Supported Banks
```php
$banks = Pesawise::getAllSupportedBanks();
```

#### Validate Bank Recipient
```php
use Santosdave\PesawiseWrapper\Requests\BankRecipientValidationRequest;

$request = new BankRecipientValidationRequest(
    bankId: 1,
    bankAccountNumber: '1234567890'
);

$recipient = Pesawise::validateBankRecipient($request);
```

#### Create Bulk Payment
```php
use Santosdave\PesawiseWrapper\Requests\BulkPaymentRequest;
use Santosdave\PesawiseWrapper\DataTypes\Currency;
use Santosdave\PesawiseWrapper\DataTypes\BulkPayment;
use Santosdave\PesawiseWrapper\DataTypes\TransferType;

$bulkPayments = [
    new BulkPayment(
        amount: 1000,
        transferType: new TransferType(TransferType::BANK),
        reference: 'REF123',
        recipient: 'John Doe',
        bankId: 1,
        accountNumber: '1234567890'
    )
];

$request = new BulkPaymentRequest(
    balanceId: 1001002,
    currency: new Currency(Currency::KES),
    bulkPayments: $bulkPayments
);

$bulkPayment = Pesawise::createBulkPayment($request);
```

#### Create Payment Order
```php
$order = Pesawise::createPaymentOrder([
    'amount' => 1000,
    'customerName' => 'John Doe',
    'currency' => 'KES',
    'externalId' => 'ORDER123',
    'description' => 'Product purchase',
    'balanceId' => 1001002,
    'callbackUrl' => 'https://example.com/callback'
]);
```

#### Complete Payment
```php
use Santosdave\PesawiseWrapper\Requests\CompletePaymentRequest;

$request = new CompletePaymentRequest(
    paymentId: 'payment_id_here',
    otp: '123456'
);

$payment = Pesawise::completePayment($request);
```

#### Create Direct Payment
```php
$payment = Pesawise::createDirectPayment([
    'balanceId' => 1001002,
    'currency' => 'KES',
    'amount' => 1000,
    'transferType' => 'BANK',
    'reference' => 'REF123',
    'recipient' => 'John Doe',
    'bankId' => 1,
    'accountNumber' => '1234567890'
]);
```

For more methods and detailed usage, refer to the [Pesawise API documentation](https://docs.pesawise.com/docs/home).

## Error Handling

The package throws `PesawiseException` for API errors. You can catch and handle these exceptions in your application:

```php
use Santosdave\PesawiseWrapper\Exceptions\PesawiseException;

try {
    $payment = Pesawise::createDirectPayment([...]);
} catch (PesawiseException $e) {
    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
    $errorResponse = $e->getErrorResponse();
    // Handle the error
}
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email the author instead of using the issue tracker.

## Credits

- [santosdave](https://github.com/santosdave)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

