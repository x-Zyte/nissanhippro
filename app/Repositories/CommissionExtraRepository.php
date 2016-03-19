<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CommissionExtra;

class CommissionExtraRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CommissionExtra;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'finacecompanyid', 'effectivefrom', 'effectiveto', 'amount');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
