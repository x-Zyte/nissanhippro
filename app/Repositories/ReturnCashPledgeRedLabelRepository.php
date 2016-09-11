<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\ReturnCashPledgeRedLabel;

class ReturnCashPledgeRedLabelRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new ReturnCashPledgeRedLabel;
        $this->orderBy = array(array('returndate', 'asc'));
        $this->crudFields = array('oper', 'id', 'redlabelid', 'carpreemptionid', 'returncashpledgedate');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
        $this->parentHasProvince = true;
        $this->parentModel = 'redlabel';
        $this->whereNotNullFields = array('returndate');
    }
}
