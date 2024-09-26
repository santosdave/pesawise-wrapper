<?php

namespace Santosdave\PesawiseWrapper\Requests;


class ResendOtpRequest extends BaseRequest
{
    /** @var string Unique identifier of the payment */
    public string $paymentId;

    public function __construct(string $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function validate(): void
    {
        $this->validateRequired(['paymentId'], [$this->paymentId]);
        $this->validateString(['paymentId'], [$this->paymentId]);
    }

    public function toArray(): array
    {
        return [
            'paymentId' => $this->paymentId,
        ];
    }
}