<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\View\View;

class VisitorController extends Controller
{
    public function index(): View
    {
        return view('admin.visitors.index', ['visitors' => Visitor::query()->latest('visited_at')->paginate(30)]);
    }
}
