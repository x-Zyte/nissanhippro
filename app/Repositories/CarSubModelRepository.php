<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarSubModel;

class CarSubModelRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarSubModel;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'carmodelid','code', 'name','taxinvoicename', 'detail');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'carmodelid','showInMsg'=>false,'label'=>'แบบรถ'),
            array('field'=>'code','showInMsg'=>true,'label'=>'แบบรถนี้ รหัสรุ่น'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
