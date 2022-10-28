<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundValue extends Model
{
    protected $table = 'fund_value';

    public $timestamps = false;

    protected $guarded = [];

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'fund_id', 'id');
    }
}
