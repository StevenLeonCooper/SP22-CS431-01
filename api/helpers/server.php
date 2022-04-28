<?php

class Response
{
    public $status = "DEFAULT";

    private array $items = [];

    public function __construct()
    {
  
    }

    public function data($data)
    {
        $this->data = $data;

        if (is_array($data)) {
            $this->items = $data;
        } else {
            $this->items = [$data];
        }
    }

    private function build()
    {
        // Returns an object with an "items" array for multiple items
        // Or a singleton object if there's only 1 item
        $output = array();
        if (count($this->items) > 1) {
            $output['items'] = $this->items;
        }
        if (count($this->items) == 1) {
            $output = $this->items[0]; // Output converted from Array to Object
        }
        if(is_array($output)){
            $output['status'] = $this->status;
        }

        if(is_object($output)){
            $output->status = $this->status;
        }
        
        return $output;
    }

    public function outputJSON($data = null)
    {
        if ($data) $this->data($data);
        Header("Content-Type: application/json; charset=utf-8");
        exit(json_encode($this->build()));
    }

    public function HttpResponseCode(int $number, string $message, string $detail = "")
    {
        header($_SERVER["SERVER_PROTOCOL"] . " " . $message, true, $number);
        exit($detail);
    }
}