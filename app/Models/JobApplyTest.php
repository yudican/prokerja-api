<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplyTest extends Model
{
    //use Uuid;
    use HasFactory;

    //public $incrementing = false;

    protected $fillable = ['job_vacancy_id', 'job_vacancy_test_id', 'test_file', 'user_id'];

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
     * Get the jobVacancy that owns the JobApplyTest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobVacancyTest()
    {
        return $this->belongsTo(JobVacancyTest::class, 'job_vacancy_test_id');
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
