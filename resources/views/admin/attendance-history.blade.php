@include('components.admin-header')

@include('components.admin-top-header')
@include('components.admin-side-header')

<main id="main" class="main">
<div class="container mt-4">
    <h2 class="mb-4">Attendance History</h2>

    @forelse ($attendanceByDate as $date => $services)
        @foreach ($services as $service => $records)
            <div class="card mb-4 shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $service }}</strong><br>
                        <small>{{ \Carbon\Carbon::parse($date)->format('l, jS F Y') }}</small>
                    </div>
                    <div>
                        <a href="{{ route('attendance.export.csv', ['date' => $date, 'service' => $service]) }}"
                           class="btn btn-sm btn-light me-2">
                            <i class="bi bi-filetype-csv"></i> CSV
                        </a>
                        <a href="{{ route('attendance.export.pdf', ['date' => $date, 'service' => $service]) }}"
                           class="btn btn-sm btn-light">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                    </div>
                </div>

                <ul class="list-group list-group-flush">
                    @forelse ($records as $record)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $record->student->name }}</strong>
                                <span class="text-muted">({{ $record->student->matric_no }})</span><br>
                                <small class="text-secondary">
                                    Marked at {{ \Carbon\Carbon::parse($record->created_at)->format('h:i A') }}
                                </small>
                            </div>
                            <span class="badge {{ $record->is_late ? 'bg-warning' : 'bg-success' }}">
                                {{ $record->is_late ? 'Late' : 'On Time' }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No records for this service</li>
                    @endforelse
                </ul>
            </div>
        @endforeach
    @empty
        <div class="alert alert-info">No attendance records available.</div>
    @endforelse
</div>
</main>

@include('components.admin-footer')
