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
        $this->crudFields = array('oper', 'id', 'carpaymentid');

        $this->uniqueKeySingles = array(array('field' => 'carpaymentid', 'label' => 'รายละเอียดบันทึกบัญชีของใบชำระเงิน'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
