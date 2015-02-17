<?php

namespace Interqualitas;

use Interqualitas;

abstract class ModuleAbstract {
    
    /**
     *
     * @var $modulePath The module path 
     */
    protected $modulePath = '';
    
    
    /**
     *
     * @var Interqualitas $main The main API class
     */
    protected $main;
    
    public function fetch($id = '', $params = []) {
        return $this->main->makeCall($this->modulePath, $id, $params);
    }
    
    public function create($data) {
        return $this->main->makeCall($this->modulePath, '', $data, Interqualitas::METHOD_POST);
    }
    
    public function update($data) {
        return $this->main->makeCall($this->modulePath, '', $data, Interqualitas::METHOD_PATCH);
    }
    
    public function delete($id, $params = []) {
        return $this->main->makeCall($this->modulePath, $id, $params, Interqualitas::METHOD_DELETE);
    }
    
    public function __construct(\Interqualitas $main) {
        $this->main = $main;
    }
}
