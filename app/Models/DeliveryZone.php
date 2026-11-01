<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    /********
     * scope ==all   : every district/upazila of the division
     * scope ==city  : only Dhaka metro thanas (countries.is_city = 1)
     * scope ==outer : the division without the metro thanas
     ****/

    protected $fillable = ['name', 'bn_name', 'division_id', 'scope', 'shipping_charge', 'drag', 'status'];

    public function division()
    {
        return $this->belongsTo(Country::class, 'division_id');
    }

    public function districtCharges()
    {
        return $this->hasMany(DeliveryZoneCharge::class);
    }

    // District override when set, otherwise the zone default
    public function chargeFor($districtId = null)
    {
        if ($districtId) {
            $override = $this->districtCharges->firstWhere('district_id', $districtId);
            if ($override) {
                return (float)$override->shipping_charge;
            }
        }
        return (float)$this->shipping_charge;
    }

    public function chargeRange()
    {
        $charges = $this->districtCharges->pluck('shipping_charge')->push($this->shipping_charge)->map(function ($c) { return (float)$c; });
        return [$charges->min(), $charges->max()];
    }

    public function districts()
    {
        $query = Country::where('type', 3)->where('parent_id', $this->division_id);

        if ($this->scope == 'city') {
            $query->whereHas('subDatas', function ($q) {
                $q->where('type', 4)->where('is_city', 1);
            });
        } elseif ($this->scope == 'outer') {
            $query->whereHas('subDatas', function ($q) {
                $q->where('type', 4)->where('is_city', 0);
            });
        }

        return $query->orderBy('name')->get(['id', 'name', 'bn_name']);
    }

    public function upazilas($districtId)
    {
        $query = Country::where('type', 4)->where('parent_id', $districtId);

        if ($this->scope == 'city') {
            $query->where('is_city', 1);
        } elseif ($this->scope == 'outer') {
            $query->where('is_city', 0);
        }

        return $query->orderBy('name')->get(['id', 'name', 'bn_name']);
    }

    public function hasDistrict($districtId)
    {
        return $this->districts()->contains('id', $districtId);
    }

    public function hasUpazila($districtId, $upazilaId)
    {
        return $this->upazilas($districtId)->contains('id', $upazilaId);
    }
}
