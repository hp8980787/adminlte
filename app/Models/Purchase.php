<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{

    use HasFactory;

    protected $table = 'purchase';

    protected $fillable = [
        'user_id', 'supplier_id', 'remark', 'deadline_at', 'complete_at', 'title'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    const PURCHASE_PENDING_REVIEW='待审核';
    const PURCHASE_COMPLETED_REVIEW='审核通过,正在采购';
    const PURCHASE_COMPLETED ='采购完成';

    const PURCHASE_STATUS_GROUP = [
        0 => self::PURCHASE_PENDING_REVIEW,
        1 => self::PURCHASE_COMPLETED_REVIEW,
        2=> self::PURCHASE_COMPLETED,
    ];

}
