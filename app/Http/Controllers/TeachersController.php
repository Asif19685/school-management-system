<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Teacher;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TeachersController extends Controller
{
    public function index(): View
    {
        return view('modules.teachers.index');
    }

    public function create(): View
    {
        return view('modules.teachers.create');
    }

    public function getTeachersData(Request $request)
    {
        $query = Teacher::query();

        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('teacher_code', 'LIKE', "%{$searchValue}%")
                  ->orWhere('email', 'LIKE', "%{$searchValue}%")
                  ->orWhere('phone', 'LIKE', "%{$searchValue}%")
                  ->orWhere('qualification', 'LIKE', "%{$searchValue}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('joining_date_formatted', function ($row) {
                return $row->joining_date ? $row->joining_date->format('d M Y') : 'N/A';
            })
            ->addColumn('salary_formatted', function ($row) {
                return 'Rs. ' . number_format($row->salary, 2);
            })
            ->addColumn('actions', function ($row) {
                $viewBtn   = '<button class="btn btn-sm btn-outline-info view-teacher-btn" data-id="' . $row->id . '" title="View Teacher"><i class="bi bi-eye"></i></button>';
                $editBtn   = '<button class="btn btn-sm btn-outline-primary edit-teacher-btn" data-id="' . $row->id . '" title="Edit Teacher"><i class="bi bi-pencil"></i></button>';
                $deleteBtn = '<button class="btn btn-sm btn-outline-danger delete-teacher-btn" data-id="' . $row->id . '" title="Delete Teacher"><i class="bi bi-trash"></i></button>';
                return '<div class="btn-group btn-group-sm gap-1" role="group">' . $viewBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_code'  => 'required|string|max:100|unique:teachers,teacher_code',
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'joining_date'  => 'nullable|date',
            'salary'        => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['teacher_code', 'name', 'email', 'phone', 'qualification', 'salary']);
        $data['joining_date'] = $request->joining_date ? Carbon::parse($request->joining_date)->toDateString() : null;
        $data['user_id'] = auth()->id(); // Store logged-in user ID

        $teacher = Teacher::create($data);
 

        return response()->json([
            'success' => true,
            'message' => 'Teacher added successfully.',
            'teacher' => $teacher,
            'redirect' => route('teachers.index'),
        ]);
    }

    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->joining_date_formatted = $teacher->joining_date ? $teacher->joining_date->format('Y-m-d') : '';
        return response()->json(['success' => true, 'teacher' => $teacher]);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'teacher_code'  => 'required|string|max:100|unique:teachers,teacher_code,' . $id,
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'joining_date'  => 'nullable|date',
            'salary'        => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['teacher_code', 'name', 'email', 'phone', 'qualification', 'salary']);
        $data['joining_date'] = $request->joining_date ? Carbon::parse($request->joining_date)->toDateString() : null;

        $teacher->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully.',
            'teacher' => $teacher,
        ]);
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher deleted successfully.',
        ]);
    }
}
