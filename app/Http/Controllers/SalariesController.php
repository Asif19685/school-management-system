<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Salary;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalariesController extends Controller
{
    public function index(): View
    {
        // Get all unique month_year strings from database to populate filter
        $availableMonths = Salary::distinct()->orderBy('month_year', 'desc')->pluck('month_year');
        
        return view('modules.salaries.index', compact('availableMonths'));
    }

    /**
     * Server-Side DataTable for Teacher Salaries
     */
    public function getSalariesData(Request $request)
    {
        $monthYear = $request->input('month_year', Carbon::now()->format('m-Y'));

        $query = Salary::with('teacher')
            ->where('month_year', $monthYear)
            ->select('salaries.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('teacher_code', fn($row) => $row->teacher->teacher_code ?? 'N/A')
            ->addColumn('teacher_name', fn($row) => $row->teacher->name ?? 'N/A')
            ->addColumn('attendance_summary', function ($row) {
                return '<div class="small fw-semibold text-start">'
                    . '<span class="text-success me-2">P: ' . $row->total_present . '</span>'
                    . '<span class="text-danger me-2">A: ' . $row->total_absent . '</span>'
                    . '<span class="text-info">H: ' . $row->total_half_days . '</span>'
                    . '</div>';
            })
            ->addColumn('base_salary_formatted', fn($row) => 'Rs. ' . number_format($row->base_salary, 2))
            ->addColumn('deductions_formatted', fn($row) => 'Rs. ' . number_format($row->deductions, 2))
            ->addColumn('net_salary_formatted', fn($row) => 'Rs. ' . number_format($row->net_salary, 2))
            ->addColumn('status', function ($row) {
                if ($row->status === 'Paid') {
                    return '<span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Paid</span>';
                }
                return '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-clock me-1"></i>Pending</span>';
            })
            ->addColumn('actions', function ($row) {
                $payBtn = '';
                if ($row->status !== 'Paid') {
                    $payBtn = '<button class="btn btn-sm btn-success pay-salary-btn me-1" data-id="' . $row->id . '" title="Pay Now"><i class="bi bi-cash"></i> Pay</button>';
                }
                
                $editBtn = '<button class="btn btn-sm btn-outline-primary edit-salary-btn me-1" data-id="' . $row->id . '" '
                    . 'data-teacher="' . htmlspecialchars($row->teacher->name ?? 'N/A') . '" '
                    . 'data-base="' . $row->base_salary . '" '
                    . 'data-deductions="' . $row->deductions . '" '
                    . 'data-net="' . $row->net_salary . '" '
                    . 'data-status="' . $row->status . '" '
                    . 'title="Edit / Recalculate"><i class="bi bi-pencil"></i></button>';

                $printBtn = '<button class="btn btn-sm btn-outline-secondary print-slip-btn" data-id="' . $row->id . '" '
                    . 'data-teacher="' . htmlspecialchars($row->teacher->name ?? 'N/A') . '" '
                    . 'data-code="' . htmlspecialchars($row->teacher->teacher_code ?? 'N/A') . '" '
                    . 'data-month="' . $row->month_year . '" '
                    . 'data-base="' . number_format($row->base_salary, 2) . '" '
                    . 'data-deductions="' . number_format($row->deductions, 2) . '" '
                    . 'data-net="' . number_format($row->net_salary, 2) . '" '
                    . 'data-status="' . $row->status . '" '
                    . 'data-present="' . $row->total_present . '" '
                    . 'data-absent="' . $row->total_absent . '" '
                    . 'data-half="' . $row->total_half_days . '" '
                    . 'title="Print Payslip"><i class="bi bi-printer"></i> Slip</button>';

                return '<div class="d-flex align-items-center justify-content-center">' . $payBtn . $editBtn . $printBtn . '</div>';
            })
            ->rawColumns(['attendance_summary', 'status', 'actions'])
            ->make(true);
    }

    /**
     * Generate payroll for all teachers for selected Month/Year
     */
    public function generateMonthlySalary(Request $request)
    {
        $request->validate([
            'month_year' => 'required|string', // Format expected: "YYYY-MM" from date input
        ]);

        // Parse YYYY-MM into MM-YYYY
        try {
            $parsedDate = Carbon::parse($request->input('month_year') . '-01');
            $monthYearStr = $parsedDate->format('m-Y');
            $year = $parsedDate->year;
            $month = $parsedDate->month;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid month selected.',
            ], 422);
        }

        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No teachers found to generate salaries for.',
            ], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($teachers as $teacher) {
                // Fetch attendance statistics for this teacher for the selected month/year
                $attendances = TeacherAttendance::where('teacher_id', $teacher->id)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->get();

                $totalPresent = $attendances->where('status', 'Present')->count();
                $totalAbsent = $attendances->where('status', 'Absent')->count();
                $totalHalfDays = $attendances->where('status', 'Half-Day')->count();

                $baseSalary = $teacher->salary ?: 0.00;

                // Default deduction calculation:
                // Base deduction on daily rate (base salary divided by 30 days)
                // Absent = full daily rate deduction, Half-day = half daily rate deduction
                $dailyRate = $baseSalary / 30;
                $deductions = ($dailyRate * $totalAbsent) + (($dailyRate / 2) * $totalHalfDays);
                $deductions = round($deductions, 2);

                $netSalary = $baseSalary - $deductions;
                if ($netSalary < 0) {
                    $netSalary = 0.00;
                }

                // Find or update salary record. Don't overwrite paid status unless forced
                $existingSalary = Salary::where('teacher_id', $teacher->id)
                    ->where('month_year', $monthYearStr)
                    ->first();

                $status = $existingSalary ? $existingSalary->status : 'Pending';

                Salary::updateOrCreate(
                    [
                        'teacher_id' => $teacher->id,
                        'month_year' => $monthYearStr,
                    ],
                    [
                        'base_salary'     => $baseSalary,
                        'total_present'   => $totalPresent,
                        'total_absent'    => $totalAbsent,
                        'total_half_days' => $totalHalfDays,
                        'deductions'      => $deductions,
                        'net_salary'      => $netSalary,
                        'status'          => $status,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Payroll successfully generated/updated for {$monthYearStr}.",
                'month_year' => $monthYearStr
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error generating payroll: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update salary parameters manually
     */
    public function update(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);

        $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'deductions'  => 'required|numeric|min:0',
            'net_salary'  => 'required|numeric|min:0',
            'status'      => 'required|in:Pending,Paid',
        ]);

        $salary->update([
            'base_salary' => $request->base_salary,
            'deductions'  => $request->deductions,
            'net_salary'  => $request->net_salary,
            'status'      => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Salary record updated successfully.'
        ]);
    }

    /**
     * Quick disburse (pay) salary action
     */
    public function paySalary($id)
    {
        $salary = Salary::findOrFail($id);
        
        $salary->update(['status' => 'Paid']);

        return response()->json([
            'success' => true,
            'message' => 'Salary marked as Paid.'
        ]);
    }
}
