<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAdmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FeesController extends Controller
{
    public function index(): View
    {
        return view('modules.fees.index');
    }

    /**
     * Server-side DataTable: approved students with fee status & actions.
     */
    public function getFeesData(Request $request)
    {
        $query = StudentAdmission::with([
            'student.guardian',
            'student.studentImage',
            'student.fees',
            'schoolClass',
            'section',
        ])
            ->where('status', 'approved')
            ->select('student_admissions.*');

        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];

            $query->where(function ($q) use ($searchValue) {
                $q->where('admission_no', 'LIKE', "%{$searchValue}%")
                    ->orWhereHas('student', function ($sq) use ($searchValue) {
                        $sq->where('first_name', 'LIKE', "%{$searchValue}%")
                            ->orWhere('last_name', 'LIKE', "%{$searchValue}%")
                            ->orWhere('b_form_no', 'LIKE', "%{$searchValue}%");
                    })
                    ->orWhereHas('schoolClass', fn ($sq) => $sq->where('class_name', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('section', fn ($sq) => $sq->where('section_name', 'LIKE', "%{$searchValue}%"));
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->filterColumn('admission_no', fn ($q, $kw) => $q->where('admission_no', 'LIKE', "%{$kw}%"))
            ->filterColumn('student_name', function ($q, $kw) {
                $q->whereHas('student', fn ($sq) =>
                    $sq->where('first_name', 'LIKE', "%{$kw}%")
                        ->orWhere('last_name', 'LIKE', "%{$kw}%")
                );
            })
            ->filterColumn('class', fn ($q, $kw) => $q->whereHas('schoolClass', fn ($sq) => $sq->where('class_name', 'LIKE', "%{$kw}%")))
            ->filterColumn('section', fn ($q, $kw) => $q->whereHas('section', fn ($sq) => $sq->where('section_name', 'LIKE', "%{$kw}%")))
            ->addColumn('student_image', function ($row) {
                if ($row->student && $row->student->studentImage) {
                    $url = asset($row->student->studentImage->image_path);
                } else {
                    $url = asset('images/default-avatar.png');
                }
                return '<img src="' . $url . '" class="rounded-circle" width="40" height="40" style="object-fit:cover;">';
            })
            ->addColumn('student_name', fn ($row) => $row->student
                ? trim(($row->student->first_name ?? '') . ' ' . ($row->student->last_name ?? ''))
                : 'N/A'
            )
            ->addColumn('admission_no', fn ($row) => $row->admission_no ?? 'N/A')
            ->addColumn('class', fn ($row) => $row->schoolClass->class_name ?? 'N/A')
            ->addColumn('section', fn ($row) => $row->section->section_name ?? 'N/A')
            ->addColumn('roll_no', fn ($row) => $row->roll_no ?? 'N/A')
            ->addColumn('fee_status', function ($row) {
                if (!$row->student) {
                    return '<span class="badge bg-secondary">N/A</span>';
                }

                $currentMonth = now()->format('F Y');
                $fee = $row->student->fees
                    ->first(fn ($f) => str_contains($f->fee_type ?? '', $currentMonth));

                if (!$fee) {
                    return '<span class="badge bg-secondary">No Fee</span>';
                }

                if ($fee->status === 'paid') {
                    return '<span class="badge bg-success">Paid</span>';
                }

                if ($fee->status === 'pending') {
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                }

                if ($fee->status === 'partial') {
                    return '<span class="badge bg-info text-dark">Partial</span>';
                }

                if ($fee->status === 'overdue') {
                    return '<span class="badge bg-danger">Overdue</span>';
                }

                return '<span class="badge bg-secondary">' . ucfirst($fee->status) . '</span>';
            })
            ->addColumn('actions', function ($row) {
                $viewBtn = '<button class="btn btn-sm btn-outline-info view-student-btn me-1"
                            data-id="' . $row->id . '"
                            title="View Profile">
                            <i class="bi bi-eye"></i> View Profile
                        </button>';
                $collectBtn = '<button class="btn btn-sm btn-outline-success submit-fee-btn"
                            data-student-id="' . ($row->student->id ?? '') . '"
                            title="Submit Fee">
                            <i class="bi bi-cash-coin"></i> Collect Fee
                        </button>';
                return '<div class="d-flex">' . $viewBtn . $collectBtn . '</div>';
            })
            ->rawColumns(['student_image', 'fee_status', 'actions'])
            ->make(true);
    }

    public function getStudentFees($studentId)
    {
        $student = Student::with(['fees.payments', 'studentImage'])->findOrFail($studentId);

        $today = Carbon::today();

        $fees = $student->fees->map(function ($fee) use ($today) {
            $baseAmount = parseFloatOrZero($fee->amount);
            $discount = parseFloatOrZero($fee->discount_amount);
            $fine = parseFloatOrZero($fee->fine_amount);

            $totalDue = ($baseAmount + $fine) - $discount;
            $totalPaid = parseFloatOrZero($fee->payments->sum('paid_amount'));
            $remaining = max(0, $totalDue - $totalPaid);

            $dueDate = $fee->due_date ? Carbon::parse($fee->due_date) : null;
            $isLate = ($dueDate && $today->greaterThan($dueDate) && $fee->status !== 'paid');

            $status = $fee->status;
            if ($remaining <= 0) {
                $status = 'paid';
            } elseif ($totalPaid > 0) {
                $status = 'partial';
            } elseif ($isLate) {
                $status = 'overdue';
            } else {
                $status = 'pending';
            }

            return [
                'id'                 => $fee->id,
                'fee_type'           => $fee->fee_type,
                'amount'             => $baseAmount,
                'fine_amount'        => $fine,
                'discount_amount'    => $discount,
                'total_due'          => $totalDue,
                'total_paid'         => $totalPaid,
                'remaining'          => $remaining,
                'due_date'           => $dueDate ? $dueDate->format('Y-m-d') : null,
                'due_date_formatted' => $dueDate ? $dueDate->format('d M, Y') : 'N/A',
                'status'             => $status,
                'is_late'            => $isLate,
            ];
        });

        return response()->json([
            'success' => true,
            'student' => [
                'id'        => $student->id,
                'name'      => $student->full_name,
                'image_url' => $student->studentImage
                    ? asset($student->studentImage->image_path)
                    : asset('images/default-avatar.png'),
            ],
            'fees'    => $fees,
        ]);
    }

    public function submitFee(Request $request, $studentId)
    {
        $validator = Validator::make($request->all(), [
            'fee_id'         => 'required|exists:fees,id',
            'paid_amount'    => 'required|numeric|min:0.01',
            'fine_amount'    => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:cash,bank_transfer,cheque,online',
            'payment_date'   => 'required|date',
            'receipt_no'     => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $fee = Fee::where('id', $request->fee_id)
                ->where('student_id', $studentId)
                ->firstOrFail();

            $fee->fine_amount = parseFloatOrZero($request->fine_amount);
            $fee->save();

            $paidInput = parseFloatOrZero($request->paid_amount);
            if ($paidInput > 0) {
                FeePayment::create([
                    'fee_id'         => $fee->id,
                    'paid_amount'    => $paidInput,
                    'payment_date'   => $request->payment_date,
                    'payment_method' => $request->payment_method,
                    'receipt_no'     => $request->receipt_no,
                ]);
            }

            $fee->refresh();
            $totalPaid = parseFloatOrZero($fee->payments()->sum('paid_amount'));
            $totalDue = (parseFloatOrZero($fee->amount) + parseFloatOrZero($fee->fine_amount)) - parseFloatOrZero($fee->discount_amount);
            $remaining = max(0, $totalDue - $totalPaid);

            if ($remaining <= 0) {
                $fee->status = 'paid';
            } elseif ($totalPaid > 0) {
                $fee->status = 'partial';
            } else {
                $fee->status = 'pending';
            }
            $fee->save();

            DB::commit();

            $msg = $paidInput > 0
                ? 'Fee payment of ' . number_format($paidInput, 2) . ' recorded successfully!'
                : 'Fee record fine amount updated successfully!';

            return response()->json([
                'success'    => true,
                'message'    => $msg,
                'total_paid' => $totalPaid,
                'total_due'  => $totalDue,
                'remaining'  => $remaining,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showStudentDetail($id)
    {
        $admission = StudentAdmission::with([
            'student.guardian',
            'student.studentImage',
            'student.fees.payments',
            'schoolClass',
            'section',
            'appliedClass',
        ])->where('status', 'approved')->findOrFail($id);

        // Image URL
        if ($admission->student && $admission->student->studentImage) {
            $admission->student->image_url = asset($admission->student->studentImage->image_path);
        } else {
            $admission->student->image_url = asset('images/default-avatar.png');
        }

        // Calculate and format fees
        $today = now();
        $fees = collect();
        if ($admission->student && $admission->student->fees) {
            $fees = $admission->student->fees->map(function ($fee) use ($today) {
                $baseAmount = parseFloatOrZero($fee->amount);
                $discount = parseFloatOrZero($fee->discount_amount);
                $fine = parseFloatOrZero($fee->fine_amount);

                $totalDue = ($baseAmount + $fine) - $discount;
                $totalPaid = parseFloatOrZero($fee->payments->sum('paid_amount'));
                $remaining = max(0, $totalDue - $totalPaid);

                $dueDate = $fee->due_date ? \Carbon\Carbon::parse($fee->due_date) : null;
                $isLate = ($dueDate && $today->greaterThan($dueDate) && $fee->status !== 'paid');

                $status = $fee->status;
                if ($remaining <= 0) {
                    $status = 'paid';
                } elseif ($totalPaid > 0) {
                    $status = 'partial';
                } elseif ($isLate) {
                    $status = 'overdue';
                } else {
                    $status = 'pending';
                }

                return [
                    'fee_type'           => $fee->fee_type,
                    'amount'             => $baseAmount,
                    'fine_amount'        => $fine,
                    'discount_amount'    => $discount,
                    'total_due'          => $totalDue,
                    'total_paid'         => $totalPaid,
                    'remaining'          => $remaining,
                    'status'             => $status,
                ];
            });
        }

        return response()->json([
            'admission' => $admission,
            'fees' => $fees
        ]);
    }

    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}

if (!function_exists('parseFloatOrZero')) {
    function parseFloatOrZero($value)
    {
        return is_numeric($value) ? (float) $value : 0.0;
    }
}
