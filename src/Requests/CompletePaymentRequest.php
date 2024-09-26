<?php

namespace Santosdave\PesawiseWrapper\Requests;

class CompletePaymentRequest extends BaseRequest
{
    /** @var string Unique identifier for the payment */
    public string $paymentId;

    /** @var string OTP sent via email, webhook or SMS */
    public string $otp;

    /**
     * @param string $paymentId
     * @param string $otp
     */
    public function __construct(string $paymentId, string $otp)
    {
        $this->paymentId = $paymentId;
        $this->otp = $otp;
    }

    public function validate(): void
    {
        $this->validateRequired(['paymentId', 'otp'], [$this->paymentId, $this->otp]);
        $this->validateString(['paymentId', 'otp'], [$this->paymentId, $this->otp]);
    }

    public function toArray(): array
    {
        return [
            'paymentId' => $this->paymentId,
            'otp' => $this->otp,
        ];
    }
}