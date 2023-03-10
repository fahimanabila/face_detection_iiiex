<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    /**
     * index
     *
     * @return void
     */
    public function index() {
        $users = User::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data User',
            'data'    => $users
        ], 200);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id) {
        $user = User::findOrfail($id)->with('absensi')->first();

        return response()->json([
            'success' => true,
            'message' => 'Detail data user!',
            'data'    => [$user]
        ], 200);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nim'   => 'required|string|between:8,8|unique:users',
            'nama' => 'required|string',
            'jurusan' => 'required|string',
            'prodi' => 'required|string',
            'kelas' => 'required|string',
            'semester' => 'required|integer',
            'telepon' => 'required|string',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|max:50|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil dibuat!'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data user gagal dibuat!',
        ], 409);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, $id) {
        $data = $request->only(
            'nim',
            'nama',
            'jurusan',
            'prodi',
            'kelas',
            'semester',
            'telepon',
            'email',
        );
        $validator = Validator::make($data, [
            'nim'   => 'required|string|between:8,8|unique:users,nim,'.$id,
            'nama' => 'required|string',
            'jurusan' => 'required|string',
            'prodi' => 'required|string',
            'kelas' => 'required|string',
            'semester' => 'required|integer',
            'telepon' => 'required|string',
            'email' => 'required|string|email|max:100|unique:users,email,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = User::find($id);
        if ($user) {
            $user->nim = $validator->validated()['nim'];
            $user->nama = $validator->validated()['nama'];
            $user->jurusan = $validator->validated()['jurusan'];
            $user->prodi = $validator->validated()['prodi'];
            $user->kelas = $validator->validated()['kelas'];
            $user->semester = $validator->validated()['semester'];
            $user->telepon = $validator->validated()['telepon'];
            $user->email = $validator->validated()['email'];
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data user berhasil diupdate!',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data user tidak ditemukan!',
            ], 404);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id) {
        $user = User::findOrfail($id);

        if ($user) {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil dihapus!',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data user tidak ditemukan!',
        ], 404);
    }

    public function user_nim() {
        $users = User::latest()->get()->pluck('nama', 'nim');
        return response()->json([
            'success' => true,
            'message' => 'List Data User',
            'data'    => $users
        ], 200);
    }
}