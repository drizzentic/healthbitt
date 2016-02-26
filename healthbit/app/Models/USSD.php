<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class USSD extends Model
{
    protected $table="ussd_registration";

    protected $fillable=['fullname','ward','national_id','phonenumber'];
}
