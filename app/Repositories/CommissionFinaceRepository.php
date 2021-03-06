<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CommissionFinace;

class CommissionFinaceRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CommissionFinace;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'finacecompanyid','interestratetypeid', 'name', //'useforcustomertype',
            'effectivefrom', 'effectiveto', 'finaceminimumprofit', 'years');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
