<?php

namespace Lvqingan\Test\MockModels;

use Illuminate\Database\Eloquent\Model;
use Lvqingan\Region\HasRegion;

class Contact extends Model
{
    use HasRegion;

    protected $table = 'contacts';

    protected $guarded = [];

    public $timestamps = false;
}
