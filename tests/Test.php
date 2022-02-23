<?php

declare(strict_types=1);

use Logan\Hunan\AES;
use Logan\Hunan\Client;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    protected $domain = 'http://113.247.238.148:8765/';
    protected $appId = 'f5402ea3d45748899bc53ae62d6fde3f';
    protected $AESKey = 'f16c23ec308f426f85855857b4ab21b0';

    public function testQueryProjectCode()
    {
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryProjectCode('431127202101280101')->send();
    }

    public function testQueryProject()
    {
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryProject()->send();
    }

    public function testQueryProjectCompany()
    {
        $proCode = '6139685';
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryProjectCompany($proCode)->send();
    }

    public function testQueryGroup()
    {
        $proCode = '6139685';
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryGroup($proCode)->send();
    }

    public function testQueryManagerGroup()
    {
        $proCode = '6139685';
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryManagerGroup($proCode)->send();
    }

    public function testQueryContract()
    {
        $proCode = '6139685';
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryContract($proCode)->send();
        $data = $res["data"]["records"][0];
        $AES = new AES($this->AESKey, 'aes-256-cbc', AES::OUTPUT_BASE64, $this->AESKey);
        $data = $AES->decrypt($data["idCardNumber"]);
    }

    public function testQueryWorkerInfo()
    {
        $proCode = '6139685';
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryWorkerInfo($proCode)->send();
    }

    public function testQueryManagerWorkerInfo()
    {
        $proCode = '6139685';
        $idCode = '431127198405150019';
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryManagerWorkerInfo($proCode, $idCode)->send();
    }

    public function testQueryProjectWorker()
    {
        $proCode = '6139685';
        $idCode = '1qhbJU/DDAZjdXMlzJHWGYiRVb1h0slPsrgPiFmX1tI=';
        // $idCode = (new AES($this->AESKey, 'aes-256-cbc', AES::OUTPUT_BASE64, $this->AESKey, OPENSSL_RAW_DATA))->encrypt($idCode);
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryProjectWorker($proCode, '', '', null, '01', $idCode, 0, 10)->send();
    }


    public function testQueryAttendance()
    {
        $proCode = '6139685';
        $instance = new Client($this->domain, $this->appId);
        $res = $instance->queryAttendance($proCode, '2022-02-23')->send();
    }
}
