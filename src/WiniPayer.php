<?php

/**
 * @package Winipayer
 * @version 1.0.0
 */
/*
Plugin Name: WiniPayer
Description: WiniPayer your online payment API, easy integration on mobile and web applications.
Author: Jars Technologies
Version: 1.0.0
Author URI: https://www.jarstechnologies.com/
namespace winipayer/winipayerphpsdk;

 */
class WiniPayer
{

    /**
     * API EndPoint
     * @var string
     */
    private string $_endpoint = 'https://api.winipayer.com';

    /**
     * API version
     * @var string
     */
    private string $_version = 'v1';

    /**
     * Environment (prod or test)
     * @var string
     */
    private string $_env = 'test';


    /**
     * Apply key
     * @var string
     */
    private string $_apply_key;

    /**
     *  Token key
     * @var string
     */
    private string $_token_key;

    /**
     * Private key
     * @var string
     */
    private string $_private_key;

    /**
     * Default currency
     * @var string
     */
    private string $_currency = 'xof';

    /**
     *  List of operating channels
     * @var array
     */
    private array $_channel = [];

    /**
     * List of articles
     * @var array
     */
    private array $_items = [];

    /**
     * Customer Owner
     * @var string
     */
    private string $_customer_owner = '';

    /**
     * Personalized data
     * @var array
     */
    private array $_custom_data = [];

    /**
     * Cancel URL
     * @var string
     */
    private string $_cancel_url = '';

    /**
     * Return URL
     * @var string
     */
    private string $_return_url = '';

    /**
     * Transaction Security
     * @var bool
     */
    private string $_wpsecure = false;

    /**
     * Callback URL
     * @var string
     */
    private string $_callback_url = '';

    /**
     * Class constructor
     *
     * @param string $env 
     * @param string $apply_key
     * @param string $token_key
     * @param string $private_key
     */
    public function __construct(string $apply_key, string $token_key, string $private_key, string $env = 'test')
    {
        $this->_apply_key = $apply_key;
        $this->_token_key = $token_key;
        $this->_private_key = $private_key;
        $this->_env = (in_array($env, ['prod', 'test'])) ? $env : 'test';
    }

    /**
     * Méthode pour Setter l'URL de l'API
     *
     * @param string $url
     * @return WiniPayer
     */
    public function setEndpoint(string $url): WiniPayer
    {
        if (!$this->_isLink($url)) {
            throw new \Exception('Winipayer : setEndpoint => Invalid endpoint URL.');
        }
        $this->_endpoint = $url;
        return $this;
    }

    /**
     * Méthode pour Setter l'URL d'annulation
     *
     * @param string $url
     * @return WiniPayer
     */
    public function setCancelUrl(string $url): WiniPayer
    {
        if (!$this->_isLink($url)) {
            throw new \Exception('Winipayer : setCancelUrl => Invalid cancel URL.');
        }
        $this->_cancel_url = $url;
        return $this;
    }

    /**
     * Méthode pour Setter l'URL de retour
     *
     * @param string $enpoint New enpoint url
     * @return WiniPayer
     */
    public function setReturnUrl(string $url): WiniPayer
    {
        if (!$this->_isLink($url)) {
            throw new \Exception('Winipayer : setReturnUrl => Invalid return URL.');
        }
        $this->_return_url = $url;
        return $this;
    }

    /**
     * Méthode pour Setter l'URL de rappel
     *
     * @param string $enpoint
     * @return WiniPayer
     */
    public function setCallbackUrl(string $url): WiniPayer
    {
        if (!$this->_isLink($url)) {
            throw new \Exception('Winipayer : setCallbackUrl => Invalid callback URL.');
        }
        $this->_callback_url = $url;
        return $this;
    }

    /**
     * Méthode pour Setter les opérateurs(canaux) de la transaction
     *
     * @param array $channel
     * @return WiniPayer
     */
    public function setChannel(array $channel): WiniPayer
    {
        $this->_channel = $channel;
        return $this;
    }


    /**
     * Méthode pour Setter la sécurité de la transaction
     * 
     * @param bool $wpsecure
     * @return WiniPayer
     */
    public function setWpsecure(bool $wpsecure): WiniPayer
    {
        $this->_wpsecure = $wpsecure;
        return $this;
    }

    /**
     * Méthode pour Setter le propriétaire de la transaction
     *
     * @param string $uuid
     * @return WiniPayer
     */
    public function setCustomerOwner(string $uuid): WiniPayer
    {
        if (!$this->_isUuid($uuid)) {
            throw new \Exception('Winipayer : setCustomerOwner => Invalid customer owner uuid.');
        }
        $this->_customer_owner = $uuid;
        return $this;
    }

    /**
     * Méthode pour Setter des données personnalisées
     *
     * @param array $data
     * @return WiniPayer
     */
    public function setCustomData(array $data): WiniPayer
    {
        $this->_custom_data = $data;
        return $this;
    }

    /**
     * Méthode pour Setter les éléments que constitue la facture
     *
     * @param array $items
     * @return WiniPayer
     */
    public function setItems(array $items): WiniPayer
    {

        foreach ($items as $key => $value) {

            if (!isset($value['name']) || !is_string($value['name']) || strlen($value['name']) < 2) {
                throw new \Exception('Winipayer : setItems => Invalid item name.');
            }

            if (!isset($value['quantity']) || !is_int($value['quantity'])) {
                throw new \Exception('Winipayer : setItems => Invalid item quantity.');
            }

            if (!isset($value['unit_price']) || !is_int($value['unit_price'])) {
                throw new \Exception('Winipayer : setItems => Invalid item unit_price.');
            }

            $total_price = $value['quantity'] * $value['unit_price'];

            if (!isset($value['total_price']) || !is_int($value['total_price']) || $value['total_price'] != $total_price) {
                throw new \Exception('Winipayer : setItems => Invalid item total_price.');
            }

            $this->_items[] = $value;
        }

        return $this;
    }


    /**
     * Méthode de création d'une facture
     *
     * @param float $amount
     * @param string $description
     * @param string $currency
     * @param string $cancel_url
     * @param string $return_url
     * @param string $callback_url
     * @return array
     */
    public function createInvoice(float $amount, string $description, string $cancel_url = '', string $return_url = '', string $callback_url = '', string $currency = 'xof', string $wpsecure = 'false'): array
    {

        $params = [
            'env' => $this->_env,
            'version' => $this->_version,
            'amount' => $amount,
            'wpsecure' => !empty($wpsecure) ? $wpsecure : $this->_wpsecure,
            'currency' => $currency ?? $this->_currency,
            'description' => $description,
            'cancel_url' => !empty($cancel_url) ? $cancel_url : $this->_cancel_url,
            'return_url' => !empty($return_url) ? $return_url : $this->_return_url,
            'callback_url' => !empty($callback_url) ? $callback_url : $this->_callback_url,
        ];

        if (!empty($this->_channel)) {
            $params['channel'] = $this->_channel;
        }
        if (!empty($this->_customer_owner)) {
            $params['customer_owner'] = $this->_customer_owner;
        }
        if (!empty($this->_items)) {
            $params['items'] = json_encode($this->_items);
        }
        if (!empty($this->_custom_data)) {
            $params['custom_data'] = json_encode($this->_custom_data);
        }

        $headers = [
            'X-Merchant-Apply' => $this->_apply_key,
            'X-Merchant-Token' => $this->_token_key,
        ];

        return $this->_curl('POST', '/transaction/invoice/create', $params, $headers);
    }

    /**
     * Méthode pour obtenir les détails d'une facture
     * 
     * @param string $uuid
     * @throws \Exception
     * @return array
     */
    public function detailInvoice(string $uuid): array
    {

        if (!$this->_isUuid($uuid)) {
            throw new \Exception('Winipayer : detailInvoice => Invalid invoice uuid.');
        }

        $params = [
            'env' => $this->_env,
            'version' => $this->_version,
        ];

        $headers = [
            'X-Merchant-Apply' => $this->_apply_key,
            'X-Merchant-Token' => $this->_token_key,
        ];

        return $this->_curl('POST', '/transaction/invoice/detail/' . $uuid, $params, $headers);
    }

    /**
     * Méthode pour verifier si une facture est validée
     * 
     * @param string $uuid
     * @param float $amount
     * @throws \Exception
     * @return bool
     */
    public function valideInvoice(string $uuid, float $amount): bool
    {

        if (!$this->_isUuid($uuid)) {
            throw new \Exception('Winipayer : detailInvoice => Invalid invoice uuid.');
        }

        $response = $this->detailInvoice($uuid);

        if (!isset($response['success']) || $response['success'] !== true) {
            return false;
        }

        $invoice = $response['results'];

        $id = $invoice['uuid'] ?? '';
        $hash = $invoice['hash'] ?? '';
        $env = $invoice['env'] ?? '';
        $state = $invoice['state'] ?? '';
        $total = $invoice['amount'] ?? 0;

        if (
            $id !== $uuid ||
            hash('sha256', $this->_private_key) !== $hash ||
            $env !== $this->_env ||
            $state !== 'success' ||
            $total < $amount
        ) {
            return false;
        }

        return true;
    }

    /**
     * Méthode privée pour effectuer des requêtes HTTP avec _curl
     *
     * @param string $method
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return array
     */
    private function _curl(string $method = 'POST', string $url, array $params = [], array $headers = []): array
    {

        $url = $this->_endpoint . $url;

        $headerFields = [];
        foreach ($headers as $key => $value) {
            $headerFields[] = $key . ': ' . $value;
        }

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $params,
                CURLOPT_HTTPHEADER => $headerFields,
            )
        );

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            throw new \Exception('WiniPayer :  ' . $error);
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * Fonction pour verifier si un UUID est valide
     *
     * @param string $uuid
     * @return boolean
     */
    private function _isUuid(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }

    /**
     * Verify is link
     * @param string $link
     * @return bool
     */
    private function _isLink(string $link): bool
    {
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }
}