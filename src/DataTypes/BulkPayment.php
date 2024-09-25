<?php

namespace Santosdave\PesawiseWrapper\DataTypes;

class BulkPayment
{
    /**
     * @var float
     */
    public $amount;

    /**
     * @var TransferType
     */
    public $transferType;

    /**
     * @var string|null
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $recipient;

    /**
     * @var int|null
     */
    public $bankId;

    /**
     * @var string|null
     */
    public $accountNumber;

    /**
     * @param float $amount
     * @param TransferType $transferType
     * @param string $reference
     * @param string $recipient
     * @param string|null $phoneNumber
     * @param int|null $bankId
     * @param string|null $accountNumber
     */
    public function __construct(
        float $amount,
        TransferType $transferType,
        string $reference,
        string $recipient,
        ?string $phoneNumber = null,
        ?int $bankId = null,
        ?string $accountNumber = null
    ) {
        $this->amount = $amount;
        $this->transferType = $transferType;
        $this->reference = $reference;
        $this->recipient = $recipient;
        $this->phoneNumber = $phoneNumber;
        $this->bankId = $bankId;
        $this->accountNumber = $accountNumber;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'transferType' => $this->transferType->getType(),
            'reference' => $this->reference,
            'recipient' => $this->recipient,
            'phoneNumber' => $this->phoneNumber,
            'bankId' => $this->bankId,
            'accountNumber' => $this->accountNumber,
        ];
    }
}
