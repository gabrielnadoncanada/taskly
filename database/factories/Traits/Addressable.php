<?php

namespace Database\Factories\Traits;

use App\Models\Address;

trait Addressable
{
    public function withAddress(Address $address)
    {
        return $this->afterCreating(function ($model) use ($address) {
            $model->address()->create([
                Address::ADDRESSABLE_ID => $model->id,
                Address::ADDRESSABLE_TYPE => get_class($model),
                Address::STREET => $address->{Address::STREET},
                Address::CITY => $address->{Address::CITY},
                Address::STATE => $address->{Address::STATE},
                Address::COUNTRY => $address->{Address::COUNTRY},
                Address::POSTAL_CODE => $address->{Address::POSTAL_CODE},
            ]);
        });
    }
}
