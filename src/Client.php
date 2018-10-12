<?php

namespace Phextopia;

class Client
{
    protected $client_id;
    protected $ip;
    protected $user_agent;
    protected $arguments = [];
    public $rejectedUserArguments;
    public $responseTimeoutInSeconds = 3.0;

    protected $defaultArguments = ['keywords', 'page', 'xml', 'abstracted_fields', 'force_or_search', 'initial_sort',
        'initial_sort_order', 'no_metaphones', 'no_stemming', 'refinements', 'refine', 'requested_fields',
        'res_per_page', 'searchtype', 'sort_by_field', 'trim_length', 'trimmed_fields', 'and_refines',
        'compact_refines', 'custom_ranges', 'paginate_refines', 'refines_mode', 'requested_refines',
        'return_single_refines', 'disable_merchandizing', 'ignore_redirects', 'cache_call', 'min_max_values',
        'related_searches', 'json'];

    protected $singletonArguments = ['json', 'xml', 'page'];

    const BASE_URL = 'https://sapi.nextopiasoftware.com/return-results';
    const RESPONSE_CONTENT_TYPES = ['html', 'xml', 'json'];
    const DEFAULT_RESP_CONT_TYPE = 'json';

    public function __construct($client_id, $useHttpGetForArguments = true, array $refinements = [], $ip = "", $user_agent = "")
    {
        $this->client_id = $client_id;
        if(!$this->client_id){
            throw new \InvalidArgumentException('Missing Nextopia Client Id');
        }
        $this->ip = $ip ?: $_SERVER['REMOTE_ADDR'];
        $this->user_agent = $user_agent ?: $_SERVER['HTTP_USER_AGENT'];
        $arguments = array_unique(array_merge($this->defaultArguments, $refinements));
        if ($useHttpGetForArguments) {
            foreach ($_GET as $k => $v) {
                if (in_array($k, $arguments)) {
                    $this->addArgument($k, $v);
                } else {
                    $this->rejectedUserArguments[$k] = $v;
                }
            }
        }
    }

    /**
     * Set the response content type (or default)
     * This will control parameters: xml=0 (html), xml=1 (xml), json=0 (html), json=1 (json), json=1&xml=1 (json)
     * In other words, html is the default when neither xml or json is set.
     * @param $type
     * @return string
     */
    public function setContentType($type)
    {
        if (in_array($type, static::RESPONSE_CONTENT_TYPES)) {
            $this->removeArgument('xml');
            $this->removeArgument('json');
            if ($type == 'json') {
                $this->addArgument('json', 1);
            }
            if ($type == 'xml') {
                $this->addArgument('xml', 1);
            }

        }
    }

    public function sendRequest()
    {
        $guzzleClient = new \GuzzleHttp\Client;
        //dump($this->generateUrl());
        $response = $guzzleClient->request('GET', $this->generateUrl(), ['connect_timeout' => $this->responseTimeoutInSeconds]);
        if ($response->getStatusCode() == 200 && $response->getBody()) {
            return $response->getBody();
        }
    }

    public function addArgument($name, $value)
    {
        $this->arguments[$name] = $value;
    }

    public function upsertArgument($name, $value)
    {
        $matched = false;
        foreach ($this->arguments as $k => $v) {
            if ($k == $name) {
                $matched = true;
                if (!empty($value)) {
                    $this->arguments[$k] = $value;
                }
            }
        }
        if (!$matched) {
            $this->arguments[$name] = $value;
        }
    }

    public function removeArgument($name)
    {
        foreach ($this->arguments as $k => $v) {
            if ($k == $name) {
                unset($k);
            }
        }
    }

    public function hasArgument($name)
    {
        return !empty($this->arguments[$name]);
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    private function generateUrl()
    {
        $this->upsertArgument('client_id', $this->client_id);
        $this->upsertArgument('ip', $this->ip);
        $this->upsertArgument('user_agent', $this->user_agent);
        return static::BASE_URL . '?' . http_build_query(array_merge($this->arguments));
    }
}
