<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Income;

class CheckController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function show($id)
    {
        $files = Storage::disk('s3')->allFiles('');
        return Storage::disk('s3')->temporaryUrl($files[0], \Carbon\Carbon::now()->addMinutes(5));
    }

    public function store(Request $request, $income)
    {
        $income = Income::findOrFail($income);

        $file = request()->file('check');
        $fileUploaded = $request->check->store('.');

        $income->check = $fileUploaded;
        $income->save();

        return response()->json([
            'data' => $income->toArray()
        ]);

        // $imagesUrl = Storage::disk('s3')->allFiles('');
        // dd($imagesUrl);
        // dd($file);
    }
}
