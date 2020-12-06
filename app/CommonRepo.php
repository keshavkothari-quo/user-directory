<?php


namespace App;


interface CommonRepo
{
    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    public function getAllData();
}
