<?php

namespace turkmvc\Hepsiburada;
use GuzzleHttp\Client;

class Hepsiburada {

    private $conf = null;
    private $client = null;
    private $products_uri = 'https://listing-external-sit.hepsiburada.com';
    private $order_uri = 'https://oms-stub-external-sit.hepsiburada.com';
    private $package_uri = 'https://oms-external-sit.hepsiburada.com';

  function __construct($conf){
    
    if(!isset($conf['username']) || !isset($conf['password']) || !isset($conf['merchant_id'])) {
      throw new HepsiburadaException("Hepsiburada Ayarları Girilmedi");
    } else {
      
        $this->conf = $conf;

        $this->client = new Client([
            'timeout'  => 10,
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);


    }

  }
  
  // Products
  public function products($data = []){

    $params = [
      'query' => $data
    ];

    $uri = sprintf("%s/listings/merchantid/%s", $this->products_uri, $this->conf['merchant_id']);

  
    return $this->MakeRequest('get', $uri , $params);

  }

  // Create Product
  public function creaateProduct($data = [], $uri_params){

    $params = [
      'query' => $uri_params,
      'form_params' => $data
    ];

    $uri = sprintf("%s/listings/merchantid/%s/inventory-uploads", $this->products_uri, $this->conf['merchant_id']);

  
    return $this->MakeRequest('post', $uri , $params);

  }

   // Create Order
   public function createOrder($data = [], $uri_params){
    
    $params = [
      'query' => $uri_params,
      'form_params' => $data
    ];

    $uri = sprintf("%s/orders/merchantid/%s", $this->order_uri, $this->conf['merchant_id']);

    return $this->MakeRequest('post', $uri , $params);

  }

  // Package
  public function package($package_number, $data){

    $params = [
      'query' => $data
    ];

    $uri = sprintf("%s/packages/merchantid/%s/packagenumber/%s", $this->package_uri, $this->conf['merchant_id'], $package_number);

    return $this->MakeRequest('get', $uri , $params);

  }

  // Packages
  public function packages($data= []){

    $params = [
      'query' => $data
    ];

    $uri = sprintf("%s/packages/merchantid/%s", $this->package_uri, $this->conf['merchant_id']);

    return $this->MakeRequest('get', $uri , $params);

  }

  // Claims
  public function claims($data){

    $params = [
      'query' => $data
    ];

    $uri = sprintf("%s/claims/merchantid/%s", $this->package_uri, $this->conf['merchant_id']);

    return $this->MakeRequest('get', $uri , $params);

  }

  // Claim Status 
  public function claimStatus($status, $data){

    $params = [
      'query' => $data
    ];

    $uri = sprintf("%s/claims/merchantid/%s/status/%s", $this->package_uri, $this->conf['merchant_id'], $status);

    return $this->MakeRequest('get', $uri , $params);

  }

  // Claim Action
  public function claimAction($claimid, $claimStatus, $data){

    $params = [
      'query' => $data
    ];

    $uri = sprintf("%s/claims/id/%s/%s", $this->package_uri, $claimid, $claimStatus);

    return $this->MakeRequest('post', $uri , $params);

  }

  private function MakeRequest($method, $uri, $data){
  
    try {

      $request = $this->client->$method($uri, $data, [
        'auth' => [
            null,
            base64_encode(sprintf('%s%s', $this->conf['username'], $this->conf['password']))
        ]
      ]);

      $status = $request->getStatusCode();

      if($status == 200){
          
        $response = json_decode($request->getBody(), true);
  
      }else if($status == 401){

        throw new HepsiburadaException('Kullanıcı bilgileriniz Geçersiz.');

      } else {

        throw new HepsiburadaException('İşleminizi Gerçekleştiremiyoruz.');

      }

    } catch (\GuzzleHttp\Exception\ConnectException $e) {
   
      throw new HepsiburadaException('Hepsiburada ile bağlantı sağlanamıyor.');
    
    }

    return $response;

  }

}
