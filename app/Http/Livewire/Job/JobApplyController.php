<?php

namespace App\Http\Livewire\Job;

use App\Models\JobApply;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobApplyController extends Component
{
    use WithFileUploads;
    public $job_applie_id;
    public $biodata_file;
    public $cv_file;
    public $job_vacancy_id;
    public $surat_lamaran_file;
    public $user_id;
    public $cv_file_path;
    public $job_vacancy_id_path;
    public $surat_lamaran_file_path;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataJobApplyById', 'getJobApplyId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.job.job-applie')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();
        $cv_file = $this->cv_file_path->store('upload', 'public');
        $job_vacancy_id = $this->job_vacancy_id_path->store('upload', 'public');
        $surat_lamaran_file = $this->surat_lamaran_file_path->store('upload', 'public');
        $data = [
            'biodata_file'  => $this->biodata_file,
            'cv_file'  => getImage($cv_file),
            'job_vacancy_id'  => getImage($job_vacancy_id),
            'surat_lamaran_file'  => getImage($surat_lamaran_file),
            'user_id'  => $this->user_id
        ];

        JobApply::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'biodata_file'  => $this->biodata_file,
            'cv_file'  => $this->cv_file,
            'job_vacancy_id'  => $this->job_vacancy_id,
            'surat_lamaran_file'  => $this->surat_lamaran_file,
            'user_id'  => $this->user_id
        ];
        $row = JobApply::find($this->job_applie_id);


        if ($this->cv_file_path) {
            $cv_file = $this->cv_file_path->store('upload', 'public');
            $data['cv_file'] = getImage($cv_file);
            if (Storage::exists('public/' . $this->cv_file)) {
                Storage::delete('public/' . $this->cv_file);
            }
        }

        if ($this->job_vacancy_id_path) {
            $job_vacancy_id = $this->job_vacancy_id_path->store('upload', 'public');
            $data['job_vacancy_id'] = getImage($job_vacancy_id);
            if (Storage::exists('public/' . $this->job_vacancy_id)) {
                Storage::delete('public/' . $this->job_vacancy_id);
            }
        }

        if ($this->surat_lamaran_file_path) {
            $surat_lamaran_file = $this->surat_lamaran_file_path->store('upload', 'public');
            $data['surat_lamaran_file'] = getImage($surat_lamaran_file);
            if (Storage::exists('public/' . $this->surat_lamaran_file)) {
                Storage::delete('public/' . $this->surat_lamaran_file);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        JobApply::find($this->job_applie_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'biodata_file'  => 'required',
            'user_id'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataJobApplyById($job_applie_id)
    {
        $this->_reset();
        $row = JobApply::find($job_applie_id);
        $this->job_applie_id = $row->id;
        $this->biodata_file = $row->biodata_file;
        $this->cv_file = $row->cv_file;
        $this->job_vacancy_id = $row->job_vacancy_id;
        $this->surat_lamaran_file = $row->surat_lamaran_file;
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

    public function getJobApplyId($job_applie_id)
    {
        $row = JobApply::find($job_applie_id);
        $this->job_applie_id = $row->id;
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
        $this->job_applie_id = null;
        $this->biodata_file = null;
        $this->cv_file_path = null;
        $this->job_vacancy_id_path = null;
        $this->surat_lamaran_file_path = null;
        $this->cv_file = null;
        $this->job_vacancy_id = null;
        $this->surat_lamaran_file = null;
        $this->user_id = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
