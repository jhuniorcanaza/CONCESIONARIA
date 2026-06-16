<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $path = $file->store('temp', 'public');

            // write this path to session array             
            $uploadedImages = session('uploadedImages', []);
            
            $uploadedImages[] = $path;
            session(['uploadedImages' => $uploadedImages]);

            return response()->json([
                'name'          => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        return response()->json(['error' => 'No image found.'], 400);
    }

    public function delete(Request $request)
    {
        $uploadedImages = session('uploadedImages', []);

        if (($path = $request->json('path')) && in_array($path, $uploadedImages)) {            
            
            //delete from storage
            Storage::disk('public')->delete($path);
            
            //delete from session
            $uploadedImages = array_filter($uploadedImages, fn($img) => $img !== $path);
            session(['uploadedImages' => $uploadedImages]);

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json(['error' => 'No path provided.'], 400);
    }
}