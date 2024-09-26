<?php

namespace Santosdave\PesawiseWrapper\Requests;

use Santosdave\PesawiseWrapper\DataTypes\BulkPayment;
use Santosdave\PesawiseWrapper\DataTypes\Currency;


class BulkPaymentRequest extends BaseRequest
{
    /** @var int Wallet Id of the account to send from */
    public int $balanceId;

    /** @var int|null Wallet Id to charge, if left empty the balance that is being debited from will be charged */
    public ?int $balanceIdCharge;

    /** @var Currency Currency */
    public Currency $currency;

    /** @var array Array of BulkPayment objects */
    public array $bulkPayments;

    /** @var bool */
    public bool $virtual;

    public function __construct(int $balanceId, Currency $currency, array $bulkPayments, ?int $balanceIdCharge = null, bool $virtual = false)
    {
        $this->balanceId = $balanceId;
        $this->currency = $currency;
        $this->bulkPayments = $bulkPayments;
        $this->balanceIdCharge = $balanceIdCharge;
        $this->virtual = $virtual;
    }

    public function validate(): void
    {
        $this->validateRequired(['balanceId', 'currency', 'bulkPayments'], [$this->balanceId, $this->currency, $this->bulkPayments]);
        foreach ($this->bulkPayments as $payment) {
            if (!$payment instanceof BulkPayment) {
                throw new \InvalidArgumentException("All elements in bulkPayments must be instances of BulkPayment");
            }
        }
    }

    public function toArray(): array
    {
        return [
            'balanceId' => $this->balanceId,
            'balanceIdCharge' => $this->balanceIdCharge,
            'currency' => $this->currency->getCode(),
            'bulkPayments' => array_map(fn($payment) => $payment->toArray(), $this->bulkPayments),
            'virtual' => $this->virtual,
        ];
    }
}