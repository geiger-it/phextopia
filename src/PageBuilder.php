<?php

namespace Phextopia;

class PageBuilder
{

    use ArgumentTrait;

    private $client;

    protected $defaultArguments = [
        'page' => 1
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function load($name = '', $format = 'json', $responseTimeoutInSeconds = 1.2)
    {
        $this->client->setContentType($format);
        $this->client->responseTimeoutInSeconds = $responseTimeoutInSeconds;

        // $pageName supersedes $_GET['nxt_landing_page']
        $this->client->upsertArgument('nxt_landing_page', $name);

        // prevent send if name not set
        if (!$this->client->hasArgument('nxt_landing_page')) {
            throw new \InvalidArgumentException('Please enter a page name (nxt_landing_page)');
        }

        return $this->client->sendRequest();

    }


}