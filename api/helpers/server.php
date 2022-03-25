<?php

class Response
{
    public $status = "DEFAULT";

    public array $items = [];

    public function __construct()
    {
        $this->status = "OK";
    }
    
    public function data($data)
    {

        if (is_array($data)) {
            $this->items = $data;
        } else {
            $this->items = [$data];
        }
    }

    public function HttpResponseCode(int $number, string $message, string $detail = "")
    {
        header($_SERVER["SERVER_PROTOCOL"] . " " . $message, true, $number);
        exit($detail);
    }

    public function checkEmpty() {
        if (empty($this->items)) {
            $this->status = "FAIL";
        }
    }
}