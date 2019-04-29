<?php

namespace Lvqingan\Region\Tests\MockModels;

use Illuminate\Database\Eloquent\Model;
use Lvqingan\Region\HasRegion;

class User extends Model
{
    use HasRegion;

    protected $table = 'contacts';

    protected $guarded = [];

    protected $regionFieldName = 'shengshiqu';

    public $timestamps = false;
}
