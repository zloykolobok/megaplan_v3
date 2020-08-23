<?php


namespace Zloykolobok\Megaplan_v3;


class Megaplan
{
    protected $key;
    protected $domain;

    public function __construct($key, $domain)
    {
        $this->key = $key;
        $this->domain = $domain;
    }

    public function send($action, $method = 'GET')
    {

    }

}
