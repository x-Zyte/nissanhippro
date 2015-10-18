<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Employee;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'title', 'firstname', 'lastname','code','workingstartdate','workingenddate',
            'username', 'email','loginstartdate','loginenddate', 'phone', 'isadmin', 'branchid', 'departmentid',
            'teamid', 'candeletedata', 'remarks', 'active');
        $this->uniqueKeySingles = array(array('field'=>'username','label'=>'ชื่อเข้าใช้ระบบ'),
            array('field'=>'email','label'=>'อีเมล์'));
        $this->uniqueKeyMultiples = array(array('field'=>'firstname','showInMsg'=>true,'label'=>'ชื่อจริง'),
            array('field'=>'lastname','showInMsg'=>true,'label'=>'นามสกุล'));
        $this->hasBranch = true;
        $this->hasProvince = false;
    }
}
