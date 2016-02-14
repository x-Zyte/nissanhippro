<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarType;

class CarTypeRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarType;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'name','actcharged', 'actpaid', 'actpaidincludevat', 'detail');
        $this->uniqueKeySingles = array(array('field'=>'name','label'=>'ชื่อประเภท'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
