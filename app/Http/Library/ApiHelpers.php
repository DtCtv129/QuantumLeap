<?php
namespace App\Http\Library;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\File;
use App\Models\PieceJointe;
use Illuminate\Http\Request;

trait ApiHelpers
{
    protected function isAdmin($user):bool
    {
        if (!empty($user)) {
            return $user->tokenCan("admin");
        }
        return false;
    }
    protected function isUser($user):bool
    {
        if (!empty($user)) {
            return $user->tokenCan("user");
        }
        return false;
    }

    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'errors' => $message,
        ], $code);
    }

   
    protected function demandeValidatedRules(): array
    {
        return [
            // 'oeuvreId' => ['required']
            'files.*' => [
                'required',
                File::types(['pdf'])
                    ->max(1024)
                    ]
            ];
    }
    protected function userUpdateValidatedRules(): array
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ];
    }
    protected function userValidatedRules(): array
    {
        return [
            'firstName' => ['required', 'string','min:6','max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'lastName' => ['required', 'string', 'min:6','max:255'],
            'password' => ['required', 'string', 'min:8']
        ];
    }
    public function storeFiles(Request $request,$demandeId)
    {
        $files = [];
        if ($request->file('files')){
            foreach($request->file('files') as $key => $file)
            {
                $fileName = time().rand(1,99).'.'.$file->extension();  
                $file->storeAs('files', $fileName);
                $files[]['name'] = $fileName;
            }
        }
        foreach ($files as $file) {
           PieceJointe::create([
            'demande_id'=>$demandeId,
            'name'=>$file['name']
           ]);
        }        
    }
}