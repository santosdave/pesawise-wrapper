<?php

namespace Santosdave\PesawiseWrapper\Requests;


class BankRecipientValidationRequest extends BaseRequest
{
    /** @var int Bank Identifier */
    public int $bankId;

    /** @var string Bank Account Number */
    public string $bankAccountNumber;

    public function __construct(int $bankId, string $bankAccountNumber)
    {
        $this->bankId = $bankId;
        $this->bankAccountNumber = $bankAccountNumber;
    }

    public function validate(): void
    {
        $this->validateRequired(['bankId', 'bankAccountNumber'], [$this->bankId, $this->bankAccountNumber]);
        $this->validateNumeric(['bankId'], [$this->bankId]);
        $this->validateString(['bankAccountNumber'], [$this->bankAccountNumber]);
    }

    public function toArray(): array
    {
        return [
            'bankId' => $this->bankId,
            'bankAccountNumber' => $this->bankAccountNumber,
        ];
    }
}