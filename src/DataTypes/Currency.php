<?php

namespace Santosdave\PesawiseWrapper\DataTypes;


class Currency
{
    const KES = 'KES';
    const USD = 'USD';

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        if (!in_array($code, [self::KES, self::USD])) {
            throw new \InvalidArgumentException("Invalid currency code: $code");
        }
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
