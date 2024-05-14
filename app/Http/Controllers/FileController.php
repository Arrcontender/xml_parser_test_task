<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\XmlParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $query = File::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->input('created_at'));
        }

        $files = $query->get();

        return view('files', compact('files'));
    }

    public function upload(Request $request, XmlParserService $service)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xml'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('file');

            if ($service->parse($file)) {
                return redirect()->back()->with('success', 'File uploaded successfully');
            } else {
                return redirect()->back()->with('error', 'File does not match our signature');
            }
        } catch (\Throwable $exception) {
            report($exception);
            return redirect()->back()->with('error', 'Parsing error');
        }
    }

    public function getFile(Request $request)
    {
        $filename = $request->input('filename');
        return file_get_contents(public_path('uploads/' . $filename));
    }

    public function showFile(Request $request)
    {
        $filename = $request->input('filename');
        $xmlContent = file_get_contents(public_path('uploads/' . $filename));
        return view('file_content')->with('xmlContent', $xmlContent);
    }

    public function showAllInJson(Request $request, XmlParserService $service)
    {
        try {
            $files = File::all();
            $result = [];
            foreach ($files as $file) {
                $json = $service->xmlToJson($file->name);
                $result[] = $json;
            }
            return response()->json($result);
        } catch (\Exception $exception) {
            return response()->json(['message' => "Files don't exist"], 404);
        }
    }

    public function showSelectedInJson(Request $request, XmlParserService $service)
    {
        $ids = $request->input('ids');

        if (!isset($ids)) {
            return response()->json(['message' => 'No file ids provided'], 400);
        }

        try {
            if (is_array($ids)) {
                $files = File::findOrFail($ids);
                $result = [];
                foreach ($files as $file) {
                    $json = $service->xmlToJson($file->name);
                    $result[] = $json;
                }
                return response()->json($result);
            } else {
                $file = File::findOrFail($ids);
                $json = $service->xmlToJson($file->name);
                return response()->json($json);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => "File(s) doesn't exist"], 404);
        }
    }
}
