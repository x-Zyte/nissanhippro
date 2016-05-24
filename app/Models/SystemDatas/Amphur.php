<?php namespace App\Models\SystemDatas;

use Illuminate\Database\Eloquent\Model;

class Amphur extends Model {

    protected $table = 'amphurs';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['code','name','geoid','provinceid'];

    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'amphurid', 'id');
    }

    public function branchs()
    {
        return $this->hasMany('App\Models\Branch', 'amphurid', 'id');
    }

    public function taxBranchs()
    {
        return $this->hasMany('App\Models\Branch', 'taxamphurid', 'id');
    }
}
