<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Pricelist;

class PricelistRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Pricelist;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'carmodelid', 'carsubmodelid', 'effectivefrom','effectiveto','sellingprice',
            'accessoriesprice', 'sellingpricewithaccessories', 'margin', 'ws50', 'dms', 'wholesale', 'execusiveinternal', 'execusivecampaing',
            'execusivetotalmargincampaing','internal','campaing','totalmargincampaing','promotion');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
