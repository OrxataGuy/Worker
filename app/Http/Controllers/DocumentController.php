<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use \App\Models\Document;


class DocumentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        $fileName = time().'_'.$request->file->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

        $doc = Document::create([
            'name' => $request->file->getClientOriginalName(),
            'type' => strtolower($request->file->getClientMimeType()),
            'url' => "https://dev.orxatasoftware.com/storage/$filePath"
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $doc
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
