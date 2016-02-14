<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Giveaway;
use App\Models\RedLabel;

class RedLabelRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new RedLabel;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'provinceid','no', 'carid','deposit');
        $this->uniqueKeySingles = array(array('field'=>'no','label'=>'เลขทะเบียน'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
