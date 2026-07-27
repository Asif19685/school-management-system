@extends('layouts.master')

@section('title', 'Subject Management - School Management System')
@section('header-title', 'Subject Management')

@section('styles')
<style>
    .subject-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
    }
    .class-badge {
        font-size: 0.78rem;
        padding: 4px 10px;
        border-radius: 12px;
        background: #e0e7ff;
        color: #3730a3;
        font-weight: 600;
    }
    .stats-card {
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
    }
    .stats-card.green {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }
    .stats-card.orange {
        background: linear-gradient(135deg, #f7971e, #ffd200);
        color: #333;
    }
    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }
    .filter-card {
        background: #f8faff;
        border: 1px solid #e8eeff;
        border-radius: 12px;
    }
    #subjectsTable th {
        background: #f1f5f9;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-edit-sub { background: #e0e7ff; color: #3730a3; }
    .btn-edit-sub:hover { background: #3730a3; color: white; }
    .btn-del-sub { background: #fee2e2; color: #dc2626; }
    .btn-del-sub:hover { background: #dc2626; color: white; }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0;
        margin-left: 2px;
        border: none;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: transparent;
        border: none;
    }
    table.dataTable { border-collapse: collapse !important; }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Subjects</li>
            </ol>
        </nav>
        <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal" id="btn-add-subject">
            <i class="bi bi-plus-circle-fill me-1"></i> New Subject Add Karein
        </button>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4" id="stats-row">
    <div class="col-6 col-md-4">
        <div class="stats-card">
            <div class="stats-number" id="stat-total">—</div>
            <div class="mt-1" style="font-size:0.85rem; opacity:0.85;">Total Subjects</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stats-card green">
            <div class="stats-number" id="stat-classes">{{ $classes->count() }}</div>
            <div class="mt-1" style="font-size:0.85rem; opacity:0.85;">Total Classes</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stats-card orange">
            <div class="stats-number" id="stat-filtered">—</div>
            <div class="mt-1" style="font-size:0.85rem; opacity:0.85;">Filtered Subjects</div>
        </div>
    </div>
</div>

{{-- Filter + Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        {{-- Filter --}}
        <div class="filter-card p-3 mb-4 d-flex align-items-center gap-3 flex-wrap">
            <i class="bi bi-funnel-fill text-primary"></i>
            <label class="fw-semibold mb-0" style="font-size:0.9rem;">Class Filter:</label>
            <select id="classFilter" class="form-select form-select-sm" style="max-width:220px;">
                <option value="">— Sab Classes —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-secondary btn-sm" id="btnClearFilter">
                <i class="bi bi-x-circle me-1"></i> Clear
            </button>
            <span class="ms-auto text-muted" style="font-size:0.82rem;" id="filterNote"></span>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="subjectsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Ka Naam</th>
                        <th>Subject Code</th>
                        <th>Class</th>
                        <th>Add Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="subjectsBody">
                    <tr><td colspan="6" class="text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div> Loading...
                    </td></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ===== Add Subject Modal ===== --}}
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg,#4f46e5,#7c3aed); color:white;">
                <h5 class="modal-title" id="addSubjectModalLabel">
                    <i class="bi bi-plus-circle me-2"></i> Naya Subject Add Karein
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="addAlert"></div>
                <form id="addSubjectForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Class Select Karein <span class="text-danger">*</span></label>
                        <select name="class_id" id="add_class_id" class="form-select" required>
                            <option value="">— Class chunein —</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject Ka Naam <span class="text-danger">*</span></label>
                        <input type="text" name="subject_name" id="add_subject_name" class="form-control"
                               placeholder="jaise: Mathematics, Urdu, Science..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject Code <small class="text-muted">(optional)</small></label>
                        <input type="text" name="subject_code" id="add_subject_code" class="form-control"
                               placeholder="jaise: MATH, URD, SCI...">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4" id="btnSaveSubject">
                    <span id="saveSpinner" class="spinner-border spinner-border-sm me-1 d-none" role="status"></span>
                    <i class="bi bi-check-circle me-1"></i> Save Karein
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== Edit Subject Modal ===== --}}
<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg,#0ea5e9,#06b6d4); color:white;">
                <h5 class="modal-title" id="editSubjectModalLabel">
                    <i class="bi bi-pencil me-2"></i> Subject Edit Karein
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="editAlert"></div>
                <form id="editSubjectForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_subject_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Class Select Karein <span class="text-danger">*</span></label>
                        <select name="class_id" id="edit_class_id" class="form-select" required>
                            <option value="">— Class chunein —</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" name="subject_name" id="edit_subject_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject Code</label>
                        <input type="text" name="subject_code" id="edit_subject_code" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white px-4" id="btnUpdateSubject">
                    <span id="updateSpinner" class="spinner-border spinner-border-sm me-1 d-none" role="status"></span>
                    <i class="bi bi-check-circle me-1"></i> Update
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirm Modal --}}
<div class="modal fade" id="deleteSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3" style="font-size:2.5rem;"><i class="bi bi-trash3-fill"></i></div>
                <h6 class="fw-bold">Subject Delete Karein?</h6>
                <p class="text-muted small" id="deleteSubjectName"></p>
                <input type="hidden" id="delete_subject_id">
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger btn-sm" id="btnConfirmDelete">
                        <span id="deleteSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                        Haan, Delete Karein
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    const SUBJECTS_DATA_URL = "{{ route('subjects.data') }}";
    const SUBJECTS_STORE_URL = "{{ route('subjects.store') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";

    let table;

    $(document).ready(function() {
        table = $('#subjectsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: SUBJECTS_DATA_URL,
                data: function (d) {
                    d.class_id = $('#classFilter').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'subject_name', name: 'subject_name' },
                { data: 'subject_code', name: 'subject_code' },
                { data: 'class_name', name: 'schoolClass.class_name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: function(settings) {
                const api = this.api();
                const totalRecords = api.page.info().recordsTotal;
                const filteredRecords = api.page.info().recordsDisplay;
                $('#stat-total').text(totalRecords);
                $('#stat-filtered').text(filteredRecords);
            }
        });

        // ====== Filter ======
        $('#classFilter').on('change', function () {
            const val = $(this).val();
            const note = $('#filterNote');
            if (val) {
                const cls = $(this).find('option:selected').text();
                note.text(`Sirf "${cls}" ke subjects dikha rahe hain`);
            } else {
                note.text('');
            }
            table.draw();
        });

        $('#btnClearFilter').on('click', function () {
            $('#classFilter').val('');
            $('#filterNote').text('');
            table.draw();
        });
    });

    // ====== Add Subject ======
    document.getElementById('btnSaveSubject').addEventListener('click', function () {
        const spinner = document.getElementById('saveSpinner');
        spinner.classList.remove('d-none');
        this.disabled = true;

        const data = {
            class_id:     document.getElementById('add_class_id').value,
            subject_name: document.getElementById('add_subject_name').value.trim(),
            subject_code: document.getElementById('add_subject_code').value.trim(),
            _token:       CSRF_TOKEN,
        };

        fetch(SUBJECTS_STORE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify(data),
        })
        .then(r => r.json())
        .then(res => {
            spinner.classList.add('d-none');
            this.disabled = false;
            const alertDiv = document.getElementById('addAlert');
            if (res.success) {
                alertDiv.innerHTML = `<div class="alert alert-success py-2"><i class="bi bi-check-circle me-1"></i> ${res.message}</div>`;
                document.getElementById('addSubjectForm').reset();
                table.draw();
                setTimeout(() => { bootstrap.Modal.getInstance(document.getElementById('addSubjectModal'))?.hide(); alertDiv.innerHTML = ''; }, 1500);
            } else {
                alertDiv.innerHTML = `<div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-1"></i> ${res.message}</div>`;
            }
        })
        .catch(() => { spinner.classList.add('d-none'); this.disabled = false; });
    });

    // ====== Edit ======
    function openEdit(id, name, code, classId) {
        document.getElementById('edit_subject_id').value = id;
        document.getElementById('edit_subject_name').value = name;
        document.getElementById('edit_subject_code').value = code;
        document.getElementById('edit_class_id').value = classId;
        document.getElementById('editAlert').innerHTML = '';
        new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
    }

    document.getElementById('btnUpdateSubject').addEventListener('click', function () {
        const id = document.getElementById('edit_subject_id').value;
        const spinner = document.getElementById('updateSpinner');
        spinner.classList.remove('d-none');
        this.disabled = true;

        const data = {
            class_id:     document.getElementById('edit_class_id').value,
            subject_name: document.getElementById('edit_subject_name').value.trim(),
            subject_code: document.getElementById('edit_subject_code').value.trim(),
            _method:      'PUT',
            _token:       CSRF_TOKEN,
        };

        fetch(`/subjects/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-HTTP-Method-Override': 'PUT' },
            body: JSON.stringify({ ...data, _method: 'PUT' }),
        })
        .then(r => r.json())
        .then(res => {
            spinner.classList.add('d-none');
            this.disabled = false;
            const alertDiv = document.getElementById('editAlert');
            if (res.success) {
                alertDiv.innerHTML = `<div class="alert alert-success py-2"><i class="bi bi-check-circle me-1"></i> ${res.message}</div>`;
                table.draw();
                setTimeout(() => { bootstrap.Modal.getInstance(document.getElementById('editSubjectModal'))?.hide(); alertDiv.innerHTML = ''; }, 1500);
            } else {
                alertDiv.innerHTML = `<div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-1"></i> ${res.message}</div>`;
            }
        })
        .catch(() => { spinner.classList.add('d-none'); this.disabled = false; });
    });

    // ====== Delete ======
    function openDelete(id, name) {
        document.getElementById('delete_subject_id').value = id;
        document.getElementById('deleteSubjectName').textContent = `"${name}" subject delete ho jaayega.`;
        new bootstrap.Modal(document.getElementById('deleteSubjectModal')).show();
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', function () {
        const id = document.getElementById('delete_subject_id').value;
        const spinner = document.getElementById('deleteSpinner');
        spinner.classList.remove('d-none');
        this.disabled = true;

        fetch(`/subjects/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ _method: 'DELETE', _token: CSRF_TOKEN }),
        })
        .then(r => r.json())
        .then(res => {
            spinner.classList.add('d-none');
            this.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteSubjectModal'))?.hide();
                table.draw();
            }
        })
        .catch(() => { spinner.classList.add('d-none'); this.disabled = false; });
    });

</script>
@endpush
