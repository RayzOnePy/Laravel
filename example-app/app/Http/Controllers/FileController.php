<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Exceptions\TestException;
use App\Http\Requests\FileStoreRequest;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(FileStoreRequest $request): JsonResponse
    {
        $fileName = $request->input('name');
        $fileExtension = $request->file('file')->extension();
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

        $request->file('file')->storeAs($filePath, $fileFullName);

        return response()->json(['success' => true, 'code' => 200, 'message' => 'Success', 'name' => $fileFullName, 'url' => $fileFullPath], 200);
    }

    public function download(Request $request, string $fileName): JsonResponse
    {
        $file = File::findByName($request->user()->id, $fileName);

        if (is_null($file) || is_null(Storage::fileExists($file->getPathWithName()))) {
            throw new NotFoundHttpException();
        }

        $fileFullPath = $file->getPathWithName();

        Storage::download($fileFullPath);

        return response()->json(['success' => true, 'code' => 200], 200);
    }

    public function edit(Request $request, string $fileName): JsonResponse
    {
        $file = File::findByName($request->user()->id, $fileName);

        if (is_null($file) || is_null(Storage::fileExists($file->getPathWithName()))) {
            throw new NotFoundHttpException();
        }

        $filePath = $file->getPath();

        $fileOldPath = $file->getPathWithName();
        $fileNewPath = $filePath . '/' . $request['new_name'] . '.' . $file->getExtension();
//        $fileNewPath = "$filePath/{$request['new_name']}.$file->getExtension()"; так читается хуже

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

        if (is_null($file) || is_null(Storage::fileExists($file->getPathWithName()))) {
            throw new NotFoundHttpException();
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
