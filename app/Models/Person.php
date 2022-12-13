<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender', 'parent_id', 'mother_id', 'father_id'];

    public function couples()
    {
        return $this->hasMany(Couple::class, 'husband_id');
    }

    public function husband()
    {
        return $this->hasMany(Couple::class, 'wife_id');
    }

    public function wifes()
    {
        return $this->hasMany(Couple::class, 'husband_id');
    }

    public function childs()
    {
        return $this->hasMany(Person::class, 'parent_id');
    }
}
