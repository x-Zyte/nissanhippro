<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarPayment;

class CarPaymentRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarPayment;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id','carpreemptionid', 'date', 'carid', 'amountperinstallment', 'insurancepremium',
            'overrideopenbill','firstinstallmentpay','installmentsinadvance',
            'accessoriesfeeactuallypaid','accessoriesfeeincludeinyodjud',
            'insurancecompanyid', 'capitalinsurance', 'compulsorymotorinsurancecompanyid',
            'buyerpay', 'overdue', 'overdueinterest', 'totaloverdue', 'paybytype', 'paybyotherdetails',
            'overdueinstallments', 'overdueinstallmentdate1', 'overdueinstallmentamount1',
            'overdueinstallmentdate2', 'overdueinstallmentamount2','overdueinstallmentdate3', 'overdueinstallmentamount3',
            'overdueinstallmentdate4', 'overdueinstallmentamount4','overdueinstallmentdate5', 'overdueinstallmentamount5',
            'overdueinstallmentdate6', 'overdueinstallmentamount6', 'oldcarbuyername', 'oldcarpayamount', 'oldcarpaytype',
            'oldcarpaydate', 'payeeemployeeid','deliverycarbookno','deliverycarno','deliverycardate','deliverycarfilepath','isdraft');

        $this->uniqueKeySingles = array(array('field'=>'carpreemptionid','label'=>'รายการชำระเงินของใบจอง'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
