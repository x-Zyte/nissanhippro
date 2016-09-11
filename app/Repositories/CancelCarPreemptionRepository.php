<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CancelCarPreemption;

class CancelCarPreemptionRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CancelCarPreemption;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id','carpreemptionid','toemployeeid', 'cancelreasontype',
            'cancelreasondetails', 'remark', 'refundamount', 'refunddate', 'refunddocno',
            'confiscateamount', 'confiscatedate', 'confiscatedocno',
            'salesmanemployeedate', 'accountemployeeid', 'accountemployeedate',
            'financeemployeeid', 'financeemployeedate', 'approversemployeeid', 'approversemployeedate');

        $this->uniqueKeySingles = array(array('field'=>'carpreemptionid','label'=>'รายการยกเลิกของใบจอง'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
