<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:40px">#</th>
                <th>Reg. No.</th>
                <th>Student Name</th>
                <th>Father's Name</th>
                <th class="text-end" style="width:200px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>
                        <code class="text-info">{{ $student->registration_no ?? 'N/A' }}</code>
                    </td>
                    <td>
                        <strong>{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</strong>
                        @if($student->admission)
                            <br><small class="text-muted">Class: {{ $student->admission->schoolClass->class_name ?? 'N/A' }}</small>
                        @endif
                    </td>
                    <td>
                        @if($student->guardian)
                            {{ $student->guardian->father_name ?? 'N/A' }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if(isset($examId))
                            <a href="{{ route('exams.result-cards.print', ['exam_id' => $examId, 'student_id' => $student->id]) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-eye me-1"></i> View
                            </a>
                            <a href="{{ route('exams.result-cards.print', ['exam_id' => $examId, 'student_id' => $student->id]) }}"
                               target="_blank"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-printer me-1"></i> Print
                            </a>
                        @else
                            <span class="text-muted text-sm">No exam selected</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-2"></i>No active students found in this class.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
