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
        $this->crudFields = array('oper', 'id', 'carpaymentid', 'invoiceno', 'date', 'additionalopenbill');

        $this->uniqueKeySingles = array(array('field' => 'carpaymentid', 'label' => 'รายละเอียดเพื่อการบันทึกบัญชีของใบชำระเงิน'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
