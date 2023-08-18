<?php

namespace App\Http\Livewire\Job;

use App\Models\JobVacancy;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobVacancyController extends Component
{
    use WithFileUploads;
    public $job_vacancie_id;
    public $job_company_name;
    public $job_description;
    public $job_image;
    public $job_location;
    public $job_name;
    public $job_image_path;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataJobVacancyById', 'getJobVacancyId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.job.job-vacancie')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'job_company_name'  => $this->job_company_name,
            'job_description'  => $this->job_description,
            'job_location'  => $this->job_location,
            'job_name'  => $this->job_name
        ];

        if ($this->job_image_path) {
            $job_image = $this->job_image_path->store('upload', 'public');
            $data['job_image'] = $job_image;
        }

        JobVacancy::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'job_company_name'  => $this->job_company_name,
            'job_description'  => $this->job_description,
            'job_image'  => $this->job_image,
            'job_location'  => $this->job_location,
            'job_name'  => $this->job_name
        ];
        $row = JobVacancy::find($this->job_vacancie_id);


        if ($this->job_image_path) {
            $job_image = $this->job_image_path->store('upload', 'public');
            $data = ['job_image' => $job_image];
            if (Storage::exists('public/' . $this->job_image)) {
                Storage::delete('public/' . $this->job_image);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        JobVacancy::find($this->job_vacancie_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'job_company_name'  => 'required',
            'job_description'  => 'required',
            'job_location'  => 'required',
            'job_name'  => 'required'
        ];

        if (!$this->update_mode) {
            $rule['job_image_path'] = 'required';
        }

        return $this->validate($rule);
    }

    public function getDataJobVacancyById($job_vacancie_id)
    {
        $this->_reset();
        $row = JobVacancy::find($job_vacancie_id);
        $this->job_vacancie_id = $row->id;
        $this->job_company_name = $row->job_company_name;
        $this->job_description = $row->job_description;
        $this->job_image = $row->job_image;
        $this->job_location = $row->job_location;
        $this->job_name = $row->job_name;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getJobVacancyId($job_vacancie_id)
    {
        $row = JobVacancy::find($job_vacancie_id);
        $this->job_vacancie_id = $row->id;
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
        $this->job_vacancie_id = null;
        $this->job_company_name = null;
        $this->job_description = null;
        $this->job_image_path = null;
        $this->job_image = null;
        $this->job_location = null;
        $this->job_name = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
