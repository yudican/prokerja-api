<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\JobApply;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class JobApplyTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_job_applies';

    public function builder()
    {
        return JobApply::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('user.name')->label('Pengguna')->searchable(),
            Column::name('jobVacancy.job_name')->label('Lowongan')->searchable(),
            Column::callback(['cv_file'], function ($file) {
                return '<a href="' . $file . '">show file</a>';
            })->label(__('Cv File')),
            Column::callback(['biodata_file'], function ($file) {
                return '<a href="' . $file . '">show file</a>';
            })->label(__('Lowongan')),
            Column::callback(['surat_lamaran_file'], function ($file) {
                return '<a href="' . $file . '">show file</a>';
            })->label(__('Surat Lamaran File')),


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
        $this->emit('getDataJobApplyById', $id);
    }

    public function getId($id)
    {
        $this->emit('getJobApplyId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
