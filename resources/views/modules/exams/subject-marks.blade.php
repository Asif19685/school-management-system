@extends('layouts.master')

@section('title', 'Subject Marks Entry')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0"><i class="fas fa-pencil-alt me-2 text-warning"></i>Subject Marks Entry</h2>
        <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Exams
        </a>
    </div>

    {{-- ── Filter Card ── --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label">Select Class <span class="text-danger">*</span></label>
                    <select id="class_id" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Select Subject <span class="text-danger">*</span></label>
                    <select id="subject_id" class="form-select" required>
                        <option value="">-- Select Class First --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" id="loadStudentsBtn" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i> Load Students
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Marks Table (AJAX-loaded) ── --}}
    <div id="marksTableContainer" class="d-none">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="marksTableTitle">Enter Marks</h5>
                <button type="button" id="saveMarksBtn" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Save Marks
                </button>
            </div>
            <div class="card-body p-0" id="tableWrapper"></div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
{{-- Toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(function() {

    var subjectsByClassUrl = "{{ url('exams/subjects-by-class') }}";

    // ─── Load subjects when class changes ────────────────────────
    $('#class_id').change(function () {
        var classId = $(this).val();
        var subjectSel = $('#subject_id');
        subjectSel.html('<option value="">Loading...</option>').prop('disabled', true);

        if (!classId) {
            subjectSel.html('<option value="">-- Select Class First --</option>').prop('disabled', false);
            return;
        }

        $.get(subjectsByClassUrl + '/' + classId, function (data) {
            var options = '<option value="">-- Select Subject --</option>';
            if (data.length === 0) {
                options = '<option value="">No subjects found for this class</option>';
            } else {
                $.each(data, function (i, subj) {
                    options += '<option value="' + subj.id + '">' + subj.subject_name + '</option>';
                });
            }
            subjectSel.html(options).prop('disabled', false);
        }).fail(function () {
            subjectSel.html('<option value="">Error loading subjects</option>').prop('disabled', false);
        });
    });

    // ─── Load students ───────────────────────────────────────────
    $('#loadStudentsBtn').click(function () {
        var exam_id    = $('#exam_id').val();
        var class_id   = $('#class_id').val();
        var subject_id = $('#subject_id').val();

        if (!exam_id || !class_id || !subject_id) {
            toastr.warning('Please select Exam, Class, and Subject first.');
            return;
        }

        var btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

        var examName    = $('#exam_id option:selected').text();
        var className   = $('#class_id option:selected').text();
        var subjectName = $('#subject_id option:selected').text();

        $.ajax({
            url: "{{ route('exams.subject-marks.fetch') }}",
            type: 'POST',
            data: { _token: "{{ csrf_token() }}", exam_id, class_id, subject_id },
            success: function (res) {
                if (res.success) {
                    $('#tableWrapper').html(res.html);
                    $('#marksTableTitle').html(examName + ' &mdash; ' + className + ' &mdash; ' + subjectName);
                    $('#marksTableContainer').removeClass('d-none');
                }
            },
            error: function () { toastr.error('Failed to load students.'); },
            complete: function () {
                btn.html('<i class="fas fa-search me-2"></i> Load Students').prop('disabled', false);
            }
        });
    });

    // ─── Save Marks ──────────────────────────────────────────────
    $('#saveMarksBtn').click(function () {
        var marksData = [];
        var valid = true;

        $('.mark-row').each(function () {
            var row       = $(this);
            var student_id    = row.data('student');
            var total_marks   = row.find('.total-marks').val();
            var obtained_marks= row.find('.obtained-marks').val();
            var grade         = row.find('.grade-input').val();
            var status        = row.find('.status-select').val();
            var remarks       = row.find('.remarks-input').val();

            if (!total_marks || obtained_marks === '' || !status) {
                valid = false;
                row.addClass('table-danger');
            } else {
                row.removeClass('table-danger');
                marksData.push({ student_id, total_marks, obtained_marks, grade, status, remarks });
            }
        });

        if (!valid) { toastr.error('Fill all required fields.'); return; }

        var btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: "{{ route('exams.subject-marks.save') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                exam_id:    $('#exam_id').val(),
                class_id:   $('#class_id').val(),
                subject_id: $('#subject_id').val(),
                marks:      marksData
            },
            success: function (res) { toastr.success(res.message); },
            error: function (xhr) {
                var msg = xhr.responseJSON?.message || 'Failed to save marks.';
                toastr.error(msg);
            },
            complete: function () {
                btn.html('<i class="fas fa-save me-2"></i>Save Marks').prop('disabled', false);
            }
        });
    });

    // ─── Auto-status (pass/fail) when obtained marks change ──────
    $(document).on('input', '.obtained-marks', function () {
        var row = $(this).closest('.mark-row');
        var total    = parseFloat(row.find('.total-marks').val()) || 0;
        var obtained = parseFloat($(this).val()) || 0;
        var passing  = total * 0.33; // 33% default
        row.find('.status-select').val(obtained >= passing ? 'pass' : 'fail');
    });

});
</script>
@endpush
