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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone' => 'required|regex:/^[0-9]+$/',
            'address' => 'required|string|max:500',
            'purpose' => 'required|in:rehabilitas,skhpn,bagian umum,pemberantasan,lainnya',
        ];
        
        // If purpose is 'lainnya', require purpose_lainnya field
        if ($request->input('purpose') === 'lainnya') {
            $rules['purpose_lainnya'] = 'required|string|max:500';
        }
        
        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.email' => 'Format email tidak valid',
            'email.regex' => 'Format email tidak valid',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka',
            'address.required' => 'Alamat harus diisi',
            'address.string' => 'Alamat harus berupa teks',
            'address.max' => 'Alamat maksimal 500 karakter',
            'purpose.required' => 'Keperluan harus diisi',
            'purpose.in' => 'Keperluan tidak valid',
            'purpose_lainnya.required' => 'Keperluan lainnya harus diisi',
            'purpose_lainnya.string' => 'Keperluan lainnya harus berupa teks',
            'purpose_lainnya.max' => 'Keperluan lainnya maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // If purpose is 'lainnya', store the custom input, otherwise store the selected value
        $purpose = $request->input('purpose') === 'lainnya' 
            ? $request->input('purpose_lainnya')
            : $request->input('purpose');

        Guest::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'purpose' => $purpose,
            'purpose_lainnya' => $request->input('purpose_lainnya'),
            'status' => 'menunggu',
            'duration_seconds' => 0,
        ]);

        return redirect()->route('guests.form')
            ->with('success', 'Terima kasih! Data Anda telah tersimpan.');
    }
}
