<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CommissionFinaceCar;

class CommissionFinaceCarRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CommissionFinaceCar;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'commissionfinaceid','carmodelid','carsubmodelid');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'commissionfinaceid','showInMsg'=>false,'label'=>'คอมไฟแนนซ์'),
            array('field'=>'carmodelid','showInMsg'=>false,'label'=>'แบบรถ'),
            array('field'=>'carsubmodelid','showInMsg'=>true,'label'=>'คอมไฟแนนซ์นี้ แบบรถนี้ รุ่นรถ'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
