<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'phone_no1',
        'phone_no2',
        'zip',
        'start_validity',
        'end_validity',
        'status',
        'created_at',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
