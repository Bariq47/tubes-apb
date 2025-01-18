<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;
    // mass assignment
    //property untuk databas
    protected $fillable = [
        'image',
        'title',
        'content'
    ];


    #accesor pada model memungkinkan untuk mengubah nilai saat fill diakses
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url ('storage/posts/' . $image)
        );
    }

}
