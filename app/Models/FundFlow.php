<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundFlow extends Model
{
    protected $table = 'fund_flow';

    public $timestamps = false;

    public function fund()
    {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }
}
