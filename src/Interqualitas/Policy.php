<?php

namespace Interqualitas;

use Interqualitas\ModuleAbstract;

/**
 * The API Calls to interact with policies
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class Policy extends ModuleAbstract{
    public function __construct(\Interqualitas $main) {
        parent::__construct($main);
        $this->modulePath = 'api/policy';
    }


    public function getDocument($id) {
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-type: application/pdf" . PHP_EOL .
                    "Accept-Encoding: gzip, deflate" .PHP_EOL .
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"
            )
        );
        return $this->main->rpcCall('api/getdocument', ['ID'=>$id], $opts);
    }

}
