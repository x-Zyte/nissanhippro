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
        $this->orderBy = array(array('interestcalculationbeginning', 'asc'),array('interestcalculationending', 'asc'));
        $this->crudFields = array('oper', 'id', 'commissionfinaceid','interestcalculationbeginning','interestcalculationending','com');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'commissionfinaceid','showInMsg'=>false,'label'=>'คอมไฟแนนซ์'),
            array('field'=>'interestcalculationbeginning','showInMsg'=>true,'label'=>'คอมไฟแนนซ์นี้ ดอกเบี้ยคำนวณ beginning'),
            array('field'=>'interestcalculationending','showInMsg'=>true,'label'=>' ดอกเบี้ยคำนวณ ending'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
