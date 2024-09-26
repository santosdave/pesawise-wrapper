<?php

namespace Santosdave\PesawiseWrapper\Requests;


use Santosdave\PesawiseWrapper\DataTypes\Currency;
use Santosdave\PesawiseWrapper\DataTypes\CustomerData;


class CreatePaymentOrderRequest extends BaseRequest
{
    /** @var float Amount of the order */
    public float $amount;

    /** @var string Customer name */
    public string $customerName;

    /** @var Currency Currency of the order */
    public Currency $currency;

    /** @var string Identifier of the order in the customer system */
    public string $externalId;

    /** @var string Description of the order */
    public string $description;

    /** @var int Balance ID to settle funds to */
    public int $balanceId;

    /** @var string Callback URL for payment processing */
    public string $callbackUrl;

    /** @var CustomerData Customer details */
    public CustomerData $customerData;

    /** @var string|null Cancellation URL */
    public ?string $cancellationUrl;

    /** @var string|null Notification identifier */
    public ?string $notificationId;

    /** @var int|null Time validity in minutes */
    public ?int $timeValidityMinutes;

    public function __construct(
        float $amount,
        string $customerName,
        Currency $currency,
        string $externalId,
        string $description,
        int $balanceId,
        string $callbackUrl,
        CustomerData $customerData,
        ?string $cancellationUrl = null,
        ?string $notificationId = null,
        ?int $timeValidityMinutes = null
    ) {
        $this->amount = $amount;
        $this->customerName = $customerName;
        $this->currency = $currency;
        $this->externalId = $externalId;
        $this->description = $description;
        $this->balanceId = $balanceId;
        $this->callbackUrl = $callbackUrl;
        $this->customerData = $customerData;
        $this->cancellationUrl = $cancellationUrl;
        $this->notificationId = $notificationId;
        $this->timeValidityMinutes = $timeValidityMinutes;
    }

    public function validate(): void
    {
        $this->validateRequired(
            ['amount', 'customerName', 'currency', 'externalId', 'description', 'balanceId', 'callbackUrl', 'customerData'],
            [$this->amount, $this->customerName, $this->currency, $this->externalId, $this->description, $this->balanceId, $this->callbackUrl, $this->customerData]
        );
        $this->validateNumeric(['amount', 'balanceId'], [$this->amount, $this->balanceId]);
        $this->validateUrl(['callbackUrl'], [$this->callbackUrl]);
        if ($this->cancellationUrl) {
            $this->validateUrl(['cancellationUrl'], [$this->cancellationUrl]);
        }
    }

    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'customerName' => $this->customerName,
            'currency' => $this->currency->getCode(),
            'externalId' => $this->externalId,
            'description' => $this->description,
            'balanceId' => $this->balanceId,
            'callbackUrl' => $this->callbackUrl,
            'customerData' => $this->customerData->toArray(),
            'cancellationUrl' => $this->cancellationUrl,
            'notificationId' => $this->notificationId,
            'timeValidityMinutes' => $this->timeValidityMinutes,
        ]);
    }
}