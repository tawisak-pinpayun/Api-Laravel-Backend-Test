<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Upload;
// use App\Http\Requests\ImageStoreRequest;
class UploadController extends Controller
{
    // public function imageStore(ImageStoreRequest $request)
    // {
    //     $validatedData = $request->validated();
    //     $validatedData['image'] = $request->file('image')->store('image');
    //     $data = Upload::create($validatedData);

    //     return response($data, Response::HTTP_CREATED);
    // }

    public function imageStore(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'image_name' => 'required',
          ], [
            'image_name.required' => 'error: “Please select a file”',
          ]);
        try {
            $upload = new Upload();
            if($request->hasFile('image_name')) {
                $image = $request->file('image_name');
                $image_name = $image->getClientOriginalName();
                $size = $request->file('image_name')->getSize();
                $extension = $request->file('image_name')->getExtension();
            } else {
                $image_name = '';
            }
                $upload->image_name = $image_name;
                $upload->path = "storage/upload/'$image_name";
                $upload->save();

            $content = array(
                'success' => true,
                'data' => $upload,
                'message' => "OK",
                'status' => 200,
                'size' => $size,
                'extension' => $extension,
            );

            return response($content)->setStatusCode(200);
        } catch (\Exception $e) {
            $content = array(
                'success' => false,
                'data' => 'something went wrong.',
                'message' => 'There was an error while processing your request: ' .
                    $e->getMessage()
            );
            return response($content)->setStatusCode(500);
        }
    }
}
