<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\JobVacancyTest;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class JobVacancyTestTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_job_vacancy_tests';

    public function builder()
    {
        return JobVacancyTest::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('job_vacancy_id')->label('Lowongan')->searchable(),
            Column::name('test_description')->label('Test Description')->searchable(),
            Column::callback(['test_image'], function ($image) {
                return view('livewire.components.photo', [
                    'image_url' => asset('storage/' . $image),
                ]);
            })->label(__('Test Image')),
            Column::name('test_name')->label('Test Name')->searchable(),

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
        $this->emit('getDataJobVacancyTestById', $id);
    }

    public function getId($id)
    {
        $this->emit('getJobVacancyTestId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function confirmDelete($id)
    {
        $this->emit('getJobVacancyTestId', $id);
        $this->emit('confirmDelete');
    }
}
