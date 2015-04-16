<?php

namespace Interqualitas;

use Interqualitas\ModuleAbstract;

/**
 * The API Calls to interact with vehicle makes
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class VehicleMake
extends ModuleAbstract{
    public function __construct(\Interqualitas $main) {
        parent::__construct($main);
        $this->modulePath = 'api/vehiclemake';
    }
}
