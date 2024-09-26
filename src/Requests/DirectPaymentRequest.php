<?php

namespace Santosdave\PesawiseWrapper\Requests;

use Santosdave\PesawiseWrapper\DataTypes\Currency;
use Santosdave\PesawiseWrapper\DataTypes\TransferType;

class DirectPaymentRequest extends BaseRequest
{
    /** @var int Wallet Id of the account to send from */
    public int $balanceId;

    /** @var Currency Currency */
    public Currency $currency;

    /** @var float Amount being sent */
    public float $amount;

    /** @var TransferType Type of transfer */
    public TransferType $transferType;

    /** @var string Reference for the payment */
    public string $reference;

    /** @var string Recipient Name */
    public string $recipient;

    /** @var int|null Pesawise Bank identifier */
    public ?int $bankId;

    /** @var string|null Account Number */
    public ?string $accountNumber;

    public function __construct(
        int $balanceId,
        Currency $currency,
        float $amount,
        TransferType $transferType,
        string $reference,
        string $recipient,
        ?int $bankId = null,
        ?string $accountNumber = null
    ) {
        $this->balanceId = $balanceId;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->transferType = $transferType;
        $this->reference = $reference;
        $this->recipient = $recipient;
        $this->bankId = $bankId;
        $this->accountNumber = $accountNumber;
    }

    public function validate(): void
    {
        $this->validateRequired(
            ['balanceId', 'currency', 'amount', 'transferType', 'reference', 'recipient'],
            [$this->balanceId, $this->currency, $this->amount, $this->transferType, $this->reference, $this->recipient]
        );
        $this->validateNumeric(['amount'], [$this->amount]);
    }

    public function toArray(): array
    {
        return array_filter([
            'balanceId' => $this->balanceId,
            'currency' => $this->currency->getCode(),
            'amount' => $this->amount,
            'transferType' => $this->transferType->getType(),
            'reference' => $this->reference,
            'recipient' => $this->recipient,
            'bankId' => $this->bankId,
            'accountNumber' => $this->accountNumber,
        ]);
    }
}