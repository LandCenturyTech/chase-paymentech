<?php

namespace bilberrry\ChasePaymentech;

use bilberrry\ChasePaymentech\Exception\ApiConnectionException;
use bilberrry\ChasePaymentech\Exception\BaseException;

class Gateway {

    const VERSION = '0.0.1';

    const INDUSTRY_TYPE_MAIL_ORDER = 'MO';
    const INDUSTRY_TYPE_RECURRING_PAYMENT = 'RC';
    const INDUSTRY_TYPE_ECOMMERCE = 'EC';
    const INDUSTRY_TYPE_IVR = 'IV';
    const INDUSTRY_TYPE_INSTALLMENT = 'IN';

    protected $test_mode = false;

    protected $endpoint;
    protected $endpoint_alt;

    protected $user_name;
    protected $password;
    protected $bin;
    protected $merchant_id;
    protected $terminal_id;
    protected $currency_code;

    public function __construct()
    {

    }

    public function setCredentials($user_name, $password, $bin, $merchant_id, $terminal_id)
    {
        if (empty($user_name) || empty($password) || empty($bin) || empty($merchant_id) || empty($terminal_id)) {
            throw new BaseException('Please fill all credentials');
        }

        $this->user_name = $user_name;
        $this->password = $password;
        $this->bin = $bin;
        $this->merchant_id = $merchant_id;
        $this->terminal_id = $terminal_id;

        return $this;
    }

    public function updateEndpoints()
    {
        if (true === $this->isTestMode()) {

            $this->endpoint = 'https://orbitalvar1.paymentech.net/authorize';
            $this->endpoint_alt = 'https://orbitalvar2.paymentech.net/authorize';
        } else {

            $this->endpoint = 'https://orbital1.paymentech.net/authorize';
            $this->endpoint_alt = 'https://orbital2.paymentech.net/authorize';
        }

        return $this;
    }

    public function getEndpoints()
    {
        return [$this->endpoint, $this->endpoint_alt];
    }

    public function setTestMode($test_mode = true)
    {
        $this->test_mode = $test_mode;

        return $this;
    }

    public function isTestMode()
    {
        return (bool)$this->test_mode;
    }

    public function createCharge(CreditCard $card, Transaction $transaction)
    {
        $charge = (new Charge())->create($card, $transaction);

        $data = array_merge([
            'IndustryType' => static::INDUSTRY_TYPE_ECOMMERCE,
            'OrbitalConnectionUsername' => $this->user_name,
            'OrbitalConnectionPassword' => $this->password,
            'BIN' => $this->bin,
            'CustomerBin' => $this->bin,
            'MerchantID' => $this->merchant_id,
            'CustomerMerchantID' => $this->merchant_id,
            'TerminalID' => $this->terminal_id,
            'TxRefIdx' => ''
        ], $charge[1]);

        $request = $this->buildXMLRequest([$charge[0] => $data], new \SimpleXMLElement('<Request/>'));

        return $this->apiCall($charge[0], $request);
    }

    protected function buildXMLRequest(array $arr, \SimpleXMLElement &$xml)
    {
        foreach ($arr as $k => $v) {

            if (is_array($v)) {

                $node = $xml->addChild($k, null);

                $this->buildXMLRequest($v, $node);
            } else {

                $xml->addChild($k, $v);
            }
        }

        return $xml->asXML();
    }

    public function apiCall($action, $request, $use_alternative = false)
    {
        $headers = [
            sprintf('MIME-Version: %s', 'HTTP/1.1'),
            sprintf('Content-Type: %s', 'application/PTI62'),
            sprintf('Content-length: %d', strlen($request)),
            sprintf('Content-transfer-encoding: %s', 'text'),
            sprintf('Request-number: %d', 1),
            sprintf('Document-type: %s', 'Request'),
            sprintf('Interface-Version: %s', 'Blueprint')
        ];

        $endpoint = $use_alternative ? $this->endpoint_alt : $this->endpoint;

        try {

            return $this->curlExecute($endpoint, 'POST', $request, $headers);
        } catch (ApiConnectionException $e) {

            return $this->apiCall($action, $request, true);
        } finally {

            return false;
        }
    }

    protected function curlExecute($endpoint, $method = 'POST', $data = null, $headers = [], $options = [])
    {
        $options = array_replace(
            [
                CURLOPT_URL => $endpoint,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_USERAGENT => 'Blueprint',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYPEER => $this->isTestMode() === false,
                CURLOPT_FORBID_REUSE => true,
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FAILONERROR => true,
                CURLINFO_HEADER_OUT => true,
            ],
            $options
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

}