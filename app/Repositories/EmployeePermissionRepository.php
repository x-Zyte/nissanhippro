<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\EmployeePermission;

class EmployeePermissionRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new EmployeePermission;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'employeeid', 'menu');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'employeeid','showInMsg'=>false,'label'=>'พนักงาน'),
            array('field'=>'menu','showInMsg'=>true,'label'=>'พนักงานคนนี้  สิทธิ์การเข้าถึงเมนู'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
