<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\AccountingDetail;

class AccountingDetailRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new AccountingDetail;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'carpaymentid', 'invoiceno', 'date', 'additionalopenbill',
            'insurancefeereceiptcondition', 'compulsorymotorinsurancefeereceiptcondition',
            'payinadvanceamountreimbursementdate', 'payinadvanceamountreimbursementdocno', 'insurancebilldifferent',
            'note1insurancefeereceiptcondition', 'note1compulsorymotorinsurancefeereceiptcondition',
            'insurancefeepayment', 'insurancefeepaidseparatelydate',
            'insurancepremiumnet', 'insurancepremiumcom', 'insurancefeepaidseparatelytotal',
            'compulsorymotorinsurancefeepayment', 'compulsorymotorinsurancefeepaidseparatelydate',
            'compulsorymotorinsurancepremiumnet', 'compulsorymotorinsurancepremiumcom', 'compulsorymotorinsurancefeepaidseparatelytotal',
            'cashpledgeredlabelreceiptbookno', 'cashpledgeredlabelreceiptno', 'cashpledgeredlabelreceiptdate',
            'cashpledgereceiptbookno', 'cashpledgereceiptno', 'cashpledgereceiptdate',
            'systemcalincasefinacecomfinamount', 'systemcalincasefinacecomfinvat', 'systemcalincasefinacecomfinamountwithvat', 'systemcalincasefinacecomfinwhtax', 'systemcalincasefinacecomfintotal',
            'incasefinacecomfinamount', 'incasefinacecomfinvat', 'incasefinacecomfinamountwithvat', 'incasefinacecomfinwhtax', 'incasefinacecomfintotal',
            'receivedcashfromfinacenet', 'receivedcashfromfinacenetshort', 'receivedcashfromfinacenetover', 'oldcarcomamount', 'oldcarcomdate', 'adj',
            'totalaccount1', 'totalaccount1short', 'totalaccount1over', 'totalaccount2', 'totalaccount2short', 'totalaccount2over',
            'totalaccounts', 'totalaccountsshort', 'totalaccountsover');

        $this->uniqueKeySingles = array(array('field' => 'carpaymentid', 'label' => 'รายละเอียดเพื่อการบันทึกบัญชีของใบชำระเงิน'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
