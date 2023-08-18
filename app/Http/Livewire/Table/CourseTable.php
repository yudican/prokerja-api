<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\Course;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class CourseTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_courses';

    public function builder()
    {
        return Course::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('course_name')->label('Course Name')->searchable(),
            Column::name('course_description')->label('Course Description')->searchable(),
            Column::callback(['course_image'], function ($image) {
                return view('livewire.components.photo', [
                    'image_url' => asset('storage/' . $image),
                ]);
            })->label(__('Course Image')),
            // Column::name('user_id')->label('User')->searchable(),

            Column::callback(['id'], function ($id) {
                return view('crud-generator-components::action-button', [
                    'id' => $id,
                    'actions' => [
                        [
                            'type' => 'button',
                            'route' => 'getDataById(' . $id . ')',
                            'label' => 'Edit',
                        ],
                        [
                            'type' => 'button',
                            'route' => 'confirmDelete(' . $id . ')',
                            'label' => 'Hapus',
                        ]
                    ]
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataCourseById', $id);
    }

    public function getId($id)
    {
        $this->emit('getCourseId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function confirmDelete($id)
    {
        $this->emit('getCourseId', $id);
        $this->emit('confirmDelete');
    }
}
