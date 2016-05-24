<?php namespace App\Models\SystemDatas;

use Illuminate\Database\Eloquent\Model;

class Province extends Model {

    protected $table = 'provinces';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['code','name','geoid'];

    public function branchs()
    {
        return $this->hasMany('App\Models\Branch', 'provinceid', 'id');
    }

    public function carModelRegisters()
    {
        return $this->hasMany('App\Models\CarModelRegister', 'provinceid', 'id');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'provinceid', 'id');
    }

    public function addCustomers()
    {
        return $this->hasMany('App\Models\Customer', 'addprovinceid', 'id');
    }
}
