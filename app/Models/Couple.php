<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;

    protected $fillable = ['wife_id', 'husband_id', 'date_wedding'];

    public function person()
    {
        return $this->hasMany(Person::class);
    }

    public function wife()
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }

    public function husband()
    {
        return $this->belongsTo(Person::class, 'husband_id');
    }
}
