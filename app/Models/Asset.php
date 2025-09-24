<?php

namespace App\Models;

use App\Enums\AssetStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
   use HasFactory;

   protected $fillable = [
    'category_id',
    'location_id',
    'manufacturer_id',
    'assigned_to_user_id',
    'asset_tag',
    'name',
    'serial_number',
    'model_name',
    'purchase_date',
    'purchase_price',
    'status',
    'notes',
   ];

   protected $casts = [
    'purchase_date' => 'date',
    'purchase_price'=> 'decimal:2',
    'status' => AssetStatusEnum::class,
   ];

   public function category(): BelongsTo
   {
    return $this->belongsTo(Category::class);
   }

   public function location(): BelongsTo
   {
    return $this->belongsTo(Location::class);
   }

   public function manufacturer(): BelongsTo
   {
    return $this->belongsTo(Manufacturer::class);
   }

   public function assignedTo(): BelongsTo
   {
    return $this->belongsTo(User::class, 'assigned_to_user_id');
   }

}
