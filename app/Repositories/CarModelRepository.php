<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarModel;

class CarModelRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarModel;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'cartypeid','carbrandid', 'name', 'detail');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'cartypeid','showInMsg'=>false,'label'=>'ประเภทรถ'),
            array('field'=>'carbrandid','showInMsg'=>false,'label'=>'ยี่ห้อรถ'),
            array('field'=>'name','showInMsg'=>true,'label'=>'ประเภทรถนี้ ยี่ห้อรถนี้ แบบ'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
