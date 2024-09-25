<?php

namespace Santosdave\PesawiseWrapper\DataTypes;

class CustomerData
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string|null
     */
    public $city;

    /**
     * @var string|null
     */
    public $state;

    /**
     * @var string|null
     */
    public $address;

    /**
     * @var string|null
     */
    public $countryCode;

    /**
     * @param string $email
     * @param string $phoneNumber
     * @param string|null $city
     * @param string|null $state
     * @param string|null $address
     * @param string|null $countryCode
     */
    public function __construct(
        string $email,
        string $phoneNumber,
        ?string $city = null,
        ?string $state = null,
        ?string $address = null,
        ?string $countryCode = null
    ) {
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->city = $city;
        $this->state = $state;
        $this->address = $address;
        $this->countryCode = $countryCode;
    }

    public function toArray(): array
    {
        return array_filter([
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'city' => $this->city,
            'state' => $this->state,
            'address' => $this->address,
            'countryCode' => $this->countryCode,
        ]);
    }
}
