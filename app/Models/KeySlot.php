<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeySlot extends Model {

    protected $table = 'key_slots';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['provinceid', 'no', 'active'];
}
