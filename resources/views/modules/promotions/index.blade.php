@extends('layouts.master')

@section('title', 'Student Promotions')
@section('header-title', 'Student Promotions')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        border-radius: 12px;
    }
    .selected-row {
        background-color: rgba(13, 110, 253, 0.05) !important;
        transition: all 0.3s ease;
    }
    .checkbox-xl {
        width: 1.5rem;
        height: 1.5rem;
        cursor: pointer;
    }
    .action-bar {
        position: sticky;
        bottom: 20px;
        z-index: 1000;
        animation: slideUp 0.5s ease-out;
    }
    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Student Promotions</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card glass-card border-0">
            <div class="card-header bg-primary bg-gradient text-white border-0 py-3 rounded-top-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter Source Class</h5>
            </div>
            <div class="card-body p-4">
                <form id="fetchStudentsForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary">Current Class <span class="text-danger">*</span></label>
                            <select id="filter_class_id" class="form-select shadow-sm border-0 bg-light" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary">Current Section (Optional)</label>
                            <select id="filter_section_id" class="form-select shadow-sm border-0 bg-light" disabled>
                                <option value="all">-- Select Class First --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm py-2 fw-bold">
                                <i class="bi bi-search me-2"></i>Fetch Students
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="studentsContainer">
    <div class="col-12">
        <div class="card glass-card border-0">
            <div class="card-body p-0">
                <div class="table-responsive p-4">
                    <table class="table table-hover align-middle mb-0" id="studentsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">
                                    <input type="checkbox" class="form-check-input checkbox-xl" id="selectAll">
                                </th>
                                <th>Student</th>
                                <th>Admission No</th>
                                <th>Roll No</th>
                                <th>Current Class</th>
                                <th>Section</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsBody">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Promotion Action Bar (Sticky Bottom) -->
<div class="action-bar d-none" id="promotionActionBar">
    <div class="container-fluid px-0">
        <div class="card glass-card border-0 shadow-lg border-primary border-top border-4">
            <div class="card-body p-4">
                <form id="promoteForm">
                    <div class="row align-items-center g-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-circle p-3 me-3">
                                    <i class="bi bi-people-fill fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold" id="selectedCount">0</h5>
                                    <span class="text-muted small">Students Selected</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-secondary">Promote To Class <span class="text-danger">*</span></label>
                            <select id="promote_class_id" class="form-select form-select-sm" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-secondary">Promote To Section</label>
                            <select id="promote_section_id" class="form-select form-select-sm" disabled>
                                <option value="">-- Select Class First --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-secondary">Academic Year <span class="text-danger">*</span></label>
                            <input type="text" id="promote_academic_year" class="form-control form-control-sm" placeholder="e.g. 2024-2025" required>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="submit" class="btn btn-success px-4 py-2 fw-bold shadow" id="btnPromote">
                                <i class="bi bi-rocket-takeoff me-2"></i>Promote Selected
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let selectedStudentIds = [];

    // ── 1. Dynamic Section Loading (Filter) ─────────────────────────────────
    $('#filter_class_id').on('change', function() {
        const classId = $(this).val();
        const $sectionDropdown = $('#filter_section_id');

        if (!classId) {
            $sectionDropdown.prop('disabled', true).html('<option value="all">-- Select Class First --</option>');
            return;
        }

        $sectionDropdown.prop('disabled', true).html('<option>Loading...</option>');

        $.ajax({
            url: '/promotions/sections/' + classId,
            method: 'GET',
            success: function(sections) {
                let html = '<option value="all">All Sections</option>';
                sections.forEach(sec => {
                    html += `<option value="${sec.id}">${sec.section_name}</option>`;
                });
                $sectionDropdown.prop('disabled', false).html(html);
            },
            error: function() {
                $sectionDropdown.prop('disabled', false).html('<option value="all">All Sections</option>');
            }
        });
    });

    // ── 2. Dynamic Section Loading (Promote To) ──────────────────────────────
    $('#promote_class_id').on('change', function() {
        const classId = $(this).val();
        const $sectionDropdown = $('#promote_section_id');

        if (!classId) {
            $sectionDropdown.prop('disabled', true).html('<option value="">-- Select Class First --</option>');
            return;
        }

        $sectionDropdown.prop('disabled', true).html('<option>Loading...</option>');

        $.ajax({
            url: '/promotions/sections/' + classId,
            method: 'GET',
            success: function(sections) {
                let html = '<option value="">No Section</option>';
                sections.forEach(sec => {
                    html += `<option value="${sec.id}">${sec.section_name}</option>`;
                });
                $sectionDropdown.prop('disabled', false).html(html);
            },
            error: function() {
                $sectionDropdown.prop('disabled', false).html('<option value="">No Section</option>');
            }
        });
    });

    // ── 3. Fetch Students ────────────────────────────────────────────────────
    function fetchStudents() {
        const classId = $('#filter_class_id').val();
        const sectionId = $('#filter_section_id').val();

        if (!classId) return;

        Swal.fire({ title: 'Fetching Students...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        $.ajax({
            url: "{{ route('promotions.fetch') }}",
            method: 'GET',
            data: { class_id: classId, section_id: sectionId },
            success: function(response) {
                Swal.close();
                const tbody = $('#studentsBody');
                tbody.empty();
                selectedStudentIds = [];
                updateSelectionCount();

                if (response.data && response.data.length > 0) {
                    response.data.forEach(student => {
                        tbody.append(`
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input checkbox-xl student-checkbox" value="${student.id}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="${student.image}" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                        <div class="fw-bold text-dark">${student.student_name}</div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border">${student.admission_no || '-'}</span></td>
                                <td>${student.roll_no}</td>
                                <td><span class="badge bg-info-subtle text-info border border-info-subtle">${student.current_class}</span></td>
                                <td>${student.current_section}</td>
                                <td class="text-center">
                                    <a href="/promotions/${student.id}/history" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill" title="View History">
                                        <i class="bi bi-clock-history me-1"></i> History
                                    </a>
                                </td>
                            </tr>
                        `);
                    });

                    $('#studentsContainer').removeClass('d-none');
                    $('#promotionActionBar').removeClass('d-none');
                    $('#selectAll').prop('checked', false);
                } else {
                    $('#studentsContainer').addClass('d-none');
                    $('#promotionActionBar').addClass('d-none');
                    Swal.fire('Info', 'No approved students found in this class/section.', 'info');
                }
            },
            error: function() {
                Swal.close();
                Swal.fire('Error', 'Failed to fetch students.', 'error');
            }
        });
    }

    $('#fetchStudentsForm').on('submit', function(e) {
        e.preventDefault();
        fetchStudents();
    });

    // ── 4. Select All Logic ──────────────────────────────────────────────────
    $('#selectAll').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.student-checkbox').prop('checked', isChecked);
        $('.student-checkbox').closest('tr').toggleClass('selected-row', isChecked);
        updateSelectionCount();
    });

    $(document).on('change', '.student-checkbox', function() {
        $(this).closest('tr').toggleClass('selected-row', $(this).is(':checked'));
        const allChecked = $('.student-checkbox:checked').length === $('.student-checkbox').length;
        $('#selectAll').prop('checked', allChecked);
        updateSelectionCount();
    });

    function updateSelectionCount() {
        selectedStudentIds = [];
        $('.student-checkbox:checked').each(function() {
            selectedStudentIds.push($(this).val());
        });
        $('#selectedCount').text(selectedStudentIds.length);
        $('#btnPromote').prop('disabled', selectedStudentIds.length === 0);
    }

    $('#btnPromote').prop('disabled', true);

    // ── 5. Promote Submit Logic ───────────────────────────────────────────────
    $('#promoteForm').on('submit', function(e) {
        e.preventDefault();

        if (selectedStudentIds.length === 0) {
            Swal.fire('Warning', 'Please select at least one student to promote.', 'warning');
            return;
        }

        const fromClassId   = $('#filter_class_id').val();
        const fromSectionId = $('#filter_section_id').val() === 'all' ? '' : $('#filter_section_id').val();
        const toClassId     = $('#promote_class_id').val();
        const toSectionId   = $('#promote_section_id').val();
        const academicYear  = $('#promote_academic_year').val();

        if (!toClassId) {
            Swal.fire('Warning', 'Please select a class to promote students to.', 'warning');
            return;
        }
        if (!academicYear) {
            Swal.fire('Warning', 'Please enter the Academic Year.', 'warning');
            return;
        }

        const confirmMsg = fromClassId === toClassId
            ? { title: 'Same Class!', text: 'You are promoting to the same class. Is this a repeater batch?', confirmButtonColor: '#dc3545' }
            : { title: 'Confirm Promotion', html: `Promote <b>${selectedStudentIds.length}</b> student(s)<br>Academic Year: <b>${academicYear}</b>`, confirmButtonColor: '#198754' };

        Swal.fire({
            title: confirmMsg.title,
            html: confirmMsg.html,
            text: confirmMsg.text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmMsg.confirmButtonColor || '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Promote!'
        }).then((result) => {
            if (!result.isConfirmed) return;

            Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            $.ajax({
                url: "{{ route('promotions.promote') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids: selectedStudentIds,
                    from_class_id: fromClassId,
                    to_class_id: toClassId,
                    from_section_id: fromSectionId,
                    to_section_id: toSectionId,
                    academic_year: academicYear,
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', response.message, 'success').then(() => {
                            // Re-fetch same class to show remaining students (don't auto-submit form)
                            fetchStudents();
                            $('#promote_class_id').val('');
                            $('#promote_section_id').prop('disabled', true).html('<option value="">-- Select Class First --</option>');
                        });
                    } else {
                        Swal.fire('Error!', response.message || 'Something went wrong.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Server Error', 'error');
                }
            });
        });
    });
});
</script>
@endpush

