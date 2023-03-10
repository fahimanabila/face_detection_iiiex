<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller {
    /**
     * index
     *
     * @return void
     */
    public function index() {
        $absensis = Absensi::latest()->with('user')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Absensi',
            'data'    => $absensis
        ], 200);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id) {
        $absensi = Absensi::findOrfail($id)->with('user')->first();

        return response()->json([
            'success' => true,
            'message' => 'Detail data absensi!',
            'data'    => [$absensi]
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
            'nim'   => 'required|string|between:8,8',
            'status'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $absensi = Absensi::create(
            $validator->validated()
        );

        if ($absensi) {
            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil dibuat!'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data absensi gagal dibuat!',
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
            'status',
        );
        $validator = Validator::make($data, [
            'nim'   => 'required|string|between:8,8',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $absensi = Absensi::find($id);
        if ($absensi) {
            $absensi->nim = $validator->validated()['nim'];
            $absensi->status = $validator->validated()['status'];
            $absensi->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data absensi berhasil diupdate!',
                'data' => $absensi
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data absensi tidak ditemukan!',
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
        $absensi = Absensi::findOrfail($id);

        if ($absensi) {
            $absensi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil dihapus!',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data absensi tidak ditemukan!',
        ], 404);
    }
}
