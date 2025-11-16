<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['partner_id','code','description','type','value','min_amount','max_discount','quota','expires_at'];

    public function calculateDiscount($total)
    {
        if ($this->min_amount && $total < $this->min_amount) return 0;

        if ($this->type === 'percentage') {
            $discount = ($this->value / 100) * $total;
            return $this->max_discount ? min($discount, $this->max_discount) : $discount;
        }

        // fixed nominal
        return min($this->value, $total);
    }
}
