<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $fileName = $request->input('name');
        $fileExtension = $request->file('image')->extension();
        $fileFullName = $fileName . '.' . $fileExtension;
        $filePath = 'uploads/' . $request->user()->id . $request->user()->first_name . $request->user()->last_name;
        $fileFullPath = $filePath . '/' . $fileFullName;
        $fileUserId = $request->user()->id;
        $file = File::create([
            'name' => $fileName,
            'extension' => $fileExtension,
            'full_name' => $fileFullName,
            'path' => $filePath,
            'user_id' => $fileUserId
        ]);

        $request->file('image')->storeAs($filePath, $fileFullName);

        return response()->json(['success' => true, 'code' => 200, 'message' => 'Success', 'name' => $fileFullName, 'url' => $fileFullPath], 200);
    }

    public function download(Request $request, string $fileName): JsonResponse
    {
        $file = File::findByName($request->user()->id, $fileName);

        if ($file == null || Storage::fileExists($file->getPathWithName()) == null) {
            return response()->json(['message' => 'Not found', 'code' => 404], 404);
        }

        $fileFullPath = $file->getPathWithName();

        Storage::download($fileFullPath);

        return response()->json(['success' => true, 'code' => 200], 200);
    }

    public function edit(Request $request, string $fileName): JsonResponse
    {
        $file = File::findByName($request->user()->id, $fileName);

        if ($file == null || Storage::fileExists($file->getPathWithName()) == null) {
            return response()->json(['message' => 'Not found', 'code' => 404], 404);
        }

        $filePath = $file->getPath();

        $fileOldPath = $file->getPathWithName();
        $fileNewPath = $filePath . '/' . $request['new_name'] . '.' . $file->getExtension();

        Storage::move($fileOldPath, $fileNewPath);

        $file->forceFill([
            'name' => $request['new_name'],
            'full_name' => $request['new_name'] . '.' . $file->getExtension(),
        ])->save();

        return response()->json(['message' => $fileNewPath], 200);
    }

    public function delete(Request $request, string $fileName): JsonResponse
    {
        $file = File::findByName($request->user()->id, $fileName);

        if ($file == null || Storage::fileExists($file->getPathWithName()) == null) {
            return response()->json(['message' => 'Not found', 'code' => 404], 404);
        }

        Storage::delete($file->getPathWithName());
        $file->delete();

        return response()->json(['success' => true, 'code' => 200, 'message' => 'File deleted'], 200);
    }

    public function showAll(Request $request): JsonResponse
    {
        $files = File::findAllByUser($request->user()->id);
        return response()->json(['files' => $files], 200);
    }
}
