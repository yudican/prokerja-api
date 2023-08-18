<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\JobApplyTest;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class JobApplyTestTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_job_apply_tests';

    public function builder()
    {
        return JobApplyTest::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('user.name')->label('Pengguna')->searchable(),
            Column::name('jobVacancy.job_name')->label('Lowongan')->searchable(),
            Column::name('jobVacancyTest.test_name')->label('Lowongan Test')->searchable(),
            Column::callback(['test_file'], function ($file) {
                return '<a href="' . $file . '">show file</a>';
            })->label(__('Test File')),

            // Column::callback(['id'], function ($id) {
            //     return view('crud-generator-components::action-button', [
            //         'id' => $id,
            //         'actions' => [
            //             [
            //                 'type' => 'button',
            //                 'route' => 'getDataById(' . $id . ')',
            //                 'label' => 'Edit',
            //             ],
            //             [
            //                 'type' => 'button',
            //                 'route' => 'confirmDelete(' . $id . ')',
            //                 'label' => 'Hapus',
            //             ]
            //         ]
            //     ]);
            // })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataJobApplyTestById', $id);
    }

    public function getId($id)
    {
        $this->emit('getJobApplyTestId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
