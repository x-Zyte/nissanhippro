<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CommissionFinaceInterest;

class CommissionFinaceInterestRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CommissionFinaceInterest;
        $this->orderBy = array(array('downfrom', 'asc'),array('downto', 'asc'));
        $this->crudFields = array('oper', 'id', 'commissionfinaceid','downfrom', 'downto','installment24', 'installment36'
            , 'installment48', 'installment60', 'installment72', 'installment84');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'commissionfinaceid','showInMsg'=>false,'label'=>'คอมไฟแนนซ์'),
            array('field'=>'downfrom','showInMsg'=>true,'label'=>'คอมไฟแนนซ์นี้ ดาวน์'),
            array('field'=>'downto','showInMsg'=>true,'label'=>'ถึง'));
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
