<?php
/**
 * Created by PhpStorm.
 * User: Karthik
 * Date: 12/27/2018
 * Time: 3:08 PM
 */

namespace Epro\Crm;


abstract class CrmBase
{
    protected $url;
    protected $client_id;
    protected $client_secret;

    public $login = false;

    abstract public function login($username, $password, array $options);

    abstract public function getAccounts(array $options = []);

    abstract public function getContacts(array $options = []);

    abstract public function getLeads(array $options = []);

    abstract public function getMeetings(array $options = []);

    public function __construct(array $config)
    {
        $this->url = trim(trim($config["url"]), '/');
        $this->client_id = $config["client_id"];
        $this->client_secret = $config["client_secret"];
    }

    protected function curlPost($url, array $bodyParams = [], array $headerParams = [])
    {
        $curl = curl_init($url);
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_POST => TRUE,
                CURLOPT_HTTPHEADER => $headerParams,
                CURLOPT_POSTFIELDS => http_build_query(
                    $bodyParams
                )
            )
        );

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo curl_error($curl);
            curl_close($curl);
            exit ();
        }
        curl_close($curl);

        return json_decode($response, true);
    }

    protected function curlGet($url, array $headerParams = [])
    {
        $curl = curl_init($url);
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => $headerParams
            )
        );

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo curl_error($curl);
            curl_close($curl);
            exit ();
        }
        curl_close($curl);

        return json_decode($response, true);
    }
}