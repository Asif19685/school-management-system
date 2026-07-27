<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:50px">#</th>
                <th>Reg. No.</th>
                <th>Student Name</th>
                <th style="width:120px">Total Marks</th>
                <th style="width:130px">Obtained Marks</th>
                <th style="width:90px">Grade</th>
                <th style="width:100px">Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
                @php $result = $results->get($student->id); @endphp
                <tr class="mark-row" data-student="{{ $student->id }}">
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td><code class="text-info">{{ $student->registration_no }}</code></td>
                    <td><strong>{{ $student->first_name }} {{ $student->last_name }}</strong></td>
                    <td>
                        <input type="number" class="form-control form-control-sm total-marks"
                               value="{{ $result ? $result->total_marks : 100 }}" min="1" required>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm obtained-marks"
                               value="{{ $result ? $result->obtained_marks : '' }}" min="0" placeholder="0">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm grade-input"
                               value="{{ $result ? $result->grade : '' }}" placeholder="A, B...">
                    </td>
                    <td>
                        <select class="form-select form-select-sm status-select">
                            <option value="pass" {{ ($result && $result->status == 'pass') ? 'selected' : '' }}>✅ Pass</option>
                            <option value="fail" {{ ($result && $result->status == 'fail') ? 'selected' : '' }}>❌ Fail</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm remarks-input"
                               value="{{ $result ? $result->remarks : '' }}" placeholder="Optional">
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-2"></i>No active students found in this class.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
