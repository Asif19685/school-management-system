@extends('layouts.master')

@section('title', 'Final Results Entry')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0"><i class="fas fa-award me-2 text-success"></i>Final Results Entry</h2>
        <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Exams
        </a>
    </div>

    {{-- ── Filter Card ── --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Select Exam <span class="text-danger">*</span></label>
                    <select id="exam_id" class="form-select" required>
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}"
                                {{ $selectedExamId == $exam->id ? 'selected' : '' }}>
                                {{ $exam->exam_name }} ({{ $exam->academic_year }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Select Class <span class="text-danger">*</span></label>
                    <select id="class_id" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" id="loadStudentsBtn" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Load Students
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Results Table (AJAX-loaded) ── --}}
    <div id="resultsTableContainer" class="d-none">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="resultsTableTitle">Enter Final Results</h5>
                <button type="button" id="saveResultsBtn" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Save Final Results
                </button>
            </div>
            <div class="card-body p-0" id="tableWrapper"></div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(function() {

    // ─── Load Students ───────────────────────────────────────────
    $('#loadStudentsBtn').click(function () {
        var exam_id  = $('#exam_id').val();
        var class_id = $('#class_id').val();

        if (!exam_id || !class_id) {
            toastr.warning('Please select Exam and Class first.');
            return;
        }

        var btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

        var examName  = $('#exam_id option:selected').text();
        var className = $('#class_id option:selected').text();

        $.ajax({
            url: "{{ route('exams.final-results.fetch') }}",
            type: 'POST',
            data: { _token: "{{ csrf_token() }}", exam_id, class_id },
            success: function (res) {
                if (res.success) {
                    $('#tableWrapper').html(res.html);
                    $('#resultsTableTitle').html(examName + ' &mdash; ' + className);
                    $('#resultsTableContainer').removeClass('d-none');
                }
            },
            error: function () { toastr.error('Failed to load students.'); },
            complete: function () {
                btn.html('<i class="fas fa-search me-2"></i>Load Students').prop('disabled', false);
            }
        });
    });

    // ─── Auto-calculate percentage ───────────────────────────────
    $(document).on('input', '.total-marks, .obtained-marks', function () {
        var row     = $(this).closest('.result-row');
        var total   = parseFloat(row.find('.total-marks').val()) || 0;
        var obtained= parseFloat(row.find('.obtained-marks').val()) || 0;
        var pct     = total > 0 ? ((obtained / total) * 100).toFixed(2) : '0.00';
        row.find('.percentage-val').val(pct);
        row.find('.status-select').val(parseFloat(pct) >= 33 ? 'pass' : 'fail');
    });

    // ─── Save Results ────────────────────────────────────────────
    $('#saveResultsBtn').click(function () {
        var resultsData = [];
        var valid = true;

        $('.result-row').each(function () {
            var row = $(this);
            var student_id          = row.data('student');
            var grand_total_marks   = row.find('.total-marks').val();
            var grand_obtained_marks= row.find('.obtained-marks').val();
            var percentage          = row.find('.percentage-val').val();
            var final_status        = row.find('.status-select').val();
            var position            = row.find('.position-input').val();
            var final_grade         = row.find('.grade-input').val();
            var remarks             = row.find('.remarks-input').val();

            if (!grand_total_marks || grand_obtained_marks === '' || !final_status) {
                valid = false;
                row.addClass('table-danger');
            } else {
                row.removeClass('table-danger');
                resultsData.push({ student_id, grand_total_marks, grand_obtained_marks, percentage, final_status, position, final_grade, remarks });
            }
        });

        if (!valid) { toastr.error('Fill all required fields (Grand Total, Obtained, Status).'); return; }

        var btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: "{{ route('exams.final-results.save') }}",
            type: 'POST',
            data: {
                _token:   "{{ csrf_token() }}",
                exam_id:  $('#exam_id').val(),
                class_id: $('#class_id').val(),
                results:  resultsData
            },
            success: function (res) { toastr.success(res.message); },
            error: function (xhr) {
                var msg = xhr.responseJSON?.message || 'Failed to save results.';
                toastr.error(msg);
            },
            complete: function () {
                btn.html('<i class="fas fa-save me-2"></i>Save Final Results').prop('disabled', false);
            }
        });
    });

});
</script>
@endpush
