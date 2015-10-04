<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarBrand;

class CarBrandRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarBrand;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'name', 'detail', 'ismain');
        $this->uniqueKeySingles = array(array('field'=>'name','label'=>'ชื่อยี่ห้อรถ'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
