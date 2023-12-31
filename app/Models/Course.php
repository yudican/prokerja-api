<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //use Uuid;
    use HasFactory;

    //public $incrementing = false;

    protected $fillable = ['course_description', 'course_image', 'course_name', 'course_url', 'user_id'];

    protected $dates = [];

    protected $appends = ['course_owner'];

    public function getCourseOwnerAttribute()
    {
        $user = User::find($this->user_id);
        if ($user) {
            return $user->name;
        }

        return '-';
    }
}
