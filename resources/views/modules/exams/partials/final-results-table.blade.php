<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:40px">#</th>
                <th>Reg. No.</th>
                <th>Student Name</th>
                @foreach($subjects as $subject)
                    <th style="width:90px"><small>{{ $subject->subject_name }}</small></th>
                @endforeach
                <th style="width:100px">Grand Total</th>
                <th style="width:100px">Obtained</th>
                <th style="width:80px">% age</th>
                <th style="width:80px">Grade</th>
                <th style="width:80px">Position</th>
                <th style="width:100px">Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
                @php
                    $result = $results->get($student->id);
                    $pct    = $result ? $result->percentage : '';
                @endphp
                <tr class="result-row" data-student="{{ $student->id }}">
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td><code class="text-info">{{ $student->registration_no }}</code></td>
                    <td><strong>{{ $student->first_name }} {{ $student->last_name }}</strong></td>

                    @php
                        $studentSubjects = $subjectResults->get($student->id, collect());
                        $calculatedTotal = 0;
                        $calculatedObtained = 0;
                    @endphp

                    @foreach($subjects as $subject)
                        @php
                            $subjRes = $studentSubjects->firstWhere('subject_id', $subject->id);
                            if($subjRes) {
                                $calculatedTotal += $subjRes->total_marks;
                                $calculatedObtained += $subjRes->obtained_marks;
                            }
                        @endphp
                        <td class="text-center">
                            @if($subjRes)
                                <span class="d-block fw-bold">{{ $subjRes->obtained_marks }}</span>
                                <small class="text-muted" style="font-size:0.7rem;">/ {{ $subjRes->total_marks }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    @endforeach

                    @php
                        $finalTotal = $result ? $result->grand_total_marks : $calculatedTotal;
                        $finalObtained = $result ? $result->grand_obtained_marks : $calculatedObtained;
                        $pct = $finalTotal > 0 ? number_format(($finalObtained / $finalTotal) * 100, 2) : '0.00';
                        $status = $result ? $result->final_status : ($pct >= 33 ? 'pass' : 'fail');
                    @endphp

                    <td>
                        <input type="number" class="form-control form-control-sm total-marks"
                               value="{{ $finalTotal }}" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm obtained-marks"
                               value="{{ $finalObtained }}" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm percentage-val"
                               value="{{ $pct }}" step="0.01" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm grade-input"
                               value="{{ $result ? $result->final_grade : '' }}" placeholder="A, B…">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm position-input"
                               value="{{ $result ? $result->position : '' }}" min="1" placeholder="#">
                    </td>
                    <td>
                        <select class="form-select form-select-sm status-select">
                            <option value="pass" {{ $status == 'pass' ? 'selected' : '' }}>✅ Pass</option>
                            <option value="fail" {{ $status == 'fail' ? 'selected' : '' }}>❌ Fail</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm remarks-input"
                               value="{{ $result ? $result->remarks : '' }}" placeholder="Optional">
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 10 + count($subjects) }}" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-2"></i>No active students found in this class.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
