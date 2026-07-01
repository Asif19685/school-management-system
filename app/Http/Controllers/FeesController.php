<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FeesController extends Controller
{
    public function index(): View
    {
        return view('modules.fees.index');
    }

    public function getStudentFees($studentId)
    {
        $student = Student::with(['fees.payments', 'studentImage'])->findOrFail($studentId);

        $today = Carbon::today();

        $fees = $student->fees->map(function ($fee) use ($today) {
            $baseAmount = parseFloatOrZero($fee->amount);
            $discount = parseFloatOrZero($fee->discount_amount);
            $fine = parseFloatOrZero($fee->fine_amount);

            // ✅ Calculate Total Due
            $totalDue = ($baseAmount + $fine) - $discount;

            // ✅ Calculate Total Paid
            $totalPaid = parseFloatOrZero($fee->payments->sum('paid_amount'));

            // ✅ Calculate Remaining
            $remaining = max(0, $totalDue - $totalPaid);

            // Check if fee is late
            $dueDate = $fee->due_date ? Carbon::parse($fee->due_date) : null;
            $isLate = ($dueDate && $today->greaterThan($dueDate) && $fee->status !== 'paid');

            // ✅ Determine Fee Status
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
                'id'              => $fee->id,
                'fee_type'        => $fee->fee_type,
                'amount'          => $baseAmount,
                'fine_amount'     => $fine,
                'discount_amount' => $discount,
                'total_due'       => $totalDue,
                'total_paid'      => $totalPaid,
                'remaining'       => $remaining,
                'due_date'        => $dueDate ? $dueDate->format('Y-m-d') : null,
                'due_date_formatted' => $dueDate ? $dueDate->format('d M, Y') : 'N/A',
                'status'          => $status,
                'is_late'         => $isLate,
            ];
        });

        return response()->json([
            'success'  => true,
            'student'  => [
                'id'         => $student->id,
                'name'       => $student->full_name,
                'image_url'  => $student->studentImage
                    ? asset($student->studentImage->image_path)
                    : asset('images/default-avatar.png'),
            ],
            'fees'     => $fees,
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

            // ✅ Update fine_amount
            $fee->fine_amount = parseFloatOrZero($request->fine_amount);
            $fee->save();

            // ✅ Create fee payment transaction
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

            // ✅ Refresh & update parent fee status
            $fee->refresh();
            $totalPaid = parseFloatOrZero($fee->payments()->sum('paid_amount'));
            $totalDue  = (parseFloatOrZero($fee->amount) + parseFloatOrZero($fee->fine_amount)) - parseFloatOrZero($fee->discount_amount);

            // ✅ Calculate Remaining
            $remaining = max(0, $totalDue - $totalPaid);

            // ✅ Update Fee Status
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

    // ── Placeholder resources ──────────────────────────────────────────
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
    public function getFeesData() {}
}

if (!function_exists('parseFloatOrZero')) {
    function parseFloatOrZero($value) {
        return is_numeric($value) ? (float)$value : 0.0;
    }
}
