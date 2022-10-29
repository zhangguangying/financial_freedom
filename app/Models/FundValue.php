<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FundValue extends Model
{
    protected $table = 'fund_value';

    public $timestamps = false;

    protected $guarded = [];

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'fund_id', 'id');
    }

    /**
     * 获取基金最新净值
     *
     * @return array
     */
    public static function getFundLatelyValue()
    {
        $valueIds = self::query()
            ->groupBy('fund_id')
            ->get(DB::raw("max(id) as id"))
            ->pluck('id')
            ->toArray();
        return self::query()
            ->whereIn('id', $valueIds)
            ->pluck('net_worth', 'fund_id')
            ->toArray();
    }
}
