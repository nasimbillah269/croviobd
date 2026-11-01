<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZoneCharge extends Model
{
    protected $fillable = ['delivery_zone_id', 'district_id', 'shipping_charge'];
}
