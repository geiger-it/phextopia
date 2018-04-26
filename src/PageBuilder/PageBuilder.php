<?php

namespace Phextopia\PageBuilder;

use Phextopia\Client;

class PageBuilder
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}