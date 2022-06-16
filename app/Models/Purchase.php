<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{

    use HasFactory;

    protected $table = 'purchase';

    protected $fillable = [
        'user_id', 'supplier_id', 'remark', 'deadline_at', 'complete_at'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
