<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\InsuranceCompany;

class InsuranceCompanyRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new InsuranceCompany;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'name', 'detail');
        $this->uniqueKeySingles = array(array('field'=>'name','label'=>'ชื่อบริษัทประกัน'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
