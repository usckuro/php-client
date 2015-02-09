<?php

namespace Interqualitas;

use Interqualitas\ModuleAbstract;

/**
 * The API Calls to interact with vehicles
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class Vehicle extends ModuleAbstract{
    public function __construct(\Interqualitas $main) {
        parent::__construct($main);
        $this->modulePath = 'api/vehicle';
    }
}
