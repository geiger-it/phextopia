<?php


namespace Phextopia;


trait ArgumentTrait
{

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

    public function setDefaultArguments()
    {
        if(!empty($this->defaultArguments)){
            foreach ($this->defaultArguments as $k => $v) {
                $this->client->upsertArgument($k, $v);
            }
        }
    }

}