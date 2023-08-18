<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getUserProfile()
    {
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
            'message' => 'Profile User Ditemukan'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
        ]);

        // response validation error
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Isi Semua Form Yang Tersedia',
                'messages' => $validator->errors()
            ], 400);
        }

        // check email
        try {
            DB::beginTransaction();
            $userCheck = User::where('email', $request->email)->where('id', '!=', auth()->user()->id)->first();
            if ($userCheck) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email Sudah Terdaftar',
                    'messages' => [
                        'email' => 'Email Sudah Terdaftar'
                    ]
                ], 400);
            }
            $user = auth()->user();

            $dataUser = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                // Rubah original filename ketika diupload by Nawan
                $filename = md5($file->getClientOriginalName() . '' . $user->id . '' . date('YmdHis') . '_' . rand()) . '.' . $file->extension();

                // Hapus file jika sudah ada sebelumnya
                // $disk = Storage::disk('public');
                // if ($disk->exists('photos/' . $filename)) {
                //     $disk->delete('photos/' . $filename);
                // }

                // Hapus foto sebelumnya by Nawan
                if ($user->profile_photo_path) {
                    $url = parse_url($user->profile_photo_path);

                    $oldFoto = str_replace('/storage/', '', $url['path']);

                    $disk = Storage::disk('public');

                    if ($disk->exists($oldFoto)) {
                        $disk->delete($oldFoto);
                    }
                }

                // Simpan file ke storage
                $path = $file->storeAs('photos', $filename);

                $dataUser['foto'] = asset('storage/' . $path);
            }


            $user->update($dataUser);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan',
                'data' => new UserResource($user)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Data Gagal Disimpan',
                'messages' => $th->getMessage(),
                'user' => auth()->user()
            ], 400);
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // response validation error
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 400);
        }

        $user = User::find(Auth::user()->id);

        // check old password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'Password lama tidak sesuai',
            ], 422);
        }

        $data = [
            'password' => Hash::make($request->password),
        ];

        $user->update($data);

        return response()->json([
            'message' => 'success',
            'user' => new UserResource($user),
        ]);
    }
}
