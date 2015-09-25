<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Giveaway;

class GiveawayRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Giveaway;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'name','price');
        $this->uniqueKeySingles = array(array('field'=>'name','label'=>'ชื่อของแถม'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
