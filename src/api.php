<?php
/**
 * wApi Class
 *
 * @version 1.0.2
 *
 * @author  Artur Mamedov <arturmamedov1993@gmail.com>
 */
class wApi
{

    /**
     * Contains the last HTTP status code returned.
     */
    public $http_code;

    /**
     * Contains the last API call.
     */
    public $url;

    /**
     * Set up the API root URL.
     */
    public $host = '';

    /**
     *  Set timeout default.
     */
    public $timeout = 30;

    /**
     * Set connect timeout.
     */
    public $connecttimeout = 30;

    /**
     * Response format.
     */
    public $format = 'json';

    /**
     *  Decode returned json data.
     */
    public $decode_json = true;

    /**
     *  Contains the last HTTP headers returned.
     */
    public $http_info;

    /**
     * Contain user authentication
     * @var type
     */
    public $auth;

    // * Immediately retry the API call if the response was not successful. */
    //public $retry = TRUE;


    /**
     * Construct wApi object
     *
     * @param $consumer_name     Name of API consumer 'Bearer'
     * @param $consumer_password Password of API consumer
     *
     */
    function __construct($consumer_name, $consumer_password)
    {
        $this->auth = 'Authorization: '.$consumer_name.' '.$consumer_password;

        // if host not set, set it with the self http host
        if (empty($this->host)) {
            $this->host = $_SERVER['HTTP_HOST'].'/api';
        }
    }


    /**
     * Debug helpers
     */
    function lastStatusCode()
    {
        return $this->http_status;
    }


    function lastAPICall()
    {
        return $this->last_api_call;
    }


    /**
     * Make an HTTP request
     *
     * @param string $endpoint
     * @param string $method    HTTP Method GET/POST
     * @param array $postfields if Method POST
     *
     * @return API results
     */
    function http($endpoint, $method, $postfields = null, $options = [])
    {
        $this->http_info = [];
        $ci = curl_init();

        // we wont JSON
        $headers[] = $this->auth;
        $headers[] = "Accept: application/json, text/javascript";
        $headers[] = "X-Requested-With: XMLHttpRequest"; // if its a Ajax Request

        if (isset($options['locale'])) {
            $headers[] = "X-Lang: {$options['locale']}";
        }

        // Curl settings
        //curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        //curl_setopt($ci, CURLOPT_USERPWD, $this->auth);
        //curl_setopt($ci, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, [ $this, 'getHeader' ]);
        curl_setopt($ci, CURLOPT_HEADER, false);
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, true);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, true);
                if ( ! empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($postfields));
                }
                break;
            //case 'DELETE':
            //    curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
            //    if ( ! empty($postfields)) {
            //        $url = "{$url}?{$postfields}";
            //    }
        }

        $url = $this->host.$endpoint;

        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));

        $this->url = $url;

        curl_close($ci);

        return $response;
    }


    /**
     * Get the header info to store.
     */
    function getHeader($ch, $header)
    {
        $i = strpos($header, ':');

        if ( ! empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }

        return strlen($header);
    }
}