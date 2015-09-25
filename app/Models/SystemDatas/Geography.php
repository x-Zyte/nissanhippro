<?php namespace App\Models\SystemDatas;

use Illuminate\Database\Eloquent\Model;

class Geography extends Model {

    protected $table = 'geographies';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name'];
}
