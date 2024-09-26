<?php

namespace Santosdave\PesawiseWrapper\DataTypes;

class BulkPayment
{
    /** @var float */
    public float $amount;

    /** @var TransferType */
    public TransferType $transferType;

    /** @var string|null */
    public ?string $phoneNumber;

    /** @var string */
    public string $reference;

    /** @var string */
    public string $recipient;

    /** @var string|null */
    public ?string $recipientIdNumber;

    /** @var int|null */
    public ?int $bankId;

    /** @var string|null */
    public ?string $accountNumber;

    /** @var string|null */
    public ?string $paybillNumber;

    /** @var string|null */
    public ?string $tillNumber;

    /** @var int|null */
    public ?int $beneficiaryId;

    /** @var string|null */
    public ?string $uniqueReference;

    /** @var int|null */
    public ?int $walletIdTo;

    /**
     * @param float $amount
     * @param TransferType $transferType
     * @param string $reference
     * @param string $recipient
     * @param string|null $phoneNumber
     * @param string|null $recipientIdNumber
     * @param int|null $bankId
     * @param string|null $accountNumber
     * @param string|null $paybillNumber
     * @param string|null $tillNumber
     * @param int|null $beneficiaryId
     * @param string|null $uniqueReference
     * @param int|null $walletIdTo
     */
    public function __construct(
        float $amount,
        TransferType $transferType,
        string $reference,
        string $recipient,
        ?string $phoneNumber = null,
        ?string $recipientIdNumber = null,
        ?int $bankId = null,
        ?string $accountNumber = null,
        ?string $paybillNumber = null,
        ?string $tillNumber = null,
        ?int $beneficiaryId = null,
        ?string $uniqueReference = null,
        ?int $walletIdTo = null
    ) {
        $this->amount = $amount;
        $this->transferType = $transferType;
        $this->reference = $reference;
        $this->recipient = $recipient;
        $this->phoneNumber = $phoneNumber;
        $this->recipientIdNumber = $recipientIdNumber;
        $this->bankId = $bankId;
        $this->accountNumber = $accountNumber;
        $this->paybillNumber = $paybillNumber;
        $this->tillNumber = $tillNumber;
        $this->beneficiaryId = $beneficiaryId;
        $this->uniqueReference = $uniqueReference;
        $this->walletIdTo = $walletIdTo;
    }

    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'transferType' => $this->transferType->getType(),
            'phoneNumber' => $this->phoneNumber,
            'reference' => $this->reference,
            'recipient' => $this->recipient,
            'recipientIdNumber' => $this->recipientIdNumber,
            'bankId' => $this->bankId,
            'accountNumber' => $this->accountNumber,
            'paybillNumber' => $this->paybillNumber,
            'tillNumber' => $this->tillNumber,
            'beneficiaryId' => $this->beneficiaryId,
            'uniqueReference' => $this->uniqueReference,
            'walletIdTo' => $this->walletIdTo,
        ], function ($value) {
            return $value !== null;
        });
    }
}