<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;


use App\Models\Customer;

class CustomerExpectRepository extends CustomerRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->Database = Customer::has('customerExpectations','>=',0);
    }
}
