<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Card &mdash; {{ $student->first_name }} {{ $student->last_name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:  #1e3a8a;
            --accent:   #3b82f6;
            --border:   #cbd5e1;
            --bg-light: #f8fafc;
            --pass:     #16a34a;
            --fail:     #dc2626;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #e2e8f0;
            color: #1f2937;
            margin: 0;
            padding: 24px 16px;
        }

        .action-bar {
            max-width: 860px;
            margin: 0 auto 16px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 22px;
            border-radius: 6px;
            border: none;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-print  { background: var(--primary); color: #fff; }
        .btn-close2 { background: #64748b;        color: #fff; }

        .result-card {
            background: #fff;
            max-width: 860px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,.12);
            border-top: 10px solid var(--primary);
            overflow: hidden;
        }

        .card-inner { padding: 36px 40px; }

        .school-header {
            text-align: center;
            padding-bottom: 22px;
            margin-bottom: 26px;
            border-bottom: 2px solid var(--primary);
        }

        .school-name {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--primary);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .exam-badge {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 20px;
            margin-top: 8px;
        }

        .exam-date { color: #64748b; font-size: 0.85rem; margin-top: 6px; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 30px;
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 18px 22px;
            margin-bottom: 28px;
        }

        .info-row { display: flex; align-items: baseline; gap: 8px; font-size: 0.95rem; }
        .info-key  { font-weight: 700; color: var(--primary); min-width: 130px; flex-shrink: 0; }
        .info-val  { font-weight: 600; color: #334155; }

        .status-badge {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
        }
        .badge-pass { background: #dcfce7; color: var(--pass); }
        .badge-fail { background: #fee2e2; color: var(--fail); }
        .badge-na   { background: #f1f5f9; color: #64748b; }

        .section-title {
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--primary);
            border-left: 4px solid var(--primary);
            padding-left: 10px;
            margin-bottom: 12px;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
            font-size: 0.93rem;
        }

        .marks-table th {
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 11px 14px;
            text-align: center;
        }

        .marks-table th:first-child { text-align: left; }

        .marks-table td {
            border: 1px solid var(--border);
            padding: 10px 14px;
            text-align: center;
        }

        .marks-table td:first-child { text-align: left; font-weight: 600; color: #334155; }

        .marks-table tbody tr:nth-child(even) td { background: var(--bg-light); }

        .text-pass { color: var(--pass); font-weight: 700; }
        .text-fail { color: var(--fail); font-weight: 700; }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }

        .summary-box {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 14px 10px;
            text-align: center;
        }

        .summary-box .s-label {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .summary-box .s-val {
            font-size: 1.55rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .summary-box.highlight { background: var(--primary); }
        .summary-box.highlight .s-label,
        .summary-box.highlight .s-val { color: #fff; }

        .remarks-box {
            background: #fef9c3;
            border-left: 4px solid #eab308;
            padding: 13px 16px;
            border-radius: 4px;
            margin-bottom: 28px;
            font-size: 0.93rem;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 20px;
        }

        .sig-line { text-align: center; width: 180px; }
        .sig-line .sig-bar { height: 1px; background: #94a3b8; margin-bottom: 8px; }
        .sig-line span { font-size: 0.85rem; font-weight: 600; color: #64748b; }

        @media print {
            body { background: #fff; padding: 0; }
            .action-bar { display: none; }
            .result-card { box-shadow: none; border-radius: 0; max-width: 100%; }
            .marks-table th { background: var(--primary) !important; color: #fff !important; }
            .summary-box.highlight { background: var(--primary) !important; }
            .summary-box.highlight .s-label,
            .summary-box.highlight .s-val { color: #fff !important; }
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button onclick="window.history.back()" class="btn-action btn-close2">&#8592; Back</button>
        <button onclick="window.print()" class="btn-action btn-print">&#128424; Print Result Card</button>
    </div>

    <div class="result-card">
        <div class="card-inner">

            {{-- Header --}}
            <div class="school-header">
                <h1 class="school-name">{{ config('app.name', 'School Management System') }}</h1>
                <div class="exam-badge">{{ $exam->exam_name }} &mdash; {{ $exam->academic_year }}</div>
                @if($exam->exam_date)
                    <div class="exam-date">Examination Date: {{ $exam->exam_date->format('F d, Y') }}</div>
                @endif
            </div>

            {{-- Student Info --}}
            <div class="section-title">Student Information</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-key">Student Name:</span>
                    <span class="info-val">{{ $student->first_name }} {{ $student->last_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Registration No:</span>
                    <span class="info-val">{{ $student->registration_no }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Father's Name:</span>
                    <span class="info-val">{{ $student->guardian ? $student->guardian->father_name : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Roll No:</span>
                    <span class="info-val">{{ $student->admission ? $student->admission->admission_no : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Class:</span>
                    <span class="info-val">{{ $className }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Result Status:</span>
                    <span class="info-val">
                        @php $fs = $finalResult ? $finalResult->final_status : null; @endphp
                        @if($fs == 'pass')
                            <span class="status-badge badge-pass">&#10003; Pass</span>
                        @elseif($fs == 'fail')
                            <span class="status-badge badge-fail">&#10007; Fail</span>
                        @else
                            <span class="status-badge badge-na">Pending</span>
                        @endif
                    </span>
                </div>
            </div>

            {{-- Marks Table --}}
            <div class="section-title">Subject-Wise Marks</div>
            <table class="marks-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Total Marks</th>
                        <th>Obtained Marks</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($subjectResults->count() > 0)
                        @foreach($subjectResults as $sr)
                            <tr>
                                <td>{{ $sr->subject ? $sr->subject->subject_name : 'N/A' }}</td>
                                <td>{{ $sr->total_marks }}</td>
                                <td><strong>{{ $sr->obtained_marks }}</strong></td>
                                <td>{{ $sr->grade ? $sr->grade : '&mdash;' }}</td>
                                <td>
                                    <span class="{{ $sr->status == 'pass' ? 'text-pass' : 'text-fail' }}">
                                        {{ strtoupper($sr->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        @php
                            $totalM    = $finalResult ? $finalResult->grand_total_marks    : $calculatedTotal;
                            $obtainedM = $finalResult ? $finalResult->grand_obtained_marks : $calculatedObtained;
                        @endphp
                        <tr style="background:#eff6ff; font-weight:700;">
                            <td style="color:var(--primary);">TOTAL</td>
                            <td style="color:var(--primary);">{{ $totalM }}</td>
                            <td style="color:var(--primary);">{{ $obtainedM }}</td>
                            <td>&mdash;</td>
                            <td>&mdash;</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px; color:#94a3b8;">
                                No subject marks have been entered for this student yet.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{-- Summary --}}
            <div class="section-title">Result Summary</div>
            @php
                $grandTotal    = $finalResult ? $finalResult->grand_total_marks    : $calculatedTotal;
                $grandObtained = $finalResult ? $finalResult->grand_obtained_marks : $calculatedObtained;
                $pct           = $finalResult ? $finalResult->percentage            : $calculatedPct;
                $grade         = $finalResult ? ($finalResult->final_grade ? $finalResult->final_grade : '&mdash;') : '&mdash;';
                $position      = $finalResult ? $finalResult->position              : null;
            @endphp
            <div class="summary-grid">
                <div class="summary-box">
                    <div class="s-label">Grand Total</div>
                    <div class="s-val">{{ $grandTotal }}</div>
                </div>
                <div class="summary-box">
                    <div class="s-label">Obtained</div>
                    <div class="s-val">{{ $grandObtained }}</div>
                </div>
                <div class="summary-box highlight">
                    <div class="s-label">Percentage</div>
                    <div class="s-val">{{ $pct }}%</div>
                </div>
                <div class="summary-box">
                    <div class="s-label">Final Grade</div>
                    <div class="s-val">{!! $grade !!}</div>
                </div>
                <div class="summary-box">
                    <div class="s-label">Position</div>
                    <div class="s-val">
                        @if($position)
                            @php
                                $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
                                $sfx  = (($position % 100) >= 11 && ($position % 100) <= 13) ? 'th' : $ends[$position % 10];
                            @endphp
                            {{ $position }}{{ $sfx }}
                        @else
                            &mdash;
                        @endif
                    </div>
                </div>
            </div>

            {{-- Remarks --}}
            @if($finalResult && $finalResult->remarks)
                <div class="remarks-box">
                    <strong>Remarks:</strong> {{ $finalResult->remarks }}
                </div>
            @endif

            {{-- Signatures --}}
            <div class="signatures">
                <div class="sig-line">
                    <div class="sig-bar"></div>
                    <span>Class Teacher</span>
                </div>
                <div class="sig-line">
                    <div class="sig-bar"></div>
                    <span>Controller of Examinations</span>
                </div>
                <div class="sig-line">
                    <div class="sig-bar"></div>
                    <span>Principal</span>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
