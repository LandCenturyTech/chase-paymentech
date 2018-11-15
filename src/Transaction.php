<?php

namespace bilberrry\ChasePaymentech;


/**
 * Class Transaction
 * @package bilberrry\ChasePaymentech
 */
class Transaction {

    /**
     * @var string
     */
    protected $transaction_id;

    /**
     * @var float
     */
    protected $amount = 0.00;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var \DateTime
     */
    protected $transaction_date;

    /**
     * @var string
     */
    protected $currency_code = 'USD';

    /**
     * @var int
     */
    protected $status = 0;

    /**
     * @var string
     */
    protected $transaction_type = 'A';

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * @param string $transaction_id
     *
     * @return $this
     */
    public function setTransactionId($transaction_id = null)
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount($amount = null)
    {
        $this->amount = (float)$amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return $this
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transaction_date;
    }

    /**
     * @param \DateTime $transaction_date
     *
     * @return $this
     */
    public function setTransactionDate(\DateTime $transaction_date = null)
    {
        $this->transaction_date = $transaction_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @param string $currency_code
     *
     * @return $this
     */
    public function setCurrency($currency_code = null)
    {
        $this->currency_code = $currency_code;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status = null)
    {
        $this->status = (int)$status;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    /**
     * @param string $transaction_type
     *
     * @return $this
     */
    public function setTransactionType($transaction_type = null)
    {
        $this->transaction_type = (int)$transaction_type;

        return $this;
    }

}