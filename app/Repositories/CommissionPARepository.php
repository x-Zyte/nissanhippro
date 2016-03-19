<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CommissionPA;

class CommissionPARepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CommissionPA;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'finacecompanyid', 'effectivefrom', 'effectiveto', 'finaceminimumprofit', 'amount');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
