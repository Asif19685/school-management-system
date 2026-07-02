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
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon me-3 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Students List</h3>
                        <p class="text-muted mb-0">All approved students directory</p>
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
