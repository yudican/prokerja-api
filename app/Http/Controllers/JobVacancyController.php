<?php

namespace App\Http\Controllers;

use App\Models\JobApply;
use App\Models\JobApplyTest;
use App\Models\JobVacancy;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobVacancyController extends Controller
{
    public function listJobs()
    {
        return response()->json([
            'message' => 'List Jobs',
            'data' => JobVacancy::all()
        ]);
    }

    public function applyTest(Request $request)
    {
        $user = auth()->user();
        $data = [
            'job_vacancy_id' => $request->job_vacancy_id,
            'job_vacancy_test_id' => $request->job_vacancy_test_id,
            'user_id' => $user->id,
        ];


        if ($request->hasFile('test_file')) {
            $file = $request->file('test_file');

            // Rubah original filename ketika diupload by Nawan
            $filename = md5($file->getClientOriginalName() . '' . $user->id . '' . date('YmdHis') . '_' . rand()) . '.' . $file->extension();

            // Simpan file ke storage
            $path = $file->storeAs('tests', $filename);

            $data['test_file'] = asset('storage/' . $path);
        }

        $test = JobApplyTest::create($data);

        Notification::create(
            [
                'user_id' => $user->id,
                'name' => 'Mengerjakan Test',
                'description' => 'Terima kasih telah mengerjakan test yang telah kami sediakan',
                'status' => '0'
            ]
        );

        return response()->json([
            'message' => 'Apply Test',
            'data' => $test
        ]);
    }

    public function apply(Request $request)
    {
        $user = auth()->user();
        $data = [
            'job_vacancy_id' => $request->job_vacancy_id,
            'job_vacancy_test_id' => $request->job_vacancy_test_id,
            'user_id' => $user->id,
        ];

        if ($request->hasFile('biodata_file')) {
            $file = $request->file('biodata_file');

            // Rubah original filename ketika diupload by Nawan
            $filename = md5($file->getClientOriginalName() . '' . $user->id . '' . date('YmdHis') . '_' . rand()) . '.' . $file->extension();

            // Simpan file ke storage
            $path = $file->storeAs('apply/biodata', $filename);

            $data['biodata_file'] = asset('storage/' . $path);
        }
        if ($request->hasFile('cv_file')) {
            $file = $request->file('cv_file');

            // Rubah original filename ketika diupload by Nawan
            $filename = md5($file->getClientOriginalName() . '' . $user->id . '' . date('YmdHis') . '_' . rand()) . '.' . $file->extension();

            // Simpan file ke storage
            $path = $file->storeAs('apply/cv', $filename);

            $data['cv_file'] = asset('storage/' . $path);
        }
        if ($request->hasFile('surat_lamaran_file')) {
            $file = $request->file('surat_lamaran_file');

            // Rubah original filename ketika diupload by Nawan
            $filename = md5($file->getClientOriginalName() . '' . $user->id . '' . date('YmdHis') . '_' . rand()) . '.' . $file->extension();

            // Simpan file ke storage
            $path = $file->storeAs('apply/lamaran', $filename);

            $data['surat_lamaran_file'] = asset('storage/' . $path);
        }

        $apply = JobApply::create($data);
        Notification::create(
            [
                'user_id' => $user->id,
                'name' => 'Mengirim Lamaran',
                'description' => 'Terima kasih, Lamaran kamu telah kami terima',
                'status' => '0'
            ]
        );

        return response()->json([
            'message' => 'Apply',
            'data' => $apply
        ]);
    }

    public function getJobDetail($job_id)
    {
        return response()->json([
            'message' => 'List Course',
            'data' => JobVacancy::find($job_id)
        ]);
    }

    public function applyHistory()
    {
        $user = auth()->user();
        $apply = JobApply::where('user_id', $user->id)->get();

        return response()->json([
            'message' => 'Apply',
            'data' => $apply
        ]);
    }
}
