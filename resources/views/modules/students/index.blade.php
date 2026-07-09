{{-- @extends('layouts.master')

@section('title', 'Students Directory - School Management System')
@section('header-title', 'Students Directory')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Students</li>
            </ol>
        </nav>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon me-3 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Students List</h3>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="approvedStudentsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Admission No.</th>
                                <th>Student Name</th>
                                <th>B-Form No</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Roll No</th>
                                <th>Guardian Name</th>
                                <th>Guardian Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Server-side DataTables population -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Student Detailed Info Modal -->
<div class="modal fade" id="studentDetailModal" tabindex="-1" aria-hidden="true" data-show-fee="true">
    <!-- ... rest of modal content same rahega ... -->
</div>

@endsection

@push('scripts')
<!-- Load libraries dynamically same as Admissions -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Setup DataTable
    var table = $('#approvedStudentsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        order: [[3, 'asc']],
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        ajax: {
            url: "{{ route('students.data') }}",
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'student_image', name: 'student_image', orderable: false, searchable: false },
            { data: 'admission_no', name: 'admission_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'b_form_no', name: 'b_form_no' },
            { data: 'class', name: 'schoolClass.class_name' },
            { data: 'section', name: 'section.section_name' },
            { data: 'roll_no', name: 'roll_no' },
            { data: 'guardian_name', name: 'guardian_name' },
            { data: 'guardian_phone', name: 'guardian_phone' }
        ],
        language: {
            processing: '<div class="spinner-border text-primary"></div>',
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            zeroRecords: "No matching student found",
        }
    });
});
</script>
@endpush --}}
@extends('layouts.master')

@section('title', 'Students Directory - School Management System')
@section('header-title', 'Students Directory')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Students</li>
            </ol>
        </nav>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- ── Filter Bar ──────────────────────────────────────────── -->
                <div class="p-3 bg-light rounded-3 border border-light shadow-sm mb-3">
                    <div class="row g-2 align-items-end">
                        <!-- Class Filter -->
                        <div class="col-md-4 col-sm-6">
                            <label for="class_filter" class="form-label small fw-bold mb-1 text-secondary">
                                <i class="bi bi-mortarboard me-1"></i>Filter by Class
                            </label>
                            <select id="class_filter" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="all">-- All Classes --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Buttons -->
                        <div class="col-md-2 col-6">
                            <button type="button" id="apply_class_filter_btn" class="btn btn-primary btn-sm w-100 shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i>Apply
                            </button>
                        </div>
                        <div class="col-md-2 col-6">
                            <button type="button" id="clear_class_filter_btn" class="btn btn-outline-secondary btn-sm w-100" title="Clear Filter">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                    <!-- Active filter badges -->
                    <div id="active_class_filter_label" class="mt-2 d-none">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill small">
                            <i class="bi bi-funnel me-1"></i>
                            Filtered: <strong id="active_class_name"></strong>
                            <i class="bi bi-x ms-1" style="cursor:pointer;" onclick="clearClassFilter()"></i>
                        </span>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="approvedStudentsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Admission No.</th>
                                <th>Student Name</th>
                                <th>B-Form No</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Roll No</th>
                                <th>Guardian Name</th>
                                <th>Guardian Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Server-side DataTables population -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Student Detail Modal -->
@include('modules.partials.student-detail-modal')

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/student-detail.js') }}"></script>

<script>
$(document).ready(function() {
    var table = $('#approvedStudentsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        order: [[3, 'asc']],
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        ajax: {
            url: "{{ route('students.data') }}",
            type: 'GET',
            data: function(d) {
                d.class_filter = $('#class_filter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'student_image', name: 'student_image', orderable: false, searchable: false },
            { data: 'admission_no', name: 'admission_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'b_form_no', name: 'b_form_no' },
            { data: 'class', name: 'schoolClass.class_name' },
            { data: 'section', name: 'section.section_name' },
            { data: 'roll_no', name: 'roll_no' },
            { data: 'guardian_name', name: 'guardian_name' },
            { data: 'guardian_phone', name: 'guardian_phone' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        language: {
            processing: '<div class="spinner-border text-primary"></div>',
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            zeroRecords: "No matching student found",
        }
    });

    // ── Apply / Clear Class Filter Buttons ──────────────────────────────────────
    $('#apply_class_filter_btn').on('click', function() {
        var selectedText = $('#class_filter option:selected').text().trim();
        var selectedVal  = $('#class_filter').val();
        if (selectedVal !== 'all') {
            $('#active_class_name').text(selectedText);
            $('#active_class_filter_label').removeClass('d-none');
        } else {
            $('#active_class_filter_label').addClass('d-none');
        }
        table.ajax.reload();
    });

    $('#clear_class_filter_btn').on('click', function() {
        clearClassFilter();
    });

    window.clearClassFilter = function() {
        $('#class_filter').val('all');
        $('#active_class_filter_label').addClass('d-none');
        table.ajax.reload();
    };

    // View Student profile (Students Directory - without Fee tab)
    $(document).on('click', '.view-student-btn-no-fee', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        if (!id) return;

        // Hide fee tab
        toggleFeeTab(false);

        // Load student detail without fee
        loadStudentDetail(id, false);
    });
});
</script>
@endpush
