<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\JobVacancy;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class JobVacancyTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_job_vacancies';

    public function builder()
    {
        return JobVacancy::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('job_name')->label('Job Name')->searchable(),
            Column::name('job_company_name')->label('Job Company Name')->searchable(),
            // Column::name('job_description')->label('Job Description')->searchable(),
            Column::name('job_location')->label('Job Location')->searchable(),
            Column::callback(['job_image'], function ($image) {
                return view('livewire.components.photo', [
                    'image_url' => asset('storage/' . $image),
                ]);
            })->label(__('Job Image')),

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
        $this->emit('getDataJobVacancyById', $id);
    }

    public function getId($id)
    {
        $this->emit('getJobVacancyId', $id);
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
