<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Translatable;

    protected $with = ['translations'];


    protected $translatedAttributes = ['name'];


    protected $fillable = ['is_active','photo'];


    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function scopeActive($query){
        return $query -> where('is_active',1);
    }
    public function getActive()
    {
        return $this->is_active == 0 ? 'غير مفعل' : 'مفعل';
    }

    public function getPhotoAttributes ($val){

        return($val  !== null) ? asset('assets/images/brands/' .$val) : "";
    }


}
