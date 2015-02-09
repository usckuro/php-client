<?php

namespace Interqualitas;

use Interqualitas\ModuleAbstract;

/**
 * The API Calls to interact with geo resources
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class Geo extends ModuleAbstract{
    public function __construct(\Interqualitas $main) {
        parent::__construct($main);
        $this->modulePath = 'api/geo';
    }
}
