<?php

namespace App\Http\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseController extends Component
{
    use WithFileUploads;
    public $course_id;
    public $course_description;
    public $course_image;
    public $course_name;
    public $course_url;
    public $user_id;
    public $course_image_path;

    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataCourseById', 'getCourseId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.course.course')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'course_description'  => $this->course_description,
            'course_name'  => $this->course_name,
            'course_url'  => $this->course_url,
            'user_id'  => auth()->user()->id
        ];

        if ($this->course_image_path) {
            $course_image = $this->course_image_path->store('upload', 'public');
            $data['course_image'] = getImage($course_image);
        }

        Course::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'course_description'  => $this->course_description,
            'course_name'  => $this->course_name,
            'course_url'  => $this->course_url,
        ];
        $row = Course::find($this->course_id);


        if ($this->course_image_path) {
            $course_image = $this->course_image_path->store('upload', 'public');
            $data['course_image'] = getImage($course_image);
            if (Storage::exists('public/' . $this->course_image)) {
                Storage::delete('public/' . $this->course_image);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        Course::find($this->course_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'course_description'  => 'required',
            'course_name'  => 'required',
            'course_url'  => 'required',
            // 'user_id'  => 'required'
        ];

        if ($this->update_mode) {
            $rule['course_image'] = 'required';
        }

        return $this->validate($rule);
    }

    public function getDataCourseById($course_id)
    {
        $this->_reset();
        $row = Course::find($course_id);
        $this->course_id = $row->id;
        $this->course_description = $row->course_description;
        $this->course_image = $row->course_image;
        $this->course_name = $row->course_name;
        $this->course_url = $row->course_url;
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

    public function getCourseId($course_id)
    {
        $row = Course::find($course_id);
        $this->course_id = $row->id;
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
        $this->course_id = null;
        $this->course_description = null;
        $this->course_image_path = null;
        $this->course_image = null;
        $this->course_url = null;
        $this->course_name = null;
        $this->user_id = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
