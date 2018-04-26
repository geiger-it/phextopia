<?php

namespace Phextopia\Search;

use Phextopia\Client;

class Search
{
    private $client;

    protected $defaultArguments = [
        'page' => 1,
        'json' => 1,
        'xml' => 0,
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
        foreach ($this->defaultArguments as $k => $v) {
            $this->client->upsertArgument($k, $v);
        }
    }

    public function search($keywords = '', $arguments = [])
    {

        foreach ($arguments as $k => $v) {
            $this->client->upsertArgument($k, $v);
        }
        // passed keyword supersedes $_GET (if set)
        $this->client->upsertArgument('keywords', $keywords);
        if (!$this->client->hasArgument('keywords')) {
            throw new \InvalidArgumentException('Please enter a search term (keywords)');
        }
        return $this->client->sendRequest();
    }

    public function addArgument($name, $value)
    {
        if ($this->client->hasArgument($name) && in_array($name, $this->client->singletonArguments)) {
            $this->client->upsertArgument($name, $value);
        } else {
            $this->client->addArgument($name, $value);
        }
        return $this;
    }

    public function removeArgument($name)
    {
        $this->client->removeArgument($name);
        return $this;
    }

    public function getUrl()
    {
        return $this->client->getUrl();
    }

}