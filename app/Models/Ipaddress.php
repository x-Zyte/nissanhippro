<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ipaddress extends Model {

    protected $table = 'ipaddresses';

    public $timestamps = false;

    protected $guarded = [];

    protected $fillable = ['ip'];
}
