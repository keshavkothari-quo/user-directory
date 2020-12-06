<?php


namespace App;


class CommonRepoFactrory implements CommonRepo
{
    private $modelName;
    public function __construct($modelName){
        $this->modelName = $modelName;
    }

    public function getModelObject($modelName){
        switch ($modelName){
            case "User":
                $object = new User();
                break;
            case "State":
                return new State();
                break;
        }
        return $object;
    }

    public function findById($id)
    {
        // TODO: Implement getAllData() method.
    }

    public function getAllData()
    {
        // TODO: Implement getAllData() method.
        $modelObject = $this->getModelObject($this->modelName);
        return $modelObject->all();
    }
}
