<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\InterestRateType;

class InterestRateTypeRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new InterestRateType;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'finacecompanyid', 'name', 'detail');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'finacecompanyid','showInMsg'=>false,'label'=>'ไฟแนนซ์'),
            array('field'=>'name','showInMsg'=>true,'label'=>'ไฟแนนซ์นี้ ชื่อประเภท'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
