<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundOwner extends Model
{
    protected $table = 'fund_owner';

    public $timestamps = false;

    protected $guarded = [];

    public function fund()
    {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }
}
