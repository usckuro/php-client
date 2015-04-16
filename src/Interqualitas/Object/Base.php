<?php

namespace Interqualitas\Object;

abstract class Base {
    protected $properties = [];

    protected $required = [];

    public function __construct($data) {
        $this->populate($data);
    }

    public function populate($data) {
        if(is_array(($data))) {
            $data = (object)$data;
        }

        foreach($data as $key => $value) {
            if(array_key_exists($key, $this->properties)){
                $this->properties[$key] = $value;
            }
        }
    }
}