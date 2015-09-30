<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Branch;

class BranchRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Branch;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'name','taxinvoicename','taxpayerno', 'address', 'districtid', 'amphurid', 'provinceid', 'zipcode','isheadquarter','keyslot');
        $this->uniqueKeySingles = array(array('field'=>'name','label'=>'ชื่อสาขา'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
