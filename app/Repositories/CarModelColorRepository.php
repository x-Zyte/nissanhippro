<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarModelColor;

class CarModelColorRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarModelColor;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'carmodelid','colorid','price');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'carmodelid','showInMsg'=>false,'label'=>'แบบรถ'),
            array('field'=>'colorid','showInMsg'=>true,'label'=>'แบบรถนี้ รหัสสี'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
