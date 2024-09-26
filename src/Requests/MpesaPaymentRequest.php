<?php

namespace Santosdave\PesawiseWrapper\Requests;



class MpesaPaymentRequest extends BaseRequest
{
    /** @var float Amount being sent */
    public float $amount;

    /** @var string Phone number of receiver */
    public string $phoneNumber;

    /** @var int Wallet Id of the account to send from */
    public int $balanceId;

    /** @var int|null Wallet Id to charge */
    public ?int $balanceIdCharge;

    /** @var string Reference */
    public string $reference;

    /** @var string Recipient Name */
    public string $recipient;

    /** @var string|null Recipient ID number */
    public ?string $recipientIdNumber;

    /** @var int|null Tag ID */
    public ?int $tagId;

    public function __construct(
        float $amount,
        string $phoneNumber,
        int $balanceId,
        string $reference,
        string $recipient,
        ?int $balanceIdCharge = null,
        ?string $recipientIdNumber = null,
        ?int $tagId = null
    ) {
        $this->amount = $amount;
        $this->phoneNumber = $phoneNumber;
        $this->balanceId = $balanceId;
        $this->reference = $reference;
        $this->recipient = $recipient;
        $this->balanceIdCharge = $balanceIdCharge;
        $this->recipientIdNumber = $recipientIdNumber;
        $this->tagId = $tagId;
    }

    public function validate(): void
    {
        $this->validateRequired(
            ['amount', 'phoneNumber', 'balanceId', 'reference', 'recipient'],
            [$this->amount, $this->phoneNumber, $this->balanceId, $this->reference, $this->recipient]
        );
        $this->validateNumeric(['amount', 'balanceId'], [$this->amount, $this->balanceId]);
        $this->validatePhone(['phoneNumber'], [$this->phoneNumber]);
    }

    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'phoneNumber' => $this->phoneNumber,
            'balanceId' => $this->balanceId,
            'balanceIdCharge' => $this->balanceIdCharge,
            'reference' => $this->reference,
            'recipient' => $this->recipient,
            'recipientIdNumber' => $this->recipientIdNumber,
            'tagId' => $this->tagId,
        ]);
    }
}