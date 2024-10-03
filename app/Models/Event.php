<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService;

class Event extends Model
{
    use HasFactory;
    protected $casts=[
        'items' => 'array'
    ];
   
    protected $dates = ['date'];
    protected $guarded = [];
    protected $fillable = [
    'title', 'city', 'private', 'description', 'items', 'date', 'image', 'user_id'
];

 public function user() {
    return $this->belongsTo(User::class); 
}

public function users()
{
    return $this->belongsToMany(User::class, 'event_user');
}


 


}