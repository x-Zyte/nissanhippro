<?php namespace App\Models\SystemDatas;

use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model {

    protected $table = 'zipcodes';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['districtcode','provinceid','amphurid','districtid','code'];
}
