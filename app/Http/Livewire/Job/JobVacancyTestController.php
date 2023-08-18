<?php

namespace App\Http\Livewire\Job;

use App\Models\JobVacancyTest;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobVacancyTestController extends Component
{
    use WithFileUploads;
    public $job_vacancy_test_id;
    public $job_vacancy_id;
    public $test_description;
    public $test_image;
    public $test_name;
    public $test_image_path;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataJobVacancyTestById', 'getJobVacancyTestId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.job.job-vacancy-test')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();
        $test_image = $this->test_image_path->store('upload', 'public');
        $data = [
            'job_vacancy_id'  => $this->job_vacancy_id,
            'test_description'  => $this->test_description,
            'test_image'  => getImage($test_image),
            'test_name'  => $this->test_name
        ];

        JobVacancyTest::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'job_vacancy_id'  => $this->job_vacancy_id,
            'test_description'  => $this->test_description,
            'test_image'  => $this->test_image,
            'test_name'  => $this->test_name
        ];
        $row = JobVacancyTest::find($this->job_vacancy_test_id);


        if ($this->test_image_path) {
            $test_image = $this->test_image_path->store('upload', 'public');
            $data['test_image'] = getImage($test_image);
            if (Storage::exists('public/' . $this->test_image)) {
                Storage::delete('public/' . $this->test_image);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        JobVacancyTest::find($this->job_vacancy_test_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'job_vacancy_id'  => 'required',
            'test_description'  => 'required',
            'test_name'  => 'required'
        ];

        if (!$this->update_mode) {
            $rule['test_image_path'] = 'required';
        }

        return $this->validate($rule);
    }

    public function getDataJobVacancyTestById($job_vacancy_test_id)
    {
        $this->_reset();
        $row = JobVacancyTest::find($job_vacancy_test_id);
        $this->job_vacancy_test_id = $row->id;
        $this->job_vacancy_id = $row->job_vacancy_id;
        $this->test_description = $row->test_description;
        $this->test_image = $row->test_image;
        $this->test_name = $row->test_name;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getJobVacancyTestId($job_vacancy_test_id)
    {
        $row = JobVacancyTest::find($job_vacancy_test_id);
        $this->job_vacancy_test_id = $row->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->job_vacancy_test_id = null;
        $this->job_vacancy_id = null;
        $this->test_description = null;
        $this->test_image_path = null;
        $this->test_image = null;
        $this->test_name = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
