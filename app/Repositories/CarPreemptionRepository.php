<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarPreemption;

class CarPreemptionRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarPreemption;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'bookno', 'no', 'date', 'bookingcustomerid',
            'carobjectivetype', 'carmodelid', 'carsubmodelid','colorid',
            'pricelistid', 'discount', 'subdown', 'accessories',

            'oldcarbrandid', 'oldcarmodelid', 'oldcargear', 'oldcarcolor', 'oldcarenginesize', 'oldcarlicenseplate',
            'oldcaryear', 'oldcarprice', 'oldcarbuyername', 'oldcarother',

            'cashpledge', 'purchasetype', 'finacecompanyid', 'interest', 'down', 'installments', 'cashpledgeredlabel',
            'registerprovinceid','registrationtype', 'registrationfee', 'insurancefee', 'compulsorymotorinsurancefee', 'accessoriesfee', 'otherfee',
            'datewantgetcar','giveawayadditionalcharges','financingfee', 'transferfee', 'transferoperationfee',

            'buyercustomerid', 'salesmanemployeeid', 'salesmanteamid', 'salesmanageremployeeid', 'approversemployeeid', 'approvaldate',

            'place', 'showroom', 'booth', 'leaflet', 'businesscard', 'invitationcard', 'phone', 'signshowroom', 'spotradiowalkin',
            'recommendedby', 'recommendedbyname', 'recommendedbytype', 'customertype', 'remark', 'status');

        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'bookno','showInMsg'=>true,'label'=>'เล่มที่'),
            array('field'=>'no','showInMsg'=>true,'label'=>'เลขที่'));
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
