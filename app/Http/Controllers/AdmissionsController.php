<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Disability;
use App\Models\Fee;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAdmission;
use App\Models\StudentImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdmissionsController extends Controller
{
    public function index(): View
    {
        $classes = SchoolClass::all();
        $sections = Section::all();
        $guardians = Guardian::all();
        $students = Student::all();

        return view('modules.admissions.index', compact('classes', 'sections', 'guardians', 'students'));
    }


    public function getStudentsData(Request $request)
{
    // 1. Sabhi zaroori relations ko eager load kiya
    $students = StudentAdmission::with(['student', 'schoolClass', 'section', 'appliedClass'])
        ->select('student_admissions.*');

    // Class-wise filter
    if ($request->filled('class_filter') && $request->class_filter !== 'all') {
        $classId = $request->class_filter;
        $students->where(function($q) use ($classId) {
            $q->where('class_id', $classId)
              ->orWhere('applied_class_id', $classId);
        });
    }

    // Date range filter (admission_date)
    if ($request->filled('from_date')) {
        $students->whereDate('admission_date', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $students->whereDate('admission_date', '<=', $request->to_date);
    }

    // 2. Custom Global Search Logic
    if ($request->has('search') && !empty($request->search['value'])) {
        $searchValue = $request->search['value'];

        $students->where(function($q) use ($searchValue) {
            $q->where('admission_no', 'LIKE', "%{$searchValue}%")
              ->orWhere('status', 'LIKE', "%{$searchValue}%")
              ->orWhere('admission_date', 'LIKE', "%{$searchValue}%");

            $q->orWhereHas('student', function($query) use ($searchValue) {
                $query->where('first_name', 'LIKE', "%{$searchValue}%")
                      ->orWhere('last_name', 'LIKE', "%{$searchValue}%")
                      ->orWhere('gender', 'LIKE', "%{$searchValue}%");
            });

            $q->orWhereHas('schoolClass', function($query) use ($searchValue) {
                $query->where('class_name', 'LIKE', "%{$searchValue}%");
            });

            // ✅ CHANGE: Applied Class ke liye alag se search
            $q->orWhereHas('appliedClass', function($query) use ($searchValue) {
                $query->where('class_name', 'LIKE', "%{$searchValue}%");
            });

            $q->orWhereHas('section', function($query) use ($searchValue) {
                $query->where('section_name', 'LIKE', "%{$searchValue}%")
                      ->orWhere('roll_no', 'LIKE', "%{$searchValue}%");
            });
        });
    }

    return DataTables::of($students)
        ->addIndexColumn()

        // Filter Columns
        ->filterColumn('admission_no', function($query, $keyword) {
            $query->where('admission_no', 'LIKE', "%{$keyword}%");
        })
        ->filterColumn('student_name', function($query, $keyword) {
            $query->whereHas('student', function($q) use ($keyword) {
                $q->where('first_name', 'LIKE', "%{$keyword}%")
                  ->orWhere('last_name', 'LIKE', "%{$keyword}%");
            });
        })
        ->filterColumn('gender', function($query, $keyword) {
            $query->whereHas('student', function($q) use ($keyword) {
                $q->where('gender', 'LIKE', "%{$keyword}%");
            });
        })
        ->filterColumn('status', function($query, $keyword) {
            $query->where('status', 'LIKE', "%{$keyword}%");
        })
        ->filterColumn('admission_date', function($query, $keyword) {
            $query->where('admission_date', 'LIKE', "%{$keyword}%");
        })
        ->filterColumn('schoolClass', function($query, $keyword) {
            $query->whereHas('schoolClass', function($q) use ($keyword) {
                $q->where('class_name', 'LIKE', "%{$keyword}%");
            });
        })
        // ✅ CHANGE: Applied Class ke liye naya filter
        ->filterColumn('applied_class', function($query, $keyword) {
            $query->whereHas('appliedClass', function($q) use ($keyword) {
                $q->where('class_name', 'LIKE', "%{$keyword}%");
            });
        })
        ->filterColumn('section', function($query, $keyword) {
            $query->whereHas('section', function($q) use ($keyword) {
                $q->where('section_name', 'LIKE', "%{$keyword}%");
            });
        })
        ->filterColumn('roll_no', function($query, $keyword) {
            $query->whereHas('section', function($q) use ($keyword) {
                $q->where('roll_no', 'LIKE', "%{$keyword}%");
            });
        })

        // Columns Formatting
        ->addColumn('admission_no', fn($row) => $row->admission_no ?? 'N/A')
        ->addColumn('student_name', fn($row) => $row->student ? trim(($row->student->first_name ?? '') . ' ' . ($row->student->last_name ?? '')) : 'N/A')
        ->addColumn('phone', fn($row) => $row->student->phone ?? 'N/A')
        ->addColumn('class', fn($row) => $row->schoolClass->class_name ?? 'N/A')
        ->addColumn('section', fn($row) => $row->section->section_name ?? 'N/A')

        // ✅ CHANGE: Applied class ab appliedClass relation se fetch karega
        ->addColumn('applied_class', fn($row) => $row->appliedClass->class_name ?? 'N/A')

        ->addColumn('roll_no', fn($row) => $row->section->roll_no ?? 'N/A')
        ->addColumn('admission_date', fn($row) => $row->admission_date ? \Carbon\Carbon::parse($row->admission_date)->format('d M, Y') : 'N/A')

        // Gender Badge
        ->addColumn('gender', function($row) {
            $gender = strtolower($row->student->gender ?? '');
            $badges = [
                'male' => '<span class="badge bg-info">Male</span>',
                'female' => '<span class="badge bg-warning">Female</span>',
                'other' => '<span class="badge bg-secondary">Other</span>',
            ];
            return $badges[$gender] ?? '<span class="badge bg-secondary">N/A</span>';
        })

        // Status Badge
        ->addColumn('status', function($row) {
            $status = strtolower($row->status);
            $badges = [
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'approved' => '<span class="badge bg-success">Approved</span>',
                'rejected' => '<span class="badge bg-danger">Rejected</span>',
            ];
            return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
        })

        // Actions
        ->addColumn('actions', function($row) {
            $processBtn = '';
            if (strtolower($row->status) !== 'approved') {
                $processBtn = '
                    <button class="btn btn-outline-primary process-btn" data-id="' . $row->id . '" title="Process" onclick="processAdmission(' . $row->id . ')">
                        <i class="bi bi-pencil-square"></i> Process
                    </button>';
            }

            return '
                <div class="btn-group btn-group-sm" role="group">
                    ' . $processBtn . '

                    <button class="btn btn-outline-info view-btn" title="View" onclick="viewAdmission(' . $row->id . ')">
                         <i class="bi bi-eye"></i> View
                    </button>

                    <a href="' . route('admissions.edit', $row->id) . '" class="btn btn-outline-primary edit-btn" title="Edit">
                         <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <button class="btn btn-outline-danger delete-btn" data-id="' . $row->id . '" title="Delete" onclick="deleteAdmission(' . $row->id . ')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            ';
        })
        ->rawColumns(['gender', 'status', 'actions'])
        ->make(true);
}
    // ✅ YAHAN TAK AAPKA EXISTING CODE - BILKUL WAISA HI

    // ============ ✅ YAHAN SE NAYE METHODS ADD KIYE (AAPKE EXISTING CODE KO KOI FARQ NAHI PADEGA) ============

   public function create()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();
        $disabilities = Disability::where('status', true)->get();
        $studentCount = Student::max('id') ?? 0;
        $admissionCount = StudentAdmission::max('id') ?? 0;

        return view('modules.admissions.create', compact('classes', 'sections', 'disabilities', 'studentCount', 'admissionCount'));
    }

  public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        // Student validation
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'gender' => 'required|string|in:male,female,other',
        'dob' => 'required|date',
        'religion' => 'required|string|max:255',
        'b_form_no' => 'required|string|max:255',
        // 'phone' => 'required|string|max:255',
        'previous_school_details' => 'nullable|string|max:255',
        'previous_class' => 'nullable|string|max:255',
        'cause_of_leaving_school' => 'nullable|string|max:255',
        'disability_id' => 'nullable|exists:disabilities,id',
        'additional_disability' => 'nullable|string|max:255',
        'disability_certificate_no' => 'nullable|string|max:255',
        'student_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        // Guardian validation
        'father_name' => 'required|string|max:255',
        'father_cnic' => 'required|string|max:255',
        'father_occupation' => 'required|string|max:255',
        'mother_name' => 'required|string|max:255',
        'mother_education' => 'required|string|max:255',
        'family_monthly_income' => 'required|string|max:255',
        'guardian_phone' => 'required|string|max:255',
        'emergency_contact' => 'required|string|max:255',
        'complete_address' => 'required|string',
        'postal_address' => 'required|string',

        // Admission validation
        'applied_class_id' => 'required|exists:classes,id',
        'admission_no' => 'required|unique:student_admissions,admission_no',
        'admission_date' => 'required|date',
        'remarks' => 'nullable|string',

        // Fee validation
        'fee_type' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'due_date' => 'required|date',
        'fine_amount' => 'nullable|numeric|min:0',
        'discount_amount' => 'nullable|numeric|min:0',
    ], [
        // Custom error messages
        'first_name.required' => 'The first name field is required.',
        'last_name.required' => 'The last name field is required.',
        'gender.required' => 'Please select a gender.',
        'gender.in' => 'Please select a valid gender.',
        'dob.required' => 'Date of birth is required.',
        'dob.date' => 'Please enter a valid date.',
        'religion.required' => 'Religion field is required.',
        'b_form_no.required' => 'B-Form number is required.',
        // 'phone.required' => 'Phone number is required.',
        'father_name.required' => 'Father\'s name is required.',
        'father_cnic.required' => 'Father\'s CNIC is required.',
        'father_occupation.required' => 'Father\'s occupation is required.',
        'mother_name.required' => 'Mother\'s name is required.',
        'mother_education.required' => 'Mother\'s education is required.',
        'family_monthly_income.required' => 'Family monthly income is required.',
        'guardian_phone.required' => 'Guardian phone number is required.',
        'emergency_contact.required' => 'Emergency contact number is required.',
        'complete_address.required' => 'Complete address is required.',
        'postal_address.required' => 'Postal address is required.',
        'applied_class_id.required' => 'Please select a class.',
        'applied_class_id.exists' => 'Selected class is invalid.',
        'admission_no.required' => 'Admission number is required.',
        'admission_no.unique' => 'This admission number already exists.',
        'admission_date.required' => 'Admission date is required.',
        'admission_date.date' => 'Please enter a valid admission date.',
        'fee_type.required' => 'Please select fee type.',
        'amount.required' => 'Amount is required.',
        'amount.numeric' => 'Amount must be a valid number.',
        'amount.min' => 'Amount cannot be less than 0.',
        'due_date.required' => 'Due date is required.',
        'due_date.date' => 'Please enter a valid due date.',
        'fine_amount.numeric' => 'Fine amount must be a valid number.',
        'fine_amount.min' => 'Fine amount cannot be less than 0.',
        'discount_amount.numeric' => 'Discount amount must be a valid number.',
        'discount_amount.min' => 'Discount amount cannot be less than 0.',
        'phone.max' => 'Phone number cannot exceed 255 characters.',
        'b_form_no.max' => 'B-Form number cannot exceed 255 characters.',
        'previous_class.max' => 'Previous class cannot exceed 255 characters.',
        'disability_id.exists' => 'Selected disability is invalid.',
        'student_image.image' => 'The profile picture must be an image file.',
        'student_image.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg, gif.',
        'student_image.max' => 'The profile picture size must not exceed 2MB.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();

    try {
        // 1. Create Guardian
        $guardian = Guardian::create([
            'father_name' => $request->father_name,
            'father_cnic' => $request->father_cnic,
            'father_occupation' => $request->father_occupation,
            'mother_name' => $request->mother_name,
            'mother_education' => $request->mother_education,
            'family_monthly_income' => $request->family_monthly_income,
            'phone' => $request->guardian_phone ?? $request->phone,
            'emergency_contact' => $request->emergency_contact,
            'complete_address' => $request->complete_address,
            'postal_address' => $request->postal_address,
        ]);

        // 2. Generate Registration Number
        $registrationNo = $request->registration_no;
        if (empty($registrationNo)) {
            $registrationNo = 'REG-' . date('Y') . '-' . str_pad((Student::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        }

        // 3. Create Student
        $student = Student::create([
            'registration_no' => $registrationNo,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'religion' => $request->religion,
            'b_form_no' => $request->b_form_no,
            'disability_id' => $request->disability_id,
            'additional_disability' => $request->additional_disability,
            'disability_certificate_no' => $request->disability_certificate_no,
            'previous_school_details' => $request->previous_school_details,
            'previous_class' => $request->previous_class,
            'cause_of_leaving_school' => $request->cause_of_leaving_school,
            'guardian_id' => $guardian->id,
        ]);

        // 4. Create Student Admission
        $admission = StudentAdmission::create([
            'student_id' => $student->id,
            'school_name_required' => $request->school_name_required,
            'applied_class_id' => $request->applied_class_id,
            'admission_no' => $request->admission_no,
            'class_id' => null,
            'section_id' => null,
            'admission_date' => $request->admission_date,
            'status' => 'pending',
            'remarks' => $request->remarks,
            'approved_by_officer' => null,
            'approved_by_head' => null,
        ]);

        // 5. Create Fee Record
        $fee = Fee::create([
            'student_id' => $student->id,
            'fee_type' => $request->fee_type,
            'amount' => $request->amount,
            'fine_amount' => $request->fine_amount ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        // 6. Save Student Image if uploaded
        if ($request->hasFile('student_image')) {
            $imageFile = $request->file('student_image');
            $originalName = $imageFile->getClientOriginalName();
            $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();

            // Move file to public/uploads/student_images/
            $imageFile->move(public_path('uploads/student_images'), $filename);

            StudentImage::create([
                'student_id' => $student->id,
                'image_name' => $originalName,
                'image_path' => 'uploads/student_images/' . $filename,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Student admission application submitted successfully! Status: Pending',
            'redirect' => route('admissions.index')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}


    // public function show($id)
    // {
    //     $admission = StudentAdmission::with(['student.guardian', 'student.studentImage', 'schoolClass', 'section', 'appliedClass'])->findOrFail($id);
    //     return response()->json($admission);
    // }
public function show($id)
{
    $admission = StudentAdmission::with([
        'student.guardian',
        'student.studentImage',
        'schoolClass',
        'section',
        'appliedClass'
    ])->findOrFail($id);

    // ✅ Image URL prepare karein
    if ($admission->student && $admission->student->studentImage) {
        $imagePath = $admission->student->studentImage->image_path;
        // image_path mein 'uploads/student_images/filename.jpg' store hai
        $admission->student->image_url = asset($imagePath);
    } else {
        $admission->student->image_url = asset('images/default-avatar.png');
    }

    return response()->json($admission);
}
    public function edit($id)
    {
        $admission = StudentAdmission::with(['student.guardian', 'student.studentImage', 'schoolClass', 'section', 'appliedClass', 'fees'])->findOrFail($id);
        $classes = SchoolClass::all();
        $sections = Section::all();
        $disabilities = Disability::where('status', true)->get();

        return view('modules.admissions.Edit', compact('admission', 'classes', 'sections', 'disabilities'));
    }

    public function update(Request $request, $id)
    {
        $admission = StudentAdmission::findOrFail($id);

        if ($request->has('first_name')) {
            $validator = Validator::make($request->all(), [
                // Student validation
                // Student validation
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'gender' => 'required|string|in:male,female,other',
                'dob' => 'required|date',
                'religion' => 'required|string|max:255',
                'b_form_no' => 'required|string|max:255',
                // 'phone' => 'required|string|max:255',
                'previous_school_details' => 'nullable|string|max:255',
                'previous_class' => 'nullable|string|max:255',
                'cause_of_leaving_school' => 'nullable|string|max:255',
                'disability_id' => 'nullable|exists:disabilities,id',
                'additional_disability' => 'nullable|string|max:255',
                'disability_certificate_no' => 'nullable|string|max:255',
                'student_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

                // Guardian validation
                'father_name' => 'required|string|max:255',
                'father_cnic' => 'required|string|max:255',
                'father_occupation' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'mother_education' => 'required|string|max:255',
                'family_monthly_income' => 'required|string|max:255',
                'guardian_phone' => 'required|string|max:255',
                'emergency_contact' => 'required|string|max:255',
                'complete_address' => 'required|string',
                'postal_address' => 'required|string',

                // Admission validation
                'applied_class_id' => 'required|exists:classes,id',
                'admission_no' => 'required|unique:student_admissions,admission_no,' . $id,
                'admission_date' => 'required|date',
                'remarks' => 'nullable|string',

                // Fee validation
                'fee_type' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'due_date' => 'required|date',
                'fine_amount' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
            ], [
                // Custom error messages
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'gender.required' => 'Please select a gender.',
                'gender.in' => 'Please select a valid gender.',
                'dob.required' => 'Date of birth is required.',
                'dob.date' => 'Please enter a valid date.',
                'religion.required' => 'Religion field is required.',
                'b_form_no.required' => 'B-Form number is required.',
                // 'phone.required' => 'Phone number is required.',
                'father_name.required' => 'Father\'s name is required.',
                'father_cnic.required' => 'Father\'s CNIC is required.',
                'father_occupation.required' => 'Father\'s occupation is required.',
                'mother_name.required' => 'Mother\'s name is required.',
                'mother_education.required' => 'Mother\'s education is required.',
                'family_monthly_income.required' => 'Family monthly income is required.',
                'guardian_phone.required' => 'Guardian phone number is required.',
                'emergency_contact.required' => 'Emergency contact number is required.',
                'complete_address.required' => 'Complete address is required.',
                'postal_address.required' => 'Postal address is required.',
                'applied_class_id.required' => 'Please select a class.',
                'applied_class_id.exists' => 'Selected class is invalid.',
                'admission_no.required' => 'Admission number is required.',
                'admission_no.unique' => 'This admission number already exists.',
                'admission_date.required' => 'Admission date is required.',
                'admission_date.date' => 'Please enter a valid admission date.',
                'fee_type.required' => 'Please select fee type.',
                'amount.required' => 'Amount is required.',
                'amount.numeric' => 'Amount must be a valid number.',
                'amount.min' => 'Amount cannot be less than 0.',
                'due_date.required' => 'Due date is required.',
                'due_date.date' => 'Please enter a valid due date.',
                'fine_amount.numeric' => 'Fine amount must be a valid number.',
                'fine_amount.min' => 'Fine amount cannot be less than 0.',
                'discount_amount.numeric' => 'Discount amount must be a valid number.',
                'discount_amount.min' => 'Discount amount cannot be less than 0.',
                'phone.max' => 'Phone number cannot exceed 255 characters.',
                'b_form_no.max' => 'B-Form number cannot exceed 255 characters.',
                'previous_class.max' => 'Previous class cannot exceed 255 characters.',
                'disability_id.exists' => 'Selected disability is invalid.',
                'student_image.image' => 'The profile picture must be an image file.',
                'student_image.mimes' => 'The profile picture must be of type: jpeg, png, jpg, gif.',
                'student_image.max' => 'The profile picture size must not exceed 2MB.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // 1. Update Student
                $student = Student::findOrFail($admission->student_id);
                $student->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'gender' => $request->gender,
                    'dob' => $request->dob,
                    'religion' => $request->religion,
                    'b_form_no' => $request->b_form_no,
                    'phone' => $request->phone,
                    'disability_id' => $request->disability_id,
                    'additional_disability' => $request->additional_disability,
                    'disability_certificate_no' => $request->disability_certificate_no,
                    'previous_school_details' => $request->previous_school_details,
                    'previous_class' => $request->previous_class,
                    'cause_of_leaving_school' => $request->cause_of_leaving_school,
                ]);

                // 2. Update/Create Guardian
                if ($student->guardian_id) {
                    $guardian = Guardian::findOrFail($student->guardian_id);
                    $guardian->update([
                        'father_name' => $request->father_name,
                        'father_cnic' => $request->father_cnic,
                        'father_occupation' => $request->father_occupation,
                        'mother_name' => $request->mother_name,
                        'mother_education' => $request->mother_education,
                        'family_monthly_income' => $request->family_monthly_income,
                        'phone' => $request->guardian_phone ?? $request->phone,
                        'emergency_contact' => $request->emergency_contact,
                        'complete_address' => $request->complete_address,
                        'postal_address' => $request->postal_address,
                    ]);
                } else {
                    $guardian = Guardian::create([
                        'father_name' => $request->father_name,
                        'father_cnic' => $request->father_cnic,
                        'father_occupation' => $request->father_occupation,
                        'mother_name' => $request->mother_name,
                        'mother_education' => $request->mother_education,
                        'family_monthly_income' => $request->family_monthly_income,
                        'phone' => $request->guardian_phone ?? $request->phone,
                        'emergency_contact' => $request->emergency_contact,
                        'complete_address' => $request->complete_address,
                        'postal_address' => $request->postal_address,
                    ]);
                    $student->update(['guardian_id' => $guardian->id]);
                }

                // 3. Update Admission
                $admission->update([
                    'applied_class_id' => $request->applied_class_id,
                    'admission_no' => $request->admission_no,
                    'admission_date' => $request->admission_date,
                    'remarks' => $request->remarks,
                ]);

                // 4. Update/Create Fee
                $fee = Fee::where('student_id', $student->id)->first();
                if ($fee) {
                    $fee->update([
                        'fee_type' => $request->fee_type,
                        'amount' => $request->amount,
                        'fine_amount' => $request->fine_amount ?? 0,
                        'discount_amount' => $request->discount_amount ?? 0,
                        'due_date' => $request->due_date,
                    ]);
                } else {
                    Fee::create([
                        'student_id' => $student->id,
                        'fee_type' => $request->fee_type,
                        'amount' => $request->amount,
                        'fine_amount' => $request->fine_amount ?? 0,
                        'discount_amount' => $request->discount_amount ?? 0,
                        'due_date' => $request->due_date,
                        'status' => 'pending',
                    ]);
                }

                // 5. Save / Update Student Image
                if ($request->hasFile('student_image')) {
                    $imageFile = $request->file('student_image');
                    $originalName = $imageFile->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                    $imageFile->move(public_path('uploads/student_images'), $filename);

                    $existingImage = StudentImage::where('student_id', $student->id)->first();
                    if ($existingImage) {
                        // Delete old file
                        if (file_exists(public_path($existingImage->image_path))) {
                            unlink(public_path($existingImage->image_path));
                        }
                        $existingImage->update([
                            'image_name' => $originalName,
                            'image_path' => 'uploads/student_images/' . $filename,
                        ]);
                    } else {
                        StudentImage::create([
                            'student_id' => $student->id,
                            'image_name' => $originalName,
                            'image_path' => 'uploads/student_images/' . $filename,
                        ]);
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Student admission details updated successfully!',
                    'redirect' => route('admissions.index')
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_no' => 'nullable|string',
            'approved_by_officer' => 'nullable|string',
            'approved_by_head' => 'nullable|string',
            'admission_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $updateData = [
                'status' => $request->status,
                'remarks' => $request->remarks,
                'admission_date' => $request->admission_date,
                'approved_by_officer' => $request->approved_by_officer,
                'approved_by_head' => $request->approved_by_head,
            ];

            if ($request->status == 'approved') {
                $updateData['class_id'] = $request->class_id;
                $updateData['section_id'] = $request->section_id;

                if (empty($request->roll_no) && $request->section_id) {
                    $section = Section::find($request->section_id);
                    if ($section) {
                        $currentCount = $section->roll_no ?? 0;
                        $updateData['roll_no'] = str_pad($currentCount + 1, 3, '0', STR_PAD_LEFT);
                        $section->roll_no = $currentCount + 1;
                        $section->save();
                    }
                } else {
                    $updateData['roll_no'] = $request->roll_no;
                }
            } else {
                $updateData['class_id'] = null;
                $updateData['section_id'] = null;
                $updateData['roll_no'] = null;
            }

            $admission->update($updateData);

            DB::commit();

            $statusMessage = $request->status == 'approved' ? 'approved' : ($request->status == 'rejected' ? 'rejected' : 'updated');

            return response()->json([
                'success' => true,
                'message' => "Admission {$statusMessage} successfully!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $admission = StudentAdmission::findOrFail($id);
        $student = Student::find($admission->student_id);

        DB::beginTransaction();

        try {
            if ($student && $student->guardian_id) {
                Guardian::where('id', $student->guardian_id)->delete();
            }
            if ($student) {
                $student->delete();
            }
            $admission->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Admission deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCounts()
    {
        return response()->json([
            'pending' => StudentAdmission::where('status', 'pending')->count(),
            'approved' => StudentAdmission::where('status', 'approved')->count(),
            'rejected' => StudentAdmission::where('status', 'rejected')->count(),
        ]);
    }
}
