<?php

namespace Santosdave\PesawiseWrapper\Requests;

use Santosdave\PesawiseWrapper\DataTypes\Currency;



class PesalinkPaymentRequest extends BaseRequest
{
    /** @var int Wallet Id making the payment */
    public int $balanceId;

    /** @var int Pesawise Bank identifier */
    public int $bankId;

    /** @var float Amount being sent */
    public float $amount;

    /** @var Currency Currency */
    public Currency $currency;

    /** @var string Bank Account Number */
    public string $accountNumber;

    /** @var string Bank payment reference */
    public string $reference;

    /** @var string Recipient Name */
    public string $recipient;

    /** @var int|null Tag ID */
    public ?int $tagId;

    public function __construct(
        int $balanceId,
        int $bankId,
        float $amount,
        Currency $currency,
        string $accountNumber,
        string $reference,
        string $recipient,
        ?int $tagId = null
    ) {
        $this->balanceId = $balanceId;
        $this->bankId = $bankId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->accountNumber = $accountNumber;
        $this->reference = $reference;
        $this->recipient = $recipient;
        $this->tagId = $tagId;
    }

    public function validate(): void
    {
        $this->validateRequired(
            ['balanceId', 'bankId', 'amount', 'currency', 'accountNumber', 'reference', 'recipient'],
            [$this->balanceId, $this->bankId, $this->amount, $this->currency, $this->accountNumber, $this->reference, $this->recipient]
        );
        $this->validateNumeric(['balanceId', 'bankId', 'amount'], [$this->balanceId, $this->bankId, $this->amount]);
    }

    public function toArray(): array
    {
        return array_filter([
            'balanceId' => $this->balanceId,
            'bankId' => $this->bankId,
            'amount' => $this->amount,
            'currency' => $this->currency->getCode(),
            'accountNumber' => $this->accountNumber,
            'reference' => $this->reference,
            'recipient' => $this->recipient,
            'tagId' => $this->tagId,
        ]);
    }
}