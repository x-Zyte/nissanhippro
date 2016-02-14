<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CommissionFinaceCom;

class CommissionFinaceComRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CommissionFinaceCom;
        $this->orderBy = array(array('interestcalculation', 'asc'));
        $this->crudFields = array('oper', 'id', 'commissionfinaceid','interestcalculation','com');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'commissionfinaceid','showInMsg'=>false,'label'=>'คอมไฟแนนซ์'),
            array('field'=>'interestcalculation','showInMsg'=>true,'label'=>'คอมไฟแนนซ์นี้ ดอกเบี้ยคำนวณ'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
