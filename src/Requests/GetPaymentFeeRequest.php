<?php

namespace Santosdave\PesawiseWrapper\Requests;

use Santosdave\PesawiseWrapper\DataTypes\Currency;


class GetPaymentFeeRequest extends BaseRequest
{
    /** @var string Payment type */
    public string $paymentType;

    /** @var float Amount being sent */
    public float $amount;

    /** @var Currency Currency */
    public Currency $currency;

    public function __construct(string $paymentType, float $amount, Currency $currency)
    {
        $this->paymentType = $paymentType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function validate(): void
    {
        $this->validateRequired(['paymentType', 'amount', 'currency'], [$this->paymentType, $this->amount, $this->currency]);
        $this->validateNumeric(['amount'], [$this->amount]);
        $this->validateEnumValue('paymentType', $this->paymentType, ['MPESA', 'PESALINK', 'RTGS', 'SWIFT']);
    }

    public function toArray(): array
    {
        return [
            'paymentType' => $this->paymentType,
            'amount' => $this->amount,
            'currency' => $this->currency->getCode(),
        ];
    }
}