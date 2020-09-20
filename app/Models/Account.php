<?php

namespace App\Models;

use App\Attributes\AccountAttributes;
use App\Models\Traits\NestingTrait;
use App\Relationships\AccountRelationships;

/**
 * @method static where(array $array)
 */
class Account extends BaseModel
{

    use AccountAttributes, AccountRelationships, NestingTrait;
    protected $guarded = [];
    protected $appends = [
        'locale_name',
        'current_amount',
        'label',
        'is_expanded',
    ];
    protected $casts = [
        'is_gateway' => 'boolean',
    ];
    public function getSerialArrayAttribute($value)
    {
        return str_split($value);
    }
    public function updateSerial()
    {

        if ($this->parent != null) {
            $parentSerial = $this->parent->serial_array;
            $serialArrayIndex = count($this->_getParentsList());
            $parentChildrenCount = $this->parent->children()->count();
            $parentSerial[$serialArrayIndex] = $parentChildrenCount;
            $serial = implode('', $parentSerial);
            $this->forceFill([
                'serial' => $serial,
            ]);
        } else {
            $count = Account::where('parent_id', 0)->count();
            $update = $this->forceFill([
                'serial' => $count . '0000000',
            ]);
        }
    }
}
