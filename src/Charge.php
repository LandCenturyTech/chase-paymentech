<?php

namespace bilberrry\ChasePaymentech;

class Charge {

    public function create(CreditCard $card, Transaction $transaction)
    {
        return ['NewOrder', [
            'MessageType' => $transaction->getTransactionType(),
            'AccountNum' => $card->getCardNumber(),
            'Exp' => $card->getCardExpiry()->format('my'),
            'CurrencyCode' => 840,
            'CurrencyExponent' => $this->getCurrencyExponent(),
            'CardSecValInd' => $card->getCardCVV() ? 1 : 9,
            'CardSecVal' => $card->getCardCVV(),
            'AVSzip' => $card->getZip(),
            'AVSaddress1' => $card->getAddress1(),
            'AVSaddress2' => $card->getAddress2(),
            'AVScity' => $card->getCity(),
            'AVSstate' => $card->getState(),
            'AVSphoneNum' => $card->getPhone(),
            'AVSname' => $card->getName(),
            'AVScountryCode' => $card->getCountryCode(),
            'OrderID' => $transaction->getTransactionId(),
            'Amount' => $transaction->getAmount() * $this->getCurrencyExponent(),
            'CustomerIpAddress' => isset($_SERVER) && array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '',
            'CustomerBrowserName' => isset($_SERVER) && array_key_exists('USER_AGENT', $_SERVER) ? $_SERVER['USER_AGENT'] : ''
        ]];
    }

    public function getCurrencyExponent()
    {
        return 100;
    }

}