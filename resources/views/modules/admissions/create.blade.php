@extends('layouts.master')

@section('title', 'New Student Admission')
@section('header-title', 'New Student Admission')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Student Admissions</a></li>
                <li class="breadcrumb-item active">New Admission</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Student Admission Form</h5>
                <p class="text-muted mb-0 small">Fill all required information. Application will be submitted as PENDING.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admissions.store') }}" method="POST" id="admissionForm" enctype="multipart/form-data">
                    @csrf

                    <!-- ==================== PROFILE PICTURE ==================== -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-camera me-2"></i> Student Profile Picture</h6>
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <!-- Circle Preview -->
                            <div class="position-relative" style="width:120px; height:120px;">
                                <img id="imagePreview"
                                    src="{{ asset('images/default-avatar.png') }}"
                                    alt="Profile Preview"
                                    class="rounded-circle border border-3 border-primary shadow"
                                    style="width:120px; height:120px; object-fit:cover; cursor:pointer;"
                                    onclick="document.getElementById('student_image').click()">
                                <!-- Camera overlay -->
                                <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:32px;height:32px;cursor:pointer;"
                                    onclick="document.getElementById('student_image').click()">
                                    <i class="bi bi-camera-fill text-white" style="font-size:14px;"></i>
                                </div>
                            </div>
                            <!-- Upload controls -->
                            <div>
                                <input type="file" name="student_image" id="student_image" class="d-none" accept="image/jpeg,image/png,image/jpg,image/gif">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 d-block" onclick="document.getElementById('student_image').click()">
                                    <i class="bi bi-upload me-1"></i> Upload Photo
                                </button>
                                <small class="text-muted d-block">Supported: JPG, PNG, GIF &bull; Max 2MB</small>
                                <small class="text-muted d-block">Click photo or button to select</small>
                                <div class="text-danger small" id="student_image_error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== STUDENT INFORMATION ==================== -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-person me-2"></i> Student Personal Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Registration No.</label>
                                <input type="text" name="registration_no" class="form-control" value="REG-{{ date('Y') }}-{{ str_pad(($studentCount + 1), 4, '0', STR_PAD_LEFT) }}" readonly>
                                <small class="text-muted">Auto-generated</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name">
                                <div class="invalid-feedback" id="first_name_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter last name">
                                <div class="invalid-feedback" id="last_name_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select" id="gender">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback" id="gender_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" id="dob">
                                <div class="invalid-feedback" id="dob_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Religion</label>
                                <input type="text" name="religion" class="form-control" placeholder="e.g., Islam" id="religion">
                                <div class="invalid-feedback" id="religion_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">B-Form No.</label>
                                <input type="text" name="b_form_no" class="form-control" placeholder="1234-5678901-2" id="b_form_no">
                                <div class="invalid-feedback" id="b_form_no_error"></div>
                            </div>
                            {{-- <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="0300-1234567" id="phone">
                                <div class="invalid-feedback" id="phone_error"></div>
                            </div> --}}
                            <div class="col-md-4">
                                <label class="form-label">Previous School Details</label>
                                <input type="text" name="previous_school_details" class="form-control" placeholder="Last school name" id="previous_school_details">
                                <div class="invalid-feedback" id="previous_school_details_error"></div>
                            </div>
                             <div class="col-md-4">
                                <label class="form-label">Previous Class</label>
                                <select name="previous_class" class="form-select" id="previous_class">
                                    <option value="">Select Previous Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->class_name }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="previous_class_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label">Cause of Leaving</label>
                                <input type="text" name="cause_of_leaving_school" class="form-control" placeholder="Reason for leaving" id="cause_of_leaving_school">
                                <div class="invalid-feedback" id="cause_of_leaving_school_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Disability</label>
                                <select name="disability_id" id="disability_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach($disabilities as $disability)
                                        <option value="{{ $disability->id }}">{{ $disability->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="disability_id_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3" id="disability_fields" style="display: none;">
                            <div class="col-md-4">
                                <label class="form-label">Additional Disability Info</label>
                                <input type="text" name="additional_disability" class="form-control" placeholder="Any additional details" id="additional_disability">
                                <div class="invalid-feedback" id="additional_disability_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Disability Certificate No.</label>
                                <input type="text" name="disability_certificate_no" class="form-control" id="disability_certificate_no">
                                <div class="invalid-feedback" id="disability_certificate_no_error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== GUARDIAN INFORMATION ==================== -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-people me-2"></i> Guardian Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Father's Name</label>
                                <input type="text" name="father_name" class="form-control" id="father_name" placeholder="Enter father's name">
                                <div class="invalid-feedback" id="father_name_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Father's CNIC</label>
                                <input type="text" name="father_cnic" class="form-control" placeholder="35201-1234567-8" id="father_cnic">
                                <div class="invalid-feedback" id="father_cnic_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Father's Occupation</label>
                                <input type="text" name="father_occupation" class="form-control" placeholder="Enter occupation" id="father_occupation">
                                <div class="invalid-feedback" id="father_occupation_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Mother's Name</label>
                                <input type="text" name="mother_name" class="form-control" id="mother_name" placeholder="Enter mother's name">
                                <div class="invalid-feedback" id="mother_name_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mother's Education</label>
                                <input type="text" name="mother_education" class="form-control" placeholder="Enter education" id="mother_education">
                                <div class="invalid-feedback" id="mother_education_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Family Monthly Income</label>
                                <input type="text" name="family_monthly_income" class="form-control" placeholder="e.g., 50000" id="family_monthly_income">
                                <div class="invalid-feedback" id="family_monthly_income_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Guardian Phone</label>
                                <input type="text" name="guardian_phone" class="form-control" placeholder="0300-1234567" id="guardian_phone">
                                <div class="invalid-feedback" id="guardian_phone_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Emergency Contact</label>
                                <input type="text" name="emergency_contact" class="form-control" placeholder="0300-7654321" id="emergency_contact">
                                <div class="invalid-feedback" id="emergency_contact_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Complete Address</label>
                                <input type="text" name="complete_address" class="form-control" placeholder="House #, Street, City" id="complete_address">
                                <div class="invalid-feedback" id="complete_address_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Postal Address</label>
                                <textarea name="postal_address" class="form-control" rows="2" placeholder="Postal address" id="postal_address"></textarea>
                                <div class="invalid-feedback" id="postal_address_error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== ADMISSION INFORMATION ==================== -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-mortarboard me-2"></i> Admission Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Admission No.</label>
                                <input type="text" name="admission_no" class="form-control" value="ADM-{{ date('Y') }}-{{ str_pad(($admissionCount + 1), 4, '0', STR_PAD_LEFT) }}" readonly id="admission_no">
                                <small class="text-muted">Auto-generated</small>
                                <div class="invalid-feedback" id="admission_no_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Admission Date</label>
                                <input type="date" name="admission_date" class="form-control" value="{{ date('Y-m-d') }}" id="admission_date">
                                <div class="invalid-feedback" id="admission_date_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Applied For Class</label>
                                <select name="applied_class_id" class="form-select" id="applied_class_id">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="applied_class_id_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Remarks (if any)</label>
                                <textarea name="remarks" class="form-control" rows="2" placeholder="Any additional remarks..." id="remarks"></textarea>
                                <div class="invalid-feedback" id="remarks_error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== FEE INFORMATION ==================== -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-currency-rupee me-2"></i> Fee Information</h6>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> Fee will be pending initially. You can collect payment later.
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Fee Type</label>
                                <select name="fee_type" class="form-select" id="fee_type">
                                    <option value="">Select Fee Type</option>
                                    <option value="monthly">Monthly Fee</option>
                                    <option value="tuition">Tuition Fee</option>
                                    <option value="admission">Admission Fee</option>
                                    <option value="exam">Exam Fee</option>
                                    <option value="annual">Annual Fee</option>
                                    <option value="transport">Transport Fee</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback" id="fee_type_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Amount (PKR)</label>
                                <input type="number" name="amount" class="form-control" step="0.01" id="amount" placeholder="Enter amount">
                                <div class="invalid-feedback" id="amount_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control" id="due_date">
                                <div class="invalid-feedback" id="due_date_error"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Fine Amount (if late)</label>
                                <input type="number" name="fine_amount" class="form-control" step="0.01" value="0" id="fine_amount">
                                <div class="invalid-feedback" id="fine_amount_error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount Amount</label>
                                <input type="number" name="discount_amount" class="form-control" step="0.01" value="0" id="discount_amount">
                                <div class="invalid-feedback" id="discount_amount_error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== HIDDEN FIELDS (Office Use Only) ==================== -->
                    <input type="hidden" name="status" value="pending">
                    <input type="hidden" name="class_id" value="">
                    <input type="hidden" name="section_id" value="">
                    <input type="hidden" name="roll_no" value="">
                    <input type="hidden" name="approved_by_officer" value="">
                    <input type="hidden" name="approved_by_head" value="">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> This application will be submitted as <span class="badge bg-warning">PENDING</span>.
                        Office staff will review and approve/reject the admission after document verification.
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Submit Application
                        </button>
                        <a href="{{ route('admissions.index') }}" class="btn btn-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // ✅ Disability Dynamic Fields
    $('#disability_id').on('change', function() {
        if ($(this).val()) {
            var selectedText = $(this).find('option:selected').text();
            if (selectedText !== 'None') {
                $('#disability_fields').slideDown();
            } else {
                $('#disability_fields').slideUp();
                $('input[name="additional_disability"]').val('');
                $('input[name="disability_certificate_no"]').val('');
            }
        } else {
            $('#disability_fields').slideUp();
        }
    });

    // ✅ Image Preview
    $('#student_image').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
            $('#student_image_error').text('');
        }
    });

    // ✅ Clear errors on input/change
    $(document).on('input change', '.form-control, .form-select', function() {
        $(this).removeClass('is-invalid');
        var fieldName = $(this).attr('name');
        if (fieldName) {
            $('#' + fieldName + '_error').text('');
        }
    });

    // ✅ Form Submit
    $('#admissionForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        clearErrors();

        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                submitBtn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Submit Application');

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Something went wrong!',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Submit Application');

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    // Display validation errors on form
                    displayErrors(xhr.responseJSON.errors);

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error!',
                        text: 'Please correct the highlighted fields.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    var errorMsg = xhr.responseJSON?.message || 'Something went wrong!';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg,
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });

  // ✅ Function to display errors - IMPROVED VERSION
function displayErrors(errors) {
    console.log('Errors received:', errors); // Debug log

    $.each(errors, function(field, messages) {
        console.log('Processing field:', field, 'Message:', messages[0]); // Debug log

        // Find the field by name
        var fieldElement = $('[name="' + field + '"]');
        console.log('Field element found:', fieldElement.length); // Debug log

        if (fieldElement.length) {
            // Add is-invalid class (red border)
            fieldElement.addClass('is-invalid');

            // Try multiple ways to find error div
            var errorDiv = $('#' + field + '_error'); // Original way

            // If not found, try with different patterns
            if (!errorDiv.length) {
                // Try finding by data attribute or class
                errorDiv = fieldElement.closest('.col-md-4, .col-md-12').find('.invalid-feedback');
            }

            if (errorDiv.length) {
                errorDiv.text(messages[0]);
                errorDiv.show(); // Make sure it's visible
            } else {
                // If no specific error div, create one
                var parentDiv = fieldElement.closest('.col-md-4, .col-md-12');
                if (parentDiv.length) {
                    // Remove any existing error
                    parentDiv.find('.field-error').remove();
                    parentDiv.append('<div class="text-danger small field-error">' + messages[0] + '</div>');
                } else {
                    // Fallback: add error after the field
                    fieldElement.after('<div class="text-danger small field-error">' + messages[0] + '</div>');
                }
            }
        } else {
            // If field not found, show error in alert
            var errorContainer = $('#form-errors');
            if (!errorContainer.length) {
                $('#admissionForm').prepend('<div id="form-errors"></div>');
                errorContainer = $('#form-errors');
            }
            errorContainer.append('<div class="alert alert-danger">' + field + ': ' + messages[0] + '</div>');
        }
    });

    // Scroll to first error
    var firstError = $('.is-invalid:first');
    if (firstError.length) {
        $('html, body').animate({
            scrollTop: firstError.offset().top - 100
        }, 500);
        firstError.focus();
    }
}

    // ✅ Function to clear errors
    function clearErrors() {
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('.field-error').remove();
        $('#form-errors').empty();
    }

    // ✅ Add error container at top
    $('#admissionForm').prepend('<div id="form-errors"></div>');
});
</script>

<style>
    .is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .invalid-feedback {
        display: block !important;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .field-error {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush
