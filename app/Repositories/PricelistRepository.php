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
            'accessoriesprice','sellingpricewithaccessories','margin','execusiveinternal','execusivecampaing', 'execusivetotalcampaing',
            'execusivetotalmargincampaing','internal','campaing','totalcampaing','totalmargincampaing','promotion');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = false;
    }
}
