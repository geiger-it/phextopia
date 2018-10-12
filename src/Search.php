<?php

namespace Phextopia;

class Search
{

    use ArgumentTrait;

    private $client;

    protected $defaultArguments = [
        'page' => 1,
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->setDefaultArguments();
    }

    public function find($keywords = '', $arguments = [], $responseTimeoutInSeconds = 2.5, $format = 'json')
    {
        $this->client->setContentType($format);
        $this->client->responseTimeoutInSeconds = $responseTimeoutInSeconds;

        foreach ($arguments as $k => $v) {
            $this->client->upsertArgument($k, $v);
        }

        // passed $keywords supersedes $_GET['keywords'] (if set)
        $this->client->upsertArgument('keywords', $keywords);

        // prevent send if keywords not set
        if (!$this->client->hasArgument('keywords')) {
            throw new \InvalidArgumentException('Please enter a search term (keywords)');
        }

        return $this->client->sendRequest();

    }

    public function getUrl()
    {
        return $this->client->getUrl();
    }

}