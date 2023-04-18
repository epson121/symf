<?php

namespace App\Message;

class TestMessage {

    public function __construct(private $content = null)
    { }

    public function getContent()
    {
        return $this->content;
    }
    
}