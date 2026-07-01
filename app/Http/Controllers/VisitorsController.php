<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitorsController extends Controller
{
    public function index(): View
    {
        return view('modules.visitors.index');
    }

    public function create()
    {
        // TODO: Return create view
    }

    public function store(Request $request)
    {
        // TODO: Handle store logic
    }

    public function show($id)
    {
        // TODO: Return show view
    }

    public function edit($id)
    {
        // TODO: Return edit view
    }

    public function update(Request $request, $id)
    {
        // TODO: Handle update logic
    }

    public function destroy($id)
    {
        // TODO: Handle delete logic
    }
}
