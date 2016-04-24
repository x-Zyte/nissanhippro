<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarModelRegister;

class CarModelRegisterRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarModelRegister;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'carmodelid','provinceid',
            'individualregistercost','implementingindividualregistercost',
            'companyregistercost','implementingcompanyregistercost',
            'governmentregistercost','implementinggovernmentregistercost');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
       /* $this->uniqueKeyMultiples = array(array('field'=>'carmodelid','showInMsg'=>false,'label'=>'แบบรถ'),
            array('field'=>'provinceid','showInMsg'=>true,'label'=>'แบบรถนี้ ค่าทะเบียนของจังหวัด'));*/
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
