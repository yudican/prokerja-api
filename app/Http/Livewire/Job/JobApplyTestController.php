<?php

namespace App\Http\Livewire\Job;

use App\Models\JobApplyTest;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobApplyTestController extends Component
{
    use WithFileUploads;
    public $job_apply_test_id;
    public $job_vacancy_id;
    public $job_vacancy_test_id;
    public $test_file;
    public $user_id;
    public $test_file_path;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataJobApplyTestById', 'getJobApplyTestId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.job.job-apply-test')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();
        $test_file = $this->test_file_path->store('upload', 'public');
        $data = [
            'job_vacancy_id'  => $this->job_vacancy_id,
            'job_vacancy_test_id'  => $this->job_vacancy_test_id,
            'test_file'  => $test_file,
            'user_id'  => $this->user_id
        ];

        JobApplyTest::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'job_vacancy_id'  => $this->job_vacancy_id,
            'job_vacancy_test_id'  => $this->job_vacancy_test_id,
            'test_file'  => $this->test_file,
            'user_id'  => $this->user_id
        ];
        $row = JobApplyTest::find($this->job_apply_test_id);


        if ($this->test_file_path) {
            $test_file = $this->test_file_path->store('upload', 'public');
            $data = ['test_file' => $test_file];
            if (Storage::exists('public/' . $this->test_file)) {
                Storage::delete('public/' . $this->test_file);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        JobApplyTest::find($this->job_apply_test_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'job_vacancy_id'  => 'required',
            'job_vacancy_test_id'  => 'required',
            'user_id'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataJobApplyTestById($job_apply_test_id)
    {
        $this->_reset();
        $row = JobApplyTest::find($job_apply_test_id);
        $this->job_apply_test_id = $row->id;
        $this->job_vacancy_id = $row->job_vacancy_id;
        $this->job_vacancy_test_id = $row->job_vacancy_test_id;
        $this->test_file = $row->test_file;
        $this->user_id = $row->user_id;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getJobApplyTestId($job_apply_test_id)
    {
        $row = JobApplyTest::find($job_apply_test_id);
        $this->job_apply_test_id = $row->id;
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
        $this->job_apply_test_id = null;
        $this->job_vacancy_id = null;
        $this->job_vacancy_test_id = null;
        $this->test_file_path = null;
        $this->test_file = null;
        $this->user_id = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
