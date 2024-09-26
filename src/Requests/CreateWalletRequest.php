<?php

namespace Santosdave\PesawiseWrapper\Requests;

use Santosdave\PesawiseWrapper\DataTypes\Currency;


class CreateWalletRequest extends BaseRequest
{
    /** @var Currency Currency for the balance */
    public Currency $currency;

    /** @var string Name for the balance */
    public string $name;

    /** @var string Type of the account */
    public string $accountType;

    public function __construct(Currency $currency, string $name, string $accountType)
    {
        $this->currency = $currency;
        $this->name = $name;
        $this->accountType = $accountType;
    }

    public function validate(): void
    {
        $this->validateRequired(['currency', 'name', 'accountType'], [$this->currency, $this->name, $this->accountType]);
        $this->validateString(['name', 'accountType'], [$this->name, $this->accountType]);
        $this->validateEnumValue('accountType', $this->accountType, ['CLIENT_ACCOUNT', 'CLIENT_VIRTUAL_ACCOUNT', 'CLIENT_SHARED_ACCOUNT', 'CLIENT_HYBRID_ACCOUNT']);
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency->getCode(),
            'name' => $this->name,
            'accountType' => $this->accountType,
        ];
    }
}