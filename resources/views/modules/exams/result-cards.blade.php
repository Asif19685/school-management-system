@extends('layouts.master')

@section('title', 'Result Cards')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0"><i class="bi bi-card-heading me-2 text-primary"></i>Result Cards</h2>
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
                        <i class="fas fa-search me-2"></i>Search Students
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Results Table (AJAX-loaded) ── --}}
    <div id="resultsTableContainer" class="d-none">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                <h5 class="mb-0" id="resultsTableTitle">Student List</h5>
                <span class="badge bg-primary" id="studentCount">0</span>
            </div>
            <div class="card-body p-0" id="tableWrapper">
                <div class="text-center py-4" id="loadingPlaceholder">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading students...</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(function() {

    // Cache DOM elements
    const $examSelect = $('#exam_id');
    const $classSelect = $('#class_id');
    const $loadBtn = $('#loadStudentsBtn');
    const $resultsContainer = $('#resultsTableContainer');
    const $tableWrapper = $('#tableWrapper');
    const $tableTitle = $('#resultsTableTitle');
    const $studentCount = $('#studentCount');
    const $loadingPlaceholder = $('#loadingPlaceholder');

    // ─── Load Students ───────────────────────────────────────────
    function loadStudents() {
        var exam_id = $examSelect.val();
        var class_id = $classSelect.val();

        if (!exam_id || !class_id) {
            toastr.warning('Please select Exam and Class first.');
            return;
        }

        // Disable button & show loading
        var btn = $loadBtn;
        btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

        // Show container and placeholder
        $resultsContainer.removeClass('d-none');
        $loadingPlaceholder.show();
        $tableWrapper.html('');

        // Get selected names for title
        var examName = $examSelect.find('option:selected').text();
        var className = $classSelect.find('option:selected').text();

        $.ajax({
            url: "{{ route('exams.result-cards.fetch') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                exam_id: exam_id,
                class_id: class_id
            },
            cache: false, // Disable browser cache
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Debug log

                if (response.success) {
                    $tableWrapper.html(response.html);
                    $tableTitle.html('Result Cards &mdash; ' + examName + ' (' + className + ')');
                    $studentCount.text(response.count || 0);
                    $loadingPlaceholder.hide();

                    if (response.count === 0) {
                        toastr.info('No students found for this exam and class.');
                    } else {
                        toastr.success(response.count + ' students loaded successfully!');
                    }
                } else {
                    toastr.error(response.message || 'Failed to load data.');
                    $resultsContainer.addClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log('Response Text:', xhr.responseText);
                console.log('Status Code:', xhr.status);

                let errorMsg = 'Failed to load students. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 419) {
                    errorMsg = 'Session expired. Please refresh the page.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error. Please check logs.';
                }

                toastr.error(errorMsg);
                $resultsContainer.addClass('d-none');
            },
            complete: function() {
                btn.html('<i class="fas fa-search me-2"></i>Search Students').prop('disabled', false);
            }
        });
    }

    // ─── Event Handler ──────────────────────────────────────────
    $('#loadStudentsBtn').click(loadStudents);

    // ─── Auto-load if both selected ─────────────────────────────
    if ($examSelect.val() && $classSelect.val()) {
        loadStudents();
    }

    // ─── Auto-load on dropdown change (optional) ──────────────
    // $examSelect.change(loadStudents);
    // $classSelect.change(loadStudents);

});
</script>
@endpush
