@extends('layouts.master')

@section('title', 'Marks Entry — {{ $exam->exam_name }}')
@section('header-title', 'Marks Entry')

@section('styles')
<style>
    .marks-input {
        width: 80px;
        text-align: center;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: border-color 0.2s;
    }
    .marks-input:focus {
        border-color: #4f46e5;
        outline: none;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
    }
    .marks-input.is-over { border-color: #dc2626; background: #fef2f2; }
    .marks-input.is-pass { border-color: #16a34a; background: #f0fdf4; }
    .marks-input.is-fail { border-color: #ea580c; background: #fff7ed; }
    .exam-info-badge {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: white;
        border-radius: 12px;
        padding: 16px 20px;
    }
    .sticky-header th {
        position: sticky;
        top: 0;
        background: #1e293b;
        color: white;
        z-index: 10;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 10px;
        white-space: nowrap;
    }
    .student-name-cell {
        position: sticky;
        left: 0;
        background: white;
        z-index: 5;
        border-right: 2px solid #e2e8f0;
        min-width: 160px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .table-marks td { padding: 8px 10px; vertical-align: middle; }
    .total-chip {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
    }
    .no-subject-warn {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }
    .save-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: white;
        border-top: 2px solid #e2e8f0;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 1000;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('exams.index') }}" class="text-decoration-none">Exams</a></li>
                <li class="breadcrumb-item active">Marks Entry</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('exams.results', $exam->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-bar-chart-line me-1"></i> Results Dekhen
            </a>
            <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Wapis
            </a>
        </div>
    </div>
</div>

{{-- Exam Info --}}
<div class="exam-info-badge mb-4 d-flex flex-wrap gap-4 align-items-center">
    <div>
        <div style="font-size:0.75rem; opacity:0.7;">Exam</div>
        <div style="font-size:1.1rem; font-weight:700;">{{ $exam->exam_name }}</div>
    </div>
    <div>
        <div style="font-size:0.75rem; opacity:0.7;">Class</div>
        <div style="font-size:1.1rem; font-weight:700;">{{ $exam->schoolClass?->class_name ?? '—' }}</div>
    </div>
    <div>
        <div style="font-size:0.75rem; opacity:0.7;">Date</div>
        <div style="font-size:1.1rem; font-weight:700;">{{ $exam->exam_date?->format('d M Y') ?? '—' }}</div>
    </div>
    <div class="ms-auto">
        <span class="badge bg-white text-dark">
            <i class="bi bi-people-fill me-1"></i> {{ $studentAdmissions->count() }} Students
        </span>
        <span class="badge bg-white text-dark ms-2">
            <i class="bi bi-journal-text me-1"></i> {{ $subjects->count() }} Subjects
        </span>
    </div>
</div>

{{-- Alert --}}
<div id="saveAlert"></div>

@if($subjects->isEmpty())
    <div class="no-subject-warn">
        <i class="bi bi-exclamation-triangle-fill text-warning fs-1 mb-3"></i>
        <h5 class="fw-bold">Is class mein koi Subject nahi!</h5>
        <p class="text-muted mb-3">Marks enter karne se pehle pehle subjects add karein.</p>
        <a href="{{ route('subjects.index') }}" class="btn btn-warning btn-sm px-4">
            <i class="bi bi-plus-circle me-1"></i> Subjects Add Karein
        </a>
    </div>
@elseif($studentAdmissions->isEmpty())
    <div class="no-subject-warn">
        <i class="bi bi-people fs-1 text-warning mb-3"></i>
        <h5 class="fw-bold">Is class mein koi student admit nahi!</h5>
        <p class="text-muted">Pehle students ka admission karein.</p>
    </div>
@else
    {{-- Marks Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: calc(100vh - 320px); overflow: auto;">
                <table class="table table-bordered table-marks mb-0">
                    <thead class="sticky-header">
                        <tr>
                            <th class="student-name-cell" style="background:#1e293b;">#</th>
                            <th class="student-name-cell" style="background:#1e293b; min-width:180px;">Student Ka Naam</th>
                            @php $overallMax = 0; @endphp
                            @foreach($subjects as $subject)
                                @php $overallMax += $subject->total_marks; @endphp
                                <th style="min-width:100px; text-align:center;">
                                    {{ $subject->subject_name }}
                                    <br><small style="opacity:0.7; font-size:0.7rem;">(Max: {{ $subject->total_marks }} | Pass: {{ $subject->pass_marks }})</small>
                                </th>
                            @endforeach
                            <th style="text-align:center;">Total<br><small>({{ $overallMax }})</small></th>
                            <th style="text-align:center;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentAdmissions as $idx => $admission)
                            @php
                                $student = $admission->student;
                            @endphp
                            @if($student)
                            <tr>
                                <td class="text-muted student-name-cell" style="font-size:0.8rem;">{{ $idx + 1 }}</td>
                                <td class="student-name-cell">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                    <br><small class="text-muted">{{ $student->registration_no }}</small>
                                </td>
                                @php $studentTotal = 0; @endphp
                                @foreach($subjects as $subject)
                                    @php
                                        $key = $student->id . '_' . $subject->id;
                                        $existingMark = $existingResults[$key]->obtained_marks ?? '';
                                        $studentTotal += (int) ($existingResults[$key]->obtained_marks ?? 0);
                                    @endphp
                                    <td style="text-align:center;">
                                        <input
                                            type="number"
                                            class="marks-input"
                                            id="marks_{{ $student->id }}_{{ $subject->id }}"
                                            data-student="{{ $student->id }}"
                                            data-subject="{{ $subject->id }}"
                                            data-max="{{ $subject->total_marks }}"
                                            data-pass="{{ $subject->pass_marks }}"
                                            value="{{ $existingMark }}"
                                            min="0"
                                            max="{{ $subject->total_marks }}"
                                            placeholder="0"
                                            oninput="onMarksInput(this)"
                                            onchange="updateRowTotal({{ $student->id }})"
                                        >
                                    </td>
                                @endforeach
                                <td style="text-align:center;">
                                    <span class="total-chip" id="row-total-{{ $student->id }}" data-maxtotal="{{ $overallMax }}">
                                        {{ $studentTotal }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    @php
                                        $pct = $overallMax > 0 ? round(($studentTotal / $overallMax) * 100) : 0;
                                    @endphp
                                    <span class="badge {{ $pct >= 40 ? 'bg-success' : 'bg-danger' }}" id="row-pct-{{ $student->id }}">
                                        {{ $pct }}%
                                    </span>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sticky Save Bar --}}
    <div class="save-bar">
        <div class="text-muted" style="font-size:0.85rem;">
            <i class="bi bi-info-circle me-1"></i>
            Sab marks fill kar ke <strong>Save All Marks</strong> press karein
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span id="saveProgress" class="text-muted" style="font-size:0.82rem;"></span>
            <button class="btn btn-success px-4" id="btnSaveAllMarks">
                <span id="saveAllSpinner" class="spinner-border spinner-border-sm me-1 d-none" role="status"></span>
                <i class="bi bi-floppy-fill me-1"></i> Save All Marks
            </button>
        </div>
    </div>
    <div style="height: 70px;"></div> {{-- save-bar space --}}
@endif

@endsection

@push('scripts')
<script>
    const EXAM_ID    = {{ $exam->id }};
    const CSRF_TOKEN  = "{{ csrf_token() }}";
    const SAVE_URL    = `/exams/${EXAM_ID}/save-marks`;

    function onMarksInput(input) {
        const val = parseInt(input.value);
        const max = parseInt(input.dataset.max);
        const pass = parseInt(input.dataset.pass);
        input.classList.remove('is-over', 'is-pass', 'is-fail');
        if (input.value === '') return;
        if (val > max) {
            input.classList.add('is-over');
            input.value = max;
        } else if (val >= pass) {
            input.classList.add('is-pass');
        } else {
            input.classList.add('is-fail');
        }
        updateRowTotal(input.dataset.student);
    }

    function updateRowTotal(studentId) {
        const inputs = document.querySelectorAll(`input[data-student="${studentId}"]`);
        let total = 0;
        inputs.forEach(inp => { total += parseInt(inp.value) || 0; });
        
        const totalChip = document.getElementById(`row-total-${studentId}`);
        totalChip.textContent = total;
        
        const maxTotal = parseInt(totalChip.dataset.maxtotal) || 0;
        const pct = maxTotal > 0 ? Math.round((total / maxTotal) * 100) : 0;
        
        const pctEl = document.getElementById(`row-pct-${studentId}`);
        pctEl.textContent = pct + '%';
        pctEl.className = `badge ${pct >= 40 ? 'bg-success' : 'bg-danger'}`;
    }

    // Initialization to format already loaded marks
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.marks-input').forEach(input => {
            if (input.value !== '') {
                 onMarksInput(input);
            }
        });
    });

    document.getElementById('btnSaveAllMarks')?.addEventListener('click', function () {
        const allInputs = document.querySelectorAll('.marks-input');
        const marks = [];
        allInputs.forEach(inp => {
            if (inp.value !== '') {
                marks.push({
                    student_id: parseInt(inp.dataset.student),
                    subject_id: parseInt(inp.dataset.subject),
                    obtained_marks: parseInt(inp.value) || 0,
                });
            }
        });

        if (!marks.length) {
            document.getElementById('saveAlert').innerHTML =
                `<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i> Koi marks enter nahi kiye gaye!</div>`;
            return;
        }

        const spinner = document.getElementById('saveAllSpinner');
        spinner.classList.remove('d-none');
        this.disabled = true;
        document.getElementById('saveProgress').textContent = `${marks.length} entries save ho rahi hain...`;

        fetch(SAVE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ marks }),
        })
        .then(r => r.json())
        .then(res => {
            spinner.classList.add('d-none');
            this.disabled = false;
            document.getElementById('saveProgress').textContent = '';
            const alertDiv = document.getElementById('saveAlert');
            if (res.success) {
                alertDiv.innerHTML = `<div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle-fill me-1"></i> ${res.message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                alertDiv.innerHTML = `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i> ${res.message}</div>`;
            }
        })
        .catch(() => { spinner.classList.add('d-none'); this.disabled = false; });
    });
</script>
@endpush
