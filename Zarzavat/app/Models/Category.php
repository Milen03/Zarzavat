<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    protected $fillable = ['name'];
     
    use HasFactory;
    
    //Много протукти
    public function products(){
        return $this->hasMany(Product::class);
    }
}
