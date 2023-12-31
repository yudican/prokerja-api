<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    //use Uuid;
    use HasFactory;

    //public $incrementing = false;

    protected $fillable = ['biodata_file', 'cv_file', 'job_vacancy_id', 'surat_lamaran_file', 'user_id'];

    protected $dates = [];

    /**
     * Get the jobVacancy that owns the JobApplyTest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }

    /**
     * Get the user that owns the JobApplyTest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
