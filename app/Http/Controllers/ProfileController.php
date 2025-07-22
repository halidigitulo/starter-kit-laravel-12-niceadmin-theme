<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        return view('profiles.index', compact('profile'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profile = Profile::firstOrNew();
        $profile->fill($request->all());


        // Handle logo upload
        $files = ['logo','icon','cover'];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                // Delete the old file if it exists
                if ($profile->$file && file_exists(public_path('uploads/' . $profile->$file))) {
                    unlink(public_path('uploads/' . $profile->$file));
                }

                // Save the new file
                $uploadedFile = $request->file($file);
                $fileName = $profile->nama . '_' . $file . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->move(public_path('uploads/'), $fileName);

                // Update the profile record
                $profile->$file = $fileName;
            }
        }

        $profile->save();

        return response()->json([
            'status' => 'success',
            'message' => $profile->wasRecentlyCreated ? 'Profile berhasil disimpan' : 'Profile berhasil diupdate',
            'profile' => $profile,
            // 'isi' => $profile->isi,
        ]);
    }
}
