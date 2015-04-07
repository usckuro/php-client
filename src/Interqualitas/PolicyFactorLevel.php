<?php

namespace Interqualitas;

use Interqualitas\ModuleAbstract;

/**
 * The API Calls to interact with policy factor levels
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class PolicyFactorLevel extends ModuleAbstract{
    public function __construct(\Interqualitas $main) {
        parent::__construct($main);
        $this->modulePath = 'api/policyfactorlevel';
    }
}
