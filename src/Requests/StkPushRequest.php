<?php

namespace Santosdave\PesawiseWrapper\Requests;


class StkPushRequest extends BaseRequest
{
    /** @var int Balance Id being collected into */
    public int $balanceId;

    /** @var float Amount being collected */
    public float $amount;

    /** @var string Phone number collecting from */
    public string $phoneNumber;

    /** @var string Your reference for the transaction */
    public string $reference;

    public function __construct(int $balanceId, float $amount, string $phoneNumber, string $reference)
    {
        $this->balanceId = $balanceId;
        $this->amount = $amount;
        $this->phoneNumber = $phoneNumber;
        $this->reference = $reference;
    }

    public function validate(): void
    {
        $this->validateRequired(
            ['balanceId', 'amount', 'phoneNumber', 'reference'],
            [$this->balanceId, $this->amount, $this->phoneNumber, $this->reference]
        );
        $this->validateNumeric(['balanceId', 'amount'], [$this->balanceId, $this->amount]);
        $this->validatePhone(['phoneNumber'], [$this->phoneNumber]);
    }

    public function toArray(): array
    {
        return [
            'balanceId' => $this->balanceId,
            'amount' => $this->amount,
            'phoneNumber' => $this->phoneNumber,
            'reference' => $this->reference,
        ];
    }
}