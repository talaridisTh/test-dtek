<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'image',
        'description',
        'parent_id'
    ];

    public static function getCategories($searchValue,$orderBy,$direction,$length)
    {
        $categories = Category::where('categories.name', 'like', '%'.$searchValue.'%')
        ->orWhere('categories.description', 'like', '%'.$searchValue.'%')
        ->leftJoin('categories as parent', 'categories.parent_id', '=', 'parent.category_id')
        ->select('categories.name', 'categories.category_id','parent.name AS parent_name')
        ->orderBy($orderBy, $direction)
        ->paginate($length);

        return $categories;
    }

    public static function getAllCategories()
    {
        $categories = Category::all();

        return $categories;
    }

    public function parent()
    {
        return $this->belongsTo('App\Category','parent_id','category_id');
    }
}
