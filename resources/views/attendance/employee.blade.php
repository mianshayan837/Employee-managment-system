@extends('layouts.app')

@section('title', $employee->name.' — Attendance')

@section('content')

    <div class="mb-4">
        <a href="{{ route('attendance-report.today') }}" class="text-slate text-decoration-none small">← Back to today's attendance</a>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="eyebrow mb-2">Monthly report</p>
            <h1 class="font-display fw-semibold text-ink mb-0">{{ $employee->name }}</h1>
            <p class="text-slate mb-0">{{ $employee->designation }} · {{ $employee->department->name ?? '—' }}</p>
        </div>
        <form method="GET" action="{{ route('attendance-report.employee', $employee) }}">
            <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </form>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Late</th>
                        <th>Overtime</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $day)
                        <tr>
                            <td class="ps-4">{{ $day['date']->format('d M (D)') }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->check_in ? \Carbon\Carbon::parse($day['record']->check_in)->format('h:i A') : '—' }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->check_out ? \Carbon\Carbon::parse($day['record']->check_out)->format('h:i A') : '—' }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->late_minutes ? $day['record']->late_minutes.' min' : '—' }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->overtime_minutes ? $day['record']->overtime_minutes.' min' : '—' }}</td>
                            <td><span class="leave-status-pill {{ $day['color'] }}">{{ $day['label'] }}</span></td>
                            <td class="pe-4 text-end">
                                @if ($day['status'] !== 'weekend' && $day['status'] !== 'upcoming')
                                    <button type="button" class="btn btn-ink edit-attendance-btn"
                                        data-date="{{ $day['date']->format('Y-m-d') }}"
                                        data-date-label="{{ $day['date']->format('d M Y') }}"
                                        data-status="{{ $day['record']->status ?? '' }}"
                                        data-check-in="{{ $day['record'] && $day['record']->check_in ? \Carbon\Carbon::parse($day['record']->check_in)->format('H:i') : '' }}"
                                        data-check-out="{{ $day['record'] && $day['record']->check_out ? \Carbon\Carbon::parse($day['record']->check_out)->format('H:i') : '' }}"
                                        data-bs-toggle="modal" data-bs-target="#editAttendanceModal">
                                        Edit
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Shared edit modal --}}
    <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editAttendanceForm" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit attendance — <span id="modalDateLabel"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="label-mono form-label">Status</label>
                            <select name="status" id="modalStatus" class="form-select" required>
                                <option value="present">Present</option>
                                <option value="late">Late</option>
                                <option value="half_day">Half Day</option>
                                <option value="absent">Absent</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="label-mono form-label">Check In</label>
                                <input type="time" name="check_in" id="modalCheckIn" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="label-mono form-label">Check Out</label>
                                <input type="time" name="check_out" id="modalCheckOut" class="form-control">
                            </div>
                        </div>
                        <p class="text-slate small mt-2 mb-0">Leave check-in/out blank if not applicable (e.g. Absent or On Leave).</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-ink" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-ink">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-attendance-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const date = btn.dataset.date;
                document.getElementById('editAttendanceForm').action = `{{ url('attendance-report') }}/{{ $employee->id }}/${date}`;
                document.getElementById('modalDateLabel').textContent = btn.dataset.dateLabel;
                document.getElementById('modalStatus').value = btn.dataset.status || 'present';
                document.getElementById('modalCheckIn').value = btn.dataset.checkIn || '';
                document.getElementById('modalCheckOut').value = btn.dataset.checkOut || '';
            });
        });
    </script>

@endsection