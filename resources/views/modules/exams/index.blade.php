@extends('layouts.master')

@section('title', 'Manage Exams')
@section('header-title', 'Examinations')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Exams</li>
            </ol>
        </nav>
        <button class="btn btn-primary btn-sm" id="addExamBtn">
            <i class="bi bi-plus-circle me-1"></i> Create New Exam
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center"
                         style="width:54px;height:54px;font-size:1.5rem;">
                        <i class="bi bi-clipboard-data text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Exams List</h3>
                        <p class="text-muted mb-0">Manage all exams and enter student results.</p>
                    </div>
                </div>
                <hr class="my-3 text-muted opacity-25">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="examsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Exam Name</th>
                                <th>Type</th>
                                <th>Year</th>
                                <th>Class</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add / Edit Exam Modal -->
<div class="modal fade" id="examModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="examModalTitle">
                    <i class="bi bi-plus-circle me-2"></i>Create New Exam
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="examForm" novalidate>
                @csrf
                <input type="hidden" id="examId" name="exam_id" value="">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Exam Name <span class="text-danger">*</span></label>
                            <input type="text" id="exam_name" name="exam_name"
                                   class="form-control" placeholder="e.g., First Term 2026" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Exam Type <span class="text-danger">*</span></label>
                            <select id="exam_type" name="exam_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="monthly_test">Monthly Test</option>
                                <option value="mid_term">Mid Term</option>
                                <option value="final_term">Final Term</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Academic Year <span class="text-danger">*</span></label>
                            <input type="number" id="academic_year" name="academic_year"
                                   class="form-control" value="{{ date('Y') }}" min="2000" max="2100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Class
                                <small class="text-muted fw-normal">(Optional — for all classes leave blank)</small>
                            </label>
                            <select id="class_id" name="class_id" class="form-select">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Exam Date</label>
                            <input type="date" id="exam_date" name="exam_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveExamBtn">
                        <i class="bi bi-save me-1"></i> Save Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

{{-- Toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    toastr.options = { positionClass: 'toast-top-right', timeOut: 3000 };

    // ─── DataTable ─────────────────────────────────────────────────
    var table = $('#examsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50], [10, 25, 50]],
        ajax: { url: "{{ route('exams.data') }}", type: 'GET' },
        columns: [
            { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
            { data: 'exam_name',       name: 'exam_name' },
            { data: 'exam_type_badge', name: 'exam_type',      orderable: false, searchable: false },
            { data: 'academic_year',   name: 'academic_year' },
            { data: 'class',           name: 'class',          orderable: false, searchable: false },
            { data: 'exam_date',       name: 'exam_date',      defaultContent: '-' },
            { data: 'action',          name: 'action',         orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="spinner-border spinner-border-sm text-primary"></div>',
            zeroRecords: 'No exams found.',
        },
        order: [[3, 'desc']],
    });

    // ─── Open CREATE modal ──────────────────────────────────────────
    $('#addExamBtn').on('click', function () {
        $('#examModalTitle').html('<i class="bi bi-plus-circle me-2"></i>Create New Exam');
        $('#saveExamBtn').html('<i class="bi bi-save me-1"></i> Save Exam');
        $('#examForm')[0].reset();
        $('#examId').val('');
        $('#academic_year').val('{{ date("Y") }}');
        $('#examModal').modal('show');
    });

    // ─── Open EDIT modal ───────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
        var btn = $(this);
        $('#examModalTitle').html('<i class="bi bi-pencil-square me-2"></i>Edit Exam');
        $('#saveExamBtn').html('<i class="bi bi-save me-1"></i> Update Exam');

        $('#examId').val(btn.data('id'));
        $('#exam_name').val(btn.data('name'));
        $('#exam_type').val(btn.data('type'));
        $('#academic_year').val(btn.data('year'));
        $('#class_id').val(btn.data('class') || '');
        var d = btn.data('date');
        $('#exam_date').val(d && d !== 'null' ? d : '');

        $('#examModal').modal('show');
    });

    // ─── Form Submit (Create & Update) ─────────────────────────────
    $('#examForm').on('submit', function (e) {
        e.preventDefault();
        var id  = $('#examId').val();
        var url = id ? "{{ url('exams') }}/" + id : "{{ route('exams.store') }}";
        var method = id ? 'PUT' : 'POST';

        $('#saveExamBtn').html('<i class="bi bi-hourglass-split"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function (res) {
                $('#examModal').modal('hide');
                $('#examForm')[0].reset();
                $('#examId').val('');
                table.ajax.reload();
                toastr.success(res.message);
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (k, v) { toastr.error(v[0]); });
                } else {
                    toastr.error('Something went wrong! Please try again.');
                }
            },
            complete: function () {
                $('#saveExamBtn').html('<i class="bi bi-save me-1"></i> Save Exam').prop('disabled', false);
            }
        });
    });

    // ─── Delete ────────────────────────────────────────────────────
    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        if (!confirm('Are you sure? This will permanently delete the exam and ALL its marks!')) return;

        $.ajax({
            type: 'DELETE',
            url: "{{ url('exams') }}/" + id,
            success: function (res) {
                table.ajax.reload();
                toastr.success(res.message);
            },
            error: function () { toastr.error('Error deleting exam.'); }
        });
    });

});
</script>
@endpush
