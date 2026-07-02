/**
 * Student Detail Modal - Common Functionality
 * Used in both Fee Management and Students Directory pages
 */

// Global function to load and show student details
window.loadStudentDetail = function(studentId, showFee = true) {
    // Show loading
    Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '/fees/' + studentId + '/student-detail',
        method: 'GET',
        success: function(response) {
            Swal.close();

            let admission = response.admission;
            let student = admission?.student;
            let guardian = student?.guardian;

            // Populate modal fields
            populateStudentData(student, admission, guardian);

            // Populate fee data based on visibility
            populateFeeData(response.fees, showFee);

            // Show modal
            $('#studentDetailModal').modal('show');
        },
        error: function(xhr) {
            Swal.close();
            let errorMsg = 'Could not fetch student details';
            if (xhr.responseJSON?.message) {
                errorMsg = xhr.responseJSON.message;
            }
            Swal.fire('Error!', errorMsg, 'error');
        }
    });
};

// Function to populate student data
function populateStudentData(student, admission, guardian) {
    // Photo and basic info
    $('#detail_student_image').attr('src', student?.image_url || '/images/default-avatar.png');
    $('#detail_full_name').text((student?.first_name || '') + ' ' + (student?.last_name || ''));
    $('#detail_class_section').text((admission?.school_class?.class_name || admission?.schoolClass?.class_name || '-') + ' (' + (admission?.section?.section_name || '-') + ')');
    $('#detail_admission_no').text(admission?.admission_no || '-');
    $('#detail_reg_no').text(student?.registration_no || '-');
    $('#detail_roll_no').text(admission?.roll_no || '-');

    let admDate = admission?.admission_date;
    if (admDate) {
        admDate = new Date(admDate).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
    }
    $('#detail_admission_date').text(admDate || '-');

    // Personal Info
    $('#detail_b_form').text(student?.b_form_no || '-');
    $('#detail_gender').text(student?.gender ? student.gender.charAt(0).toUpperCase() + student.gender.slice(1) : '-');

    let dob = student?.dob;
    if (dob) {
        dob = new Date(dob).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
    }
    $('#detail_dob').text(dob || '-');
    $('#detail_religion').text(student?.religion || '-');
    $('#detail_disability').text(student?.disability?.name || student?.additional_disability || 'None');
    $('#detail_prev_class').text(student?.previous_class || '-');
    $('#detail_prev_school').text(student?.previous_school_details || '-');
    $('#detail_remarks').text(admission?.remarks || 'None');

    // Guardian Info
    $('#detail_father_name').text(guardian?.father_name || '-');
    $('#detail_father_cnic').text(guardian?.father_cnic || '-');
    $('#detail_father_occ').text(guardian?.father_occupation || '-');
    $('#detail_mother_name').text(guardian?.mother_name || '-');
    $('#detail_mother_edu').text(guardian?.mother_education || '-');
    $('#detail_guardian_phone').text(guardian?.phone || '-');
    $('#detail_emergency_contact').text(guardian?.emergency_contact || '-');
    $('#detail_family_income').text(guardian?.family_monthly_income || '-');
    $('#detail_complete_address').text(guardian?.complete_address || '-');
    $('#detail_postal_address').text(guardian?.postal_address || '-');
}

// Function to populate fee data
function populateFeeData(fees, showFee = true) {
    let feesHtml = '';

    if (showFee && fees && fees.length > 0) {
        fees.forEach(function(fee) {
            let statusBadge = '';
            if (fee.status === 'paid') {
                statusBadge = '<span class="badge bg-success">Paid</span>';
            } else if (fee.status === 'partial') {
                statusBadge = '<span class="badge bg-info text-dark">Partial</span>';
            } else if (fee.status === 'overdue') {
                statusBadge = '<span class="badge bg-danger">Overdue</span>';
            } else {
                statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
            }

            feesHtml += `<tr>
                <td><strong>${fee.fee_type}</strong></td>
                <td>Rs. ${parseFloat(fee.amount).toFixed(2)}</td>
                <td>Rs. ${parseFloat(fee.discount_amount).toFixed(2)}</td>
                <td>Rs. ${parseFloat(fee.fine_amount).toFixed(2)}</td>
                <td class="fw-bold">Rs. ${parseFloat(fee.total_due).toFixed(2)}</td>
                <td class="text-success fw-semibold">Rs. ${parseFloat(fee.total_paid).toFixed(2)}</td>
                <td class="text-danger fw-semibold">Rs. ${parseFloat(fee.remaining).toFixed(2)}</td>
                <td>${statusBadge}</td>
            </tr>`;
        });
    } else {
        feesHtml = '<tr><td colspan="8" class="text-center text-muted py-3">No fee records found for this student.</td></tr>';
    }
    $('#detail_fee_records_body').html(feesHtml);
}

// Function to toggle fee tab visibility
window.toggleFeeTab = function(show) {
    if (show) {
        $('#fee-tab-container').show();
    } else {
        $('#fee-tab-container').hide();
        // If fee tab was active, switch to personal tab
        if ($('#fee').hasClass('show active')) {
            $('#personal-tab').tab('show');
        }
    }
};
