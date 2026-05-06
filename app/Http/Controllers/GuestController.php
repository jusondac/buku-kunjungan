<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    /**
     * Show the public guest form
     */
    public function showForm()
    {
        return view('guests.form');
    }

    /**
     * Store guest data
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'purpose' => 'required|string|max:500',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.string' => 'Nomor telepon harus berupa teks',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'address.required' => 'Alamat harus diisi',
            'address.string' => 'Alamat harus berupa teks',
            'address.max' => 'Alamat maksimal 500 karakter',
            'purpose.required' => 'Keperluan harus diisi',
            'purpose.string' => 'Keperluan harus berupa teks',
            'purpose.max' => 'Keperluan maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Guest::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'purpose' => $request->input('purpose'),
            'status' => 'menunggu',
        ]);

        return redirect()->route('guests.form')
            ->with('success', 'Terima kasih! Data Anda telah tersimpan.');
    }
}
