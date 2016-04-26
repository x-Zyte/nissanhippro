<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\Car;

class CarRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Car;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'provinceid', 'carmodelid', 'carsubmodelid', 'no', 'dodate', 'receiveddate',
            'dealername','engineno', 'chassisno', 'keyno', 'colorid', 'objective', 'receivetype','parklocation',
            'issold', 'isregistered', 'isdelivered');
        $this->uniqueKeySingles = array(array('field'=>'engineno','label'=>'เลขเครื่องยนต์'),
            array('field'=>'chassisno','label'=>'เลขตัวถัง'));
        $this->uniqueKeyMultiples = array();
        $this->hasBranch = false;
        $this->hasProvince = true;
    }
}
