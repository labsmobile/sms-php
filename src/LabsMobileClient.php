<?php

namespace Labsmobile\SmsPhp;

use GuzzleHttp\BodySummarizer;
use Labsmobile\SmsPhp\LabsMobileModelTextMessage;
use Labsmobile\SmsPhp\LabsMobileModelCountryPrice;
use Labsmobile\SmsPhp\LabsMobileModelScheduledSendings;
use Labsmobile\SmsPhp\LabsMobileModelHlrRequest;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Ring\Client\CurlHandler;

use Labsmobile\SmsPhp\Exception\ParametersException;
use Labsmobile\SmsPhp\Exception\LabsMobileException;
use Labsmobile\SmsPhp\Exception\RestException;

class LabsMobileClient
{
  protected $token;
  protected $username;
  protected $body;
  protected $optionalParams;
  protected $urlBase = 'https://api.labsmobile.com';

  public function __construct($username, $token)
  {
    $this->token = $token;
    $this->username = $username;
  }

  /**
   * Get the value of token
   */
  public function getToken()
  {
    return $this->token;
  }

  /**
   * Set the value of token
   *
   * @return  self
   */
  public function setToken($token)
  {
    $this->token = $token;

    return $this;
  }

  /**
   * Get the value of username
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * Set the value of username
   *
   * @return  self
   */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
   * Get the value of body
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * Get the value of opcParameter
   */
  public function getOptionalParams()
  {
    return $this->optionalParams;
  }

  /**
   * Send a SMS.
   * 
   * @param LabsMobileModelTextMessage $textMessage this object contains the SMS data. See LabsMobileModelTextMessage class.
   * 
   * @return Response $response response object
   */
  public function sendSms(LabsMobileModelTextMessage $body): \GuzzleHttp\Psr7\Response
  {
    $response = null;
    $bodySms = [];
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      if ($body->getMsisdn() != null && !empty($body->getMsisdn())) {
        $recipients = [];
        foreach ($body->getMsisdn() as $phone) {
          $recipients[] = ['msisdn' => $phone];
        }
        $bodySms["recipient"] = $recipients;
      }
      if ($body->getMessage() != null && !empty($body->getMessage())) {
        $bodySms["message"] = $body->getMessage();
      }
      if ($body->getTpoa() != null && !empty($body->getTpoa())) {
        $bodySms["tpoa"] = $body->getTpoa();
      }
      if ($body->getSubid() != null && !empty($body->getSubid())) {
        $bodySms["subid"] = $body->getSubid();
      }
      if ($body->getLabel() != null && !empty($body->getLabel())) {
        $bodySms["label"] = $body->getLabel();
      }
      if ($body->getTest() != null && !empty($body->getTest())) {
        $bodySms["test"] = $body->getTest();
      }
      if ($body->getAckurl() != null && !empty($body->getAckurl())) {
        $bodySms["ackurl"] = $body->getAckurl();
      }
      if ($body->getShortlink() != null && !empty($body->getShortlink())) {
        $bodySms["shortlink"] = $body->getShortlink();
      }
      if ($body->getClickurl() != null && !empty($body->getClickurl())) {
        $bodySms["clickurl"] = $body->getClickurl();
      }
      if ($body->getScheduled() != null && !empty($body->getScheduled())) {
        $bodySms["scheduled"] = $body->getScheduled();
      }
      if ($body->getCrt() != null && !empty($body->getCrt())) {
        $bodySms["crt"] = $body->getCrt();
      }
      if ($body->getCrt_id() != null && !empty($body->getCrt_id())) {
        $bodySms["crt_id"] = $body->getCrt_id();
      }
      if ($body->getCrt_name() != null && !empty($body->getCrt_name())) {
        $bodySms["crt_name"] = $body->getCrt_name();
      }
      if ($body->getUcs2() != null && !empty($body->getUcs2())) {
        $bodySms["ucs2"] = $body->getUcs2();
      }
      if ($body->getNofilter() != null && !empty($body->getNofilter())) {
        $bodySms["nofilter"] = $body->getNofilter();
      }
      if ($body->getParameters() != null && !empty($body->getParameters())) {
        $bodySms["parameters"] = $body->getParameters();
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
          'POST',
          $this->urlBase . '/json/send',
          [
            'headers' => [
              'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
              'Cache-Control' => 'no-cache',
              'Content-Type' => 'application/json',
            ],
            'json' => [$bodySms]
          ]
        );
      } catch (\GuzzleHttp\Exception\ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if ($json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function getCredit()
  {
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
          'GET',
          $this->urlBase . '/json/balance',
          [
            'headers' => [
              'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
              'Cache-Control' => 'no-cache',
              'Content-Type' => 'application/json',
            ]
          ]
        );
      } catch (\GuzzleHttp\Exception\ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      echo json_encode($json);
      if ($json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function getpricesCountry(LabsMobileModelCountryPrice $body)
  {
    $countries = !empty($body->getPrice()) ? $body->getPrice() : null;
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
          'POST',
          $this->urlBase . '/json/prices',
          [
            'headers' => [
              'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
              'Cache-Control' => 'no-cache',
              'Content-Type' => 'application/json',
            ],
            'json' => [
              'countries' => $countries
  
            ]
          ]
        );
      } catch (\GuzzleHttp\Exception\ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if (property_exists($json, 'code') && $json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function scheduledSendings(LabsMobileModelScheduledSendings $body)
  {
    $subid = $body->getSubid();
    $cmd = $body->getCmd();
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
          'POST',
          $this->urlBase . '/json/scheduled',
          [
            'headers' => [
              'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
              'Cache-Control' => 'no-cache',
              'Content-Type' => 'application/json',
            ],
            'json' => [
              'subid' => $subid,
              'cmd' => $cmd
            ]
          ]
        );
      } catch (\GuzzleHttp\Exception\ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if ($json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function hlrRequest(LabsMobileModelHlrRequest $body)
  {
    $numbers = $body->getNumbers();
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
          'GET',
          $this->urlBase . '/hlr/?numbers=' . $numbers,
          [
            'headers' => [
              'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
              'Cache-Control' => 'no-cache',
            ]
          ]
        );
      } catch (\GuzzleHttp\Exception\ServerException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if ($response->getStatusCode() != 200) {
        throw new RestException($json->error, $response->getStatusCode());
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }
}
