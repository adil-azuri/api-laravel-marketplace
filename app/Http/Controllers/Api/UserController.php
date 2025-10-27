<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function profile (Request $request){
        return response()->json([
            'status' => 'success',
            'message' => 'profile fetch success',
            'user' => $request->user()
        ]);
    }
    
    public function update (Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Validate Input
        $updateData = $request->validate([
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'username' => [
                'sometimes', 
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'fullname' => ['sometimes', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'], // max 5MB
        ]);

        $oldPicture = $user->profile_picture;
        $newProfilePath = null ;

        
        //upload file jika ada
        if($request->hasFile('profile_picture')){
            // Simpan file baru dan path-nya
            $newProfilePath = $request->file('profile_picture')->store('profile_picture','public');
            $updateData['profile_picture'] =$newProfilePath;
        }
        //update data
        $user->update($updateData);
        

        if($newProfilePath && $oldPicture){
            Storage::disk('public')->delete($oldPicture);
        }

        // Muat ulang data user dari database untuk mendapatkan data terbaru
        $user->refresh();

        //response json
        return response()->json([
            'status' => 'success',
            'message' => 'profile update success',
            'user' => $user
        ],200);
            
        

    }
    
}
