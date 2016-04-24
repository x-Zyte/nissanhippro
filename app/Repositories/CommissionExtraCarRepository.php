<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CommissionExtraCar;

class CommissionExtraCarRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CommissionExtraCar;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'commissionextraid','carmodelid','carsubmodelid');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'commissionextraid','showInMsg'=>false,'label'=>'คอมเอกซ์ตร้า'),
            array('field'=>'carmodelid','showInMsg'=>false,'label'=>'แบบรถ'),
            array('field'=>'carsubmodelid','showInMsg'=>true,'label'=>'คอมเอกซ์ตร้านี้ แบบรถนี้ รุ่นรถ'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
