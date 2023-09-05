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
namespace Package\Winipayer;

 */
class WiniPayer
{

    /**
     * URL de l'API Winipayer
     * @var string
     */
    private string $_endpoint = 'https://api.winipayer.com';

    /**
     * Version de l'API
     * @var string
     */
    private string $_version = 'v1';

    /**
     * // Environnement (prod ou test)
     * @var string
     */
    private string $_env;


    /**
     * Clé d'application
     * @var string
     */
    private string $_apply_key;

    /**
     *  Clé de token
     * @var string
     */
    private string $_token_key;

    /**
     * Clé privée
     * @var string
     */
    private string $_private_key;

    /**
     * Devise par défaut
     * @var string
     */
    private string $_currency = 'xof';

    /**
     *  Liste des canaux operateurs
     * @var array
     */
    private array $_channel = [];

    /**
     * Liste des articles
     * @var array
     */
    private array $_items = [];

    /**
     * Propriétaire du client
     * @var string
     */
    private string $_customer_owner = '';

    /**
     * Données personnalisées
     * @var array
     */
    private array $_custom_data = [];

    /**
     * URL d'annulation
     * @var string
     */
    private string $_cancel_url = '';

    /**
     * URL de retour
     * @var string
     */
    private string $_return_url = '';

    /**
     * Sécurité des transactions
     * @var string
     */
    private string $_wpsecure = 'false';

    /**
     * URL de rappel
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
    public function __construct(string $env = 'test', string $apply_key, string $token_key, string $private_key)
    {
        $this->_env = (in_array($env, ['prod', 'test'])) ? $env : 'test';
        $this->_apply_key = $apply_key;
        $this->_token_key = $token_key;
        $this->_private_key = $private_key;
    }

    /**
     * Méthode pour Setter l'URL de l'API
     *
     * @param string $url
     * @return Winipayer
     */
    public function setEndpoint(string $url): Winipayer
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Winipayer : setEndpoint => Invalid endpoint URL.');
        }
        $this->_endpoint = $url;
        return $this;
    }

    /**
     * Méthode pour Setter l'URL d'annulation
     *
     * @param string $url
     * @return Winipayer
     */
    public function setCancelUrl(string $url): Winipayer
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Winipayer : setCancelUrl => Invalid cancel URL.');
        }
        $this->_cancel_url = $url;
        return $this;
    }

    /**
     * Méthode pour Setter l'URL de retour
     *
     * @param string $enpoint New enpoint url
     * @return Winipayer
     */
    public function setReturnUrl(string $url): Winipayer
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Winipayer : setReturnUrl => Invalid return URL.');
        }
        $this->_return_url = $url;
        return $this;
    }

    /**
     * Méthode pour Setter l'URL de rappel
     *
     * @param string $enpoint
     * @return Winipayer
     */
    public function setCallbackUrl(string $url): Winipayer
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Winipayer : setCallbackUrl => Invalid callback URL.');
        }
        $this->_callback_url = $url;
        return $this;
    }

    /**
     * Méthode pour Setter les opérateurs(canaux) de la transaction
     *
     * @param array $channel
     * @return Winipayer
     */
    public function setChannel(array $channel): Winipayer
    {
        $this->_channel = $channel;
        return $this;
    }


    /**
     * Méthode pour Setter la sécurité de la transaction
     * 
     * @param string $wpsecure
     * @return Winipayer
     */
    public function setWpsecure(string $wpsecure): Winipayer
    {
        $this->_wpsecure = $wpsecure;
        return $this;
    }

    /**
     * Méthode pour Setter le propriétaire de la transaction
     *
     * @param string $uuid
     * @return Winipayer
     */
    public function setCustomerOwner(string $uuid): Winipayer
    {
        if (!$this->_uuid($uuid)) {
            throw new \Exception('Winipayer : setCustomerOwner => Invalid customer owner uuid.');
        }
        $this->_customer_owner = $uuid;
        return $this;
    }

    /**
     * Méthode pour Setter des données personnalisées
     *
     * @param array $data
     * @return Winipayer
     */
    public function setCustomData(array $data): Winipayer
    {
        $this->_custom_data = $data;
        return $this;
    }

    /**
     * Méthode pour Setter les éléments que constitue la facture
     *
     * @param array $items
     * @return Winipayer
     */
    public function setItems(array $items): Winipayer
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

        if (!$this->_uuid($uuid)) {
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

        if (!$this->_uuid($uuid)) {
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
            throw new \Exception('OrangeSms :  ' . $error);
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
    private function _uuid(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }
}