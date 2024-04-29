<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    public $timestamps = false;
    protected $fillable = [
        'dni' ,
        'id_reg' ,
        'id_com' ,
        'email' ,
        'name' ,
        'last_name' ,
        'address' ,
        'date_reg',
        'status' 
    ];
}
