<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Visitor;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class VisitorsController extends Controller
{
    public function index(): View
    {
        return view('modules.visitors.index');
    }

    public function getVisitorsData(Request $request)
    {
        $query = Visitor::query();

        // Global search
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('phone', 'LIKE', "%{$searchValue}%")
                  ->orWhere('purpose', 'LIKE', "%{$searchValue}%")
                  ->orWhere('meeting_with', 'LIKE', "%{$searchValue}%")
                  ->orWhere('visitor_type', 'LIKE', "%{$searchValue}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('time_in_formatted', function ($row) {
                return $row->time_in ? $row->time_in->format('Y-m-d h:i A') : 'N/A';
            })
            ->addColumn('time_out_formatted', function ($row) {
                return $row->time_out ? $row->time_out->format('Y-m-d h:i A') : 'N/A';
            })
            ->addColumn('actions', function ($row) {
                $viewBtn = '<button class="btn btn-sm btn-outline-info view-visitor-btn" data-id="' . $row->id . '" title="View Visitor"><i class="bi bi-eye"></i></button>';
                $editBtn = '<button class="btn btn-sm btn-outline-primary edit-visitor-btn" data-id="' . $row->id . '" title="Edit Visitor"><i class="bi bi-pencil"></i></button>';
                $deleteBtn = '<button class="btn btn-sm btn-outline-danger delete-visitor-btn" data-id="' . $row->id . '" title="Delete Visitor"><i class="bi bi-trash"></i></button>';

                return '<div class="btn-group btn-group-sm gap-1" role="group">' . $viewBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create(): View
    {
        return view('modules.visitors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'purpose' => 'required|string|max:255',
            'meeting_with' => 'required|string|max:255',
            'visitor_type' => 'required|string|max:255',
            'time_in' => 'nullable|date',
            'time_out' => 'nullable|date|after_or_equal:time_in',
        ]);

        $data = $request->only(['name', 'phone', 'purpose', 'meeting_with', 'visitor_type']);
        $data['time_in'] = $request->time_in ? Carbon::parse($request->time_in) : now();
        if ($request->time_out) {
            $data['time_out'] = Carbon::parse($request->time_out);
        }

        $visitor = Visitor::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Visitor logged successfully.',
            'visitor' => $visitor,
            'redirect' => route('visitors.index')
        ]);
    }

    public function show($id)
    {
        $visitor = Visitor::findOrFail($id);
        
        $visitor->time_in_formatted = $visitor->time_in ? $visitor->time_in->format('Y-m-d\TH:i') : '';
        $visitor->time_out_formatted = $visitor->time_out ? $visitor->time_out->format('Y-m-d\TH:i') : '';

        return response()->json([
            'success' => true,
            'visitor' => $visitor
        ]);
    }

    public function update(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'purpose' => 'required|string|max:255',
            'meeting_with' => 'required|string|max:255',
            'visitor_type' => 'required|string|max:255',
            'time_in' => 'nullable|date',
            'time_out' => 'nullable|date|after_or_equal:time_in',
        ]);

        $data = $request->only(['name', 'phone', 'purpose', 'meeting_with', 'visitor_type']);
        $data['time_in'] = $request->time_in ? Carbon::parse($request->time_in) : null;
        $data['time_out'] = $request->time_out ? Carbon::parse($request->time_out) : null;

        $visitor->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Visitor updated successfully.',
            'visitor' => $visitor,
        ]);
    }

    public function destroy($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Visitor deleted successfully.'
        ]);
    }
}
