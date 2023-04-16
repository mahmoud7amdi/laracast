<?php

namespace App\Models;

//use Egulias\EmailValidator\Parser\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @method static latest()
 */
class Post extends Model
{
    use HasFactory;
    protected $with = ['category','author'] ;

    public function scopeFilter($query, array $filters)
    {
        //search

        $query->when($filters['search'] ?? false, fn($query, $search)=>
            $query->where(fn($query)=>
                $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%')
            )
        );
        //filter
        $query->when($filters['category'] ?? false, fn($query, $category)=>
            $query->whereHas('category',fn ($query)=>
                $query->where('slug',$category)
        )
        );
        $query->when($filters['author'] ?? false, fn($query, $author)=>
            $query->whereHas('author',fn ($query)=>
               $query->where('username',$author)
              )
        );

    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class,'user_id');
    }

//    public function getRouteKeyName()
//    {
//        return 'slug' ;
//    }


}
