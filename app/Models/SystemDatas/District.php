<?php namespace App\Models\SystemDatas;

use Illuminate\Database\Eloquent\Model;

class District extends Model {

    protected $table = 'districts';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['code','name','amphurid','provinceid','geoid'];

    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'districtid', 'id');
    }

    public function branchs()
    {
        return $this->hasMany('App\Models\Branch', 'districtid', 'id');
    }

    public function taxBranchs()
    {
        return $this->hasMany('App\Models\Branch', 'taxdistrictid', 'id');
    }
}
