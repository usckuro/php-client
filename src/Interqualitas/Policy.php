<?php

namespace Interqualitas;

use Interqualitas\ModuleAbstract;

/**
 * The API Calls to interact with policy holders
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class Policy extends ModuleAbstract{
    public function __construct(\Interqualitas $main) {
        parent::__construct($main);
        $this->modulePath = 'api/policy';
    }
}
