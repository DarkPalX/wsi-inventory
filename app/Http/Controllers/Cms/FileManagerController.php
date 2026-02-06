<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:admin/file-manager', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        return view('admin.cms4.files.index');
    }
}
