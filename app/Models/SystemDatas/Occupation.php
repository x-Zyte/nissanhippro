<?php namespace App\Models\SystemDatas;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model {

    protected $table = 'occupations';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name'];
}
