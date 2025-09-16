@include('components.admin-header')
@include('components.admin-top-header')
@include('components.admin-side-header')

<main id="main" class="main">
<div class="container mt-4">
    <h2 class="mb-4">Attendance History</h2>

    <!-- Filter + Search -->
    <div class="card mb-4 shadow-sm p-3">
        <div class="row g-3">
            <div class="col-md-3">
                <select id="filterYear" class="form-select">
                    <option value="">Filter by Year</option>
                    @foreach(range(date('Y'), 2020) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterMonth" class="form-select">
                    <option value="">Filter by Month</option>
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" id="filterDate" class="form-control" placeholder="Filter by Date">
            </div>
            <div class="col-md-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search student">
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="card mb-4 shadow-sm p-3" id="summaryCard">
        <h5>Summary</h5>
        <p>Total Present: <span id="presentCount">0</span></p>
        <p>Total Late: <span id="lateCount">0</span></p>
        <p>Total Absent: <span id="absentCount">0</span></p>
    </div>

    <!-- Attendance List -->
<div id="attendanceList">
    @forelse ($attendances as $groupKey => $records)
        @php
            $parts = explode('|', $groupKey);
            $service = $parts[0] ?? 'Unknown';
            $date = $parts[1] ?? now()->toDateString();
            $first = $records->first();
        @endphp

        <div class="card mb-3 shadow attendance-card"
             data-date="{{ $date }}"
             data-year="{{ $first->created_at->format('Y') }}"
             data-month="{{ $first->created_at->format('n') }}"
             data-student="{{ strtolower($records->pluck('student.name')->join(' ')) }}">
             
            <div class="card-header bg-primary text-white d-flex justify-content-between">
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

            <div class="card-body">
                @foreach ($records as $record)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2"
                         data-status="{{ $record->is_late ? 'late' : 'present' }}"
                         data-student="{{ strtolower($record->student->name) }}">
                        <div>
                            <strong>{{ $record->student->name }}</strong>
                            <span class="text-muted">({{ $record->student->matric_no }})</span><br>
                            <small class="text-secondary">
                                Marked at {{ $record->created_at->format('h:i A') }}
                            </small>
                        </div>
                        <span class="badge {{ $record->is_late ? 'bg-warning' : 'bg-success' }}">
                            {{ $record->is_late ? 'Late' : 'On Time' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-info">No attendance records available.</div>
    @endforelse
</div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $attendances->links() }}
    </div>
</div>
</main>

@include('components.admin-footer')

<!--JavaScript Filters & Search -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const yearFilter = document.getElementById("filterYear");
    const monthFilter = document.getElementById("filterMonth");
    const dateFilter = document.getElementById("filterDate");
    const searchInput = document.getElementById("searchInput");
    const cards = document.querySelectorAll(".attendance-card");

    const presentCountEl = document.getElementById("presentCount");
    const lateCountEl = document.getElementById("lateCount");
    const absentCountEl = document.getElementById("absentCount");

    function filterAttendance() {
        const year = yearFilter.value;
        const month = monthFilter.value;
        const date = dateFilter.value;
        const search = searchInput.value.toLowerCase();

        let totalPresent = 0;
        let totalLate = 0;
        let totalAbsent = 0;

        cards.forEach(card => {
            const cardYear = card.dataset.year;
            const cardMonth = card.dataset.month;
            const cardDate = card.dataset.date;

            let cardVisible = true;

            if (year && cardYear !== year) cardVisible = false;
            if (month && cardMonth !== month) cardVisible = false;
            if (date && cardDate !== date) cardVisible = false;

            let anyStudentVisible = false;

            // loop through students inside this card
            card.querySelectorAll("[data-status]").forEach(row => {
                const studentName = row.dataset.student;
                let rowVisible = true;

                if (search && !studentName.includes(search)) {
                    rowVisible = false;
                }

                row.style.display = rowVisible ? "flex" : "none";

                if (rowVisible) {
                    anyStudentVisible = true;
                    if (row.dataset.status === "present") totalPresent++;
                    if (row.dataset.status === "late") totalLate++;
                }
            });

            // show card only if it passes filters AND has at least one matching student
            card.style.display = (cardVisible && anyStudentVisible) ? "block" : "none";
        });

        // Absent (needs total students from backend, set to 0 for now)
        totalAbsent = 0;

        presentCountEl.textContent = totalPresent;
        lateCountEl.textContent = totalLate;
        absentCountEl.textContent = totalAbsent;
    }

    yearFilter.addEventListener("change", filterAttendance);
    monthFilter.addEventListener("change", filterAttendance);
    dateFilter.addEventListener("change", filterAttendance);
    searchInput.addEventListener("keyup", filterAttendance);

    filterAttendance();
});
</script>

