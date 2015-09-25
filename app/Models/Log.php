<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

    protected $table = 'logs';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['employeeid', 'operation', 'date', 'model', 'detail'];
}
