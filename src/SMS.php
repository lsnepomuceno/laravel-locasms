<?php

namespace LSNepomuceno\LaravelLocasms;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\{PendingRequest, Response};

class SMS extends Http
{
  /** @var \Illuminate\Http\Client\PendingRequest */
  protected PendingRequest $client;

  /**  @var string */
  private string $login, $pass;

  /** @var string */
  private const URL = 'http://209.133.205.2/painel/api.ashx';

  /** @var array */
  private array $params;

  /**
   * __construct
   *
   * @param  string $login
   * @param  string $pass
   * @return void
   */
  public function __construct(string $login, string $pass)
  {
    $this->login  = $login;
    $this->pass   = $pass;
    $this->client = self::withOptions(['verify' => false])->baseUrl(self::URL);
  }

  /**
   * cleanNumbers - Format numbers to Brazilian format without country code
   *
   * @param  array $numbers
   * @return array
   */
  private function cleanNumbers(array $numbers): array
  {
    return array_map(function ($number) {
      $number = preg_replace("/\D/", '', $number);
      return substr($number, -11);
    }, $numbers);
  }

  /**
   * performGet - Prepare get
   *
   * @throws \Exception
   *
   * @return \Illuminate\Http\Client\Response
   */
  private function performGet(): Response
  {
    $response = $this->client->get('', $this->params);

    if (!$response->body()['status']) {
      throw new \Exception($response->body()['msg']);
    }

    return $response;
  }

  /**
   * send - Send sms mesages from defined numbers
   *
   * @param  array $numbers
   * @param  string $message
   *
   * @throws \Exception
   *
   * @return \Illuminate\Http\Client\Response
   */
  public function send(array $numbers, string $message): Response
  {
    if (strlen($message) > 200) {
      throw new \Exception('Message limit above 200 characters.');
    }

    $this->params = [
      'action'  => 'sendsms',
      'lgn'     => $this->login,
      'pwd'     => $this->pass,
      'msg'     => $message,
      'numbers' => join(',', $this->cleanNumbers($numbers))
    ];

    return $this->performGet();
  }

  /**
   * getBalance - Receive sms balance for account
   *
   * @return \Illuminate\Http\Client\Response
   */
  public function getBalance(): Response
  {
    $this->params = [
      'action'  => 'getbalance',
      'lgn'     => $this->login,
      'pwd'     => $this->pass
    ];

    return $this->performGet();
  }
}
