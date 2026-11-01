<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public static function findValid($code)
    {
        return self::where('code', $code)->where('status', 'active')->first();
    }

    // Returns null when the coupon can be used, otherwise the reason (in Bangla, shown to the customer)
    public function invalidReason($subtotal)
    {
        $today = Carbon::today();

        if ($this->status != 'active') {
            return 'এই কুপনটি সক্রিয় নেই।';
        }
        if ($this->date_from && $today->lt($this->date_from)) {
            return 'এই কুপনটি এখনো চালু হয়নি।';
        }
        if ($this->date_to && $today->gt($this->date_to)) {
            return 'এই কুপনের মেয়াদ শেষ হয়ে গেছে।';
        }
        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) {
            return 'এই কুপনের ব্যবহারের সীমা শেষ।';
        }
        if ($this->minimum_shopping > 0 && $subtotal < $this->minimum_shopping) {
            return 'এই কুপন ব্যবহার করতে কমপক্ষে ৳' . en2bnNumber(number_format($this->minimum_shopping)) . ' এর কেনাকাটা করতে হবে।';
        }
        return null;
    }

    public function discountFor($subtotal)
    {
        if ($this->invalidReason($subtotal)) {
            return 0;
        }

        if ($this->discount_type == 'flat') {
            $discount = $this->discount;
        } else {
            $discount = $subtotal * ($this->discount / 100);
            if ($this->max_discount > 0) {
                $discount = min($discount, $this->max_discount);
            }
        }

        return round(min($discount, $subtotal));
    }
}
