<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Customer;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'title', 'firstname', 'lastname','phone1','phone2','occupationid', 'address', 'districtid', 'amphurid', 'addprovinceid',
            'zipcode', 'email', 'provinceid');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'firstname','showInMsg'=>true,'label'=>'ชื่อจริง'),
            array('field'=>'lastname','showInMsg'=>true,'label'=>'นามสกุล'),
            array('field'=>'provinceid','showInMsg'=>false,'label'=>''));
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
