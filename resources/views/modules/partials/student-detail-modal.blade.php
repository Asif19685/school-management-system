<!-- Student Detailed Info Modal -->
<div class="modal fade" id="studentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-lines-fill me-2"></i> Student Full Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Sidebar: Photo & Key details -->
                    <div class="col-lg-3 text-center mb-4 mb-lg-0 border-end-lg">
                        <div class="p-3 bg-light rounded-3 mb-3">
                            <img id="detail_student_image" src="{{ asset('images/default-avatar.png') }}"
                                 alt="Student Photo" class="img-fluid rounded-circle border border-4 border-white shadow-sm mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 class="fw-bold text-dark mb-1" id="detail_full_name">Student Name</h5>
                            <span class="badge bg-success-light px-3 py-2 rounded-pill fw-semibold" id="detail_class_section">Class 1 - A</span>
                            <div class="mt-3 text-start small">
                                <div class="mb-2"><strong>Admission No:</strong> <span id="detail_admission_no" class="text-muted float-end">-</span></div>
                                <div class="mb-2"><strong>Reg No:</strong> <span id="detail_reg_no" class="text-muted float-end">-</span></div>
                                <div class="mb-2"><strong>Roll No:</strong> <span id="detail_roll_no" class="text-muted float-end">-</span></div>
                                <div><strong>Admission Date:</strong> <span id="detail_admission_date" class="text-muted float-end">-</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Main details tabs -->
                    <div class="col-lg-9">
                        <ul class="nav nav-tabs border-bottom mb-3" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold text-secondary" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                                    <i class="bi bi-person me-1"></i> Personal Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="guardian-tab" data-bs-toggle="tab" data-bs-target="#guardian" type="button" role="tab">
                                    <i class="bi bi-people me-1"></i> Guardian Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation" id="fee-tab-container">
                                <button class="nav-link fw-bold text-secondary" id="fee-tab" data-bs-toggle="tab" data-bs-target="#fee" type="button" role="tab">
                                    <i class="bi bi-cash-stack me-1"></i> Fee Details
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">
                            <!-- Personal Info Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                <div class="row g-3 py-2">
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">B-Form / CNIC No</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_b_form">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Gender</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_gender">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Date of Birth</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_dob">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Religion</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_religion">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Disability Detail</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_disability">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Previous Class</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_prev_class">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Previous School Details</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_prev_school">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Remarks</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_remarks">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Guardian Info Tab -->
                            <div class="tab-pane fade" id="guardian" role="tabpanel">
                                <div class="row g-3 py-2">
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Father Name</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_father_name">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Father CNIC</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_father_cnic">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Father Occupation</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_father_occ">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Mother Name</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_mother_name">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Mother Education</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_mother_edu">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Guardian Phone</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_guardian_phone">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Emergency Contact</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_emergency_contact">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Family Monthly Income</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_family_income">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Complete Address</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_complete_address">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Postal Address</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_postal_address">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fee Info Tab -->
                            <div class="tab-pane fade" id="fee" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover align-middle mt-2">
                                        <thead class="table-light text-secondary small fw-bold">
                                            <tr>
                                                <th>Fee Month / Type</th>
                                                <th>Base Amount</th>
                                                <th>Discount</th>
                                                <th>Fine</th>
                                                <th>Total Due</th>
                                                <th>Total Paid</th>
                                                <th>Remaining</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detail_fee_records_body" class="small">
                                            <!-- Dynamic content loaded via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
