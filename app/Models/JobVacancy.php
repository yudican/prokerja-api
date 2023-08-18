<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    //use Uuid;
    use HasFactory;

    //public $incrementing = false;

    protected $fillable = ['job_company_name', 'job_description', 'job_image', 'job_location', 'job_name'];

    protected $dates = [];

    protected $appends = ['has_test'];
    protected $with = ['jobTest'];

    /**
     * Get the jobTest associated with the JobVacancy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function jobTest()
    {
        return $this->hasOne(JobVacancyTest::class, 'job_vacancy_id');
    }

    function getHasTestAttribute()
    {
        if ($this->jobTest) {
            $vacancy = JobApplyTest::where('job_vacancy_id', $this->id)->where('job_vacancy_test_id', $this->jobTest?->id)->where('user_id', auth()->user()->id)->first();
            if ($vacancy) {
                return true;
            }
        }

        return false;
    }
}
