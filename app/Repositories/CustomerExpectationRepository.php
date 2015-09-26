<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CustomerExpectation;

class CustomerExpectationRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CustomerExpectation;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'customerid','employeeid', 'date','carmodelid1','carmodelid2','carmodelid3',
            'colorid1','colorid2','colorid3','buyingtrends','newcarthingsrequired','otherconsideration','oldcarspecifications',
            'budgetpermonth','conditionproposed','conditionfinancedown','conditionfinanceinterest','conditionfinanceperiod',
            'nextappointmentdate','remarks');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
