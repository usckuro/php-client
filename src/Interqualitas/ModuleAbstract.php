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
        $this->main->makeCall($this->modulePath, $id, $params);
    }
    
    public function create($data) {
        $this->main->makeCall($this->modulePath, '', $data, Interqualitas::METHOD_POST);
    }
    
    public function update($data) {
        $this->main->makeCall($this->modulePath, '', $data, Interqualitas::METHOD_PATCH);
    }
    
    public function delete($id, $params = []) {
        $this->main->makeCall($this->modulePath, '', $data, Interqualitas::METHOD_DELETE);
    }
    
    public function __construct(\Interqualitas $main) {
        $this->main = $main;
    }
}
