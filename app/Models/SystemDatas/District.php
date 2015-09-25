<?php namespace App\Models\SystemDatas;

use Illuminate\Database\Eloquent\Model;

class District extends Model {

    protected $table = 'districts';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['code','name','amphurid','provinceid','geoid'];
}
