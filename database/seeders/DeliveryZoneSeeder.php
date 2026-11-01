<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\DeliveryZone;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    public function run()
    {
        $bangladesh = Country::where('type', 1)->where('name', 'Bangladesh')->first();
        if (!$bangladesh) {
            return;
        }

        $dhakaDivision = Country::where('type', 2)->where('parent_id', $bangladesh->id)->where('name', 'Dhaka')->first();

        // Dhaka District holds both metro thanas and 5 rural upazilas; flag the metro thanas
        if ($dhakaDivision) {
            $dhakaDistrict = Country::where('type', 3)->where('parent_id', $dhakaDivision->id)->where('name', 'like', 'Dhaka%')->first();
            if ($dhakaDistrict) {
                Country::where('type', 4)->where('parent_id', $dhakaDistrict->id)
                    ->whereNotIn('name', ['Dhamrai', 'Dohar', 'Keraniganj', 'Nawabganj', 'Savar'])
                    ->update(['is_city' => 1]);
            }
        }

        if (DeliveryZone::count() > 0) {
            return;
        }

        $inside = general()->inside_dhaka_shipping_charge ?: 70;
        $outside = general()->outside_dhaka_shipping_charge ?: 130;

        $drag = 0;
        if ($dhakaDivision) {
            DeliveryZone::create(['name' => 'Dhaka City', 'bn_name' => 'ঢাকা সিটি', 'division_id' => $dhakaDivision->id, 'scope' => 'city', 'shipping_charge' => $inside, 'drag' => $drag++]);
            DeliveryZone::create(['name' => 'Outer Dhaka', 'bn_name' => 'আউটার ঢাকা', 'division_id' => $dhakaDivision->id, 'scope' => 'outer', 'shipping_charge' => $outside, 'drag' => $drag++]);
        }

        $divisions = Country::where('type', 2)->where('parent_id', $bangladesh->id)
            ->where('id', '<>', $dhakaDivision ? $dhakaDivision->id : 0)->orderBy('name')->get();

        foreach ($divisions as $division) {
            DeliveryZone::create([
                'name' => $division->name,
                'bn_name' => trim(preg_replace('/[\x{200B}-\x{200F}\x{FEFF}]/u', '', $division->bn_name)),
                'division_id' => $division->id,
                'scope' => 'all',
                'shipping_charge' => $outside,
                'drag' => $drag++,
            ]);
        }
    }
}
