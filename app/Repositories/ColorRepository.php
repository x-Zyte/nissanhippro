<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Color;

class ColorRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Color;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'code','name');
        $this->uniqueKeySingles = array(array('field'=>'code','label'=>'รหัสสี'),array('field'=>'name','label'=>'ชื่อสี'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
