<?php

require (dirname(__FILE__) . '/../src/Gateway.php');
require (dirname(__FILE__) . '/../src/Transaction.php');
require (dirname(__FILE__) . '/../src/CreditCard.php');
require (dirname(__FILE__) . '/../src/Charge.php');

date_default_timezone_set('UTC');

$gateway = new bilberrry\ChasePaymentech\Gateway();
$gateway->setTestMode(true);
$gateway->setCredentials('BLUEPRINTTEST1', '3BfJ1CvFbL6Y87', '000001', '318602', '001');

$creditCard = new bilberrry\ChasePaymentech\CreditCard;
$creditCard->setName('Test Test');
$creditCard->setCardNumber('4242 4242 4242 4242');
$creditCard->setCardExpiry(7, 2020);
$creditCard->setCardCVV(123);

if (false === $creditCard->validate()) {
    die('Credit Card Not Valid');
}


$transaction = new bilberrry\ChasePaymentech\Transaction;
$transaction->setTransactionId(time());
$transaction->setTransactionDate(new \DateTime);
$transaction->setCurrency('USD');
$transaction->setAmount('10.00');
$transaction->setComment('Test Transaction');

var_dump($gateway->updateEndpoints()->createCharge($creditCard, $transaction));