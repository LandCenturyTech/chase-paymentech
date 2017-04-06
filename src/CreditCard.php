<?php

namespace bilberrry\ChasePaymentech;

/**
 * Class CreditCard
 * @package bilberrry\ChasePaymentech\CreditCard
 */
class CreditCard
{
    /** @var string */
    protected $card_number;

    /** @var \DateTime */
    protected $card_expiry;

    /** @var string */
    protected $card_cvv;

    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var string */
    protected $address1;

    /** @var string */
    protected $address2;

    /** @var string */
    protected $city;

    /** @var string */
    protected $state;

    /** @var string */
    protected $zip;

    /** @var string */
    protected $country;

    /** @var string */
    protected $country_code;

    /** @var string */
    protected $phone;

    /** @var string */
    protected $email;


    /**
     * @param string $card_number
     *
     * @return $this
     */
    public function setCardNumber($card_number)
    {
        $this->card_number = preg_replace('/[^0-9]/', '', $card_number);

        return $this;
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->card_number;
    }

    /**
     * @param int $month
     * @param int $year
     *
     * @return $this
     */
    public function setCardExpiry($month, $year)
    {
        $this->card_expiry = new \DateTime;
        $this->card_expiry->setTimestamp(mktime($hour = 0, $minute = 0, $second = 0, $month + 1, $day = 0, $year));

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCardExpiry()
    {
        return $this->card_expiry;
    }

    /**
     * @param string $cvv
     *
     * @return $this
     */
    public function setCardCVV($cvv)
    {
        $this->card_cvv = $cvv;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardCVV()
    {
        return $this->card_cvv;
    }

    /**
     * @return string
     */
    public function getMaskedNumber()
    {
        return sprintf('%s **** **** %s', substr($this->getCardNumber(), 0, 4), substr($this->getCardNumber(), -4));
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $address1
     *
     * @return $this
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * @return string string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address2
     *
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $zip
     *
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country_code
     *
     * @return $this
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $card_check = $this->cardCheck($this->getCardNumber());
        $expiry_check = $this->getCardExpiry()->getTimestamp() > time();

        return $card_check && $expiry_check;
    }

    /**
     * @param $number
     *
     * @return bool
     */
    private function cardCheck($number)
    {
        $checksum = 0;

        for ($i = (2-(strlen($number) % 2)); $i <= strlen($number); $i += 2) {

            $checksum += (int) ($number{$i - 1});
        }

        for ($i = (strlen($number) % 2) + 1; $i < strlen($number); $i += 2) {

            $digit = (int) ($number{$i - 1}) * 2;

            if ($digit < 10) {

                $checksum += $digit;
            } else {

                $checksum += ($digit - 9);
            }
        }
        if (($checksum % 10) == 0) {

            return true;
        } else {

            return false;
        }
    }
}