<?php

namespace Interqualitas;

use Interqualitas;

/**
 * Base class that all modules inherit
 * @package Interqualitas
 */
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

    /**
     * Returns the requested entity of the inheriting class using the given id
     *
     * @param string $id The id of the entity
     * @param array  $params Any params that may need be sent
     * @return mixed
     */
    public function fetch($id = '', $params = []) {
        return $this->main->makeCall($this->modulePath, $id, $params);
    }

    /**
     * Creates a new entity of the inheriting class.
     *
     * @param array|object $data The information needed to create the new entity
     * @return mixed The new entity or the messages as to why it was not created
     */
    public function create($data) {
        return $this->main->makeCall($this->modulePath, '', $data, Interqualitas::METHOD_POST);
    }

    /**
     * Updates the entity with the given id of the inheriting class
     *
     * @param string       $id The id of the entity to edit
     * @param array|object $data The data to change
     * @return mixed The modified object or messages as to why the entity was not modified
     */
    public function update($id, $data) {
        return $this->main->makeCall($this->modulePath, $id, $data, Interqualitas::METHOD_PATCH);
    }

    /**
     * Deletes the entity from the inheriting class that matches the id
     *
     * @param string $id
     * @param array  $params
     * @return mixed
     */
    public function delete($id, $params = []) {
        return $this->main->makeCall($this->modulePath, $id, $params, Interqualitas::METHOD_DELETE);
    }

    /**
     * Instantiates a new Module
     *
     * @param Interqualitas $main
     */
    public function __construct(\Interqualitas $main) {
        $this->main = $main;
    }
}
