@include('components.admin-header')

@include('components.admin-top-header')
@include('components.admin-side-header')

<main id="main" class="main">
    <div class="container mt-4">
        <div class="pagetitle">
            <h1>Attendance Panel</h1>
            <p class="badge {{ $attendanceClosed ? 'bg-danger' : 'bg-success' }}" id="status">
                {{ $attendanceClosed ? 'Attendance Closed' : 'Attendance Open' }}
            </p>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <h4>Today's Service: {{ $time->service ?? 'None Scheduled' }}</h4>
            <button class="btn btn-primary" id="scanFingerprint" {{ $attendanceClosed ? 'disabled' : '' }}>
                <i class="bi bi-fingerprint"></i> Scan Fingerprint
            </button>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">Present Students</div>
                    <ul class="list-group list-group-flush" id="presentList"></ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">Absent Students</div>
                    <ul class="list-group list-group-flush" id="absentList"></ul>
                </div>
            </div>
        </div>
    </div>
</main>
 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const students = @json($students);
    const presentIds = @json($presentIds); // From backend
    const serviceTime = @json($time);
    const deadline = new Date(`{{ now()->format('Y-m-d') }}T${serviceTime?.time ?? '23:59'}`);
    const serviceName = serviceTime?.service ?? 'Unknown';

    let presentStudents = new Set(presentIds);

    function updateLists() {
        const presentList = document.getElementById('presentList');
        const absentList = document.getElementById('absentList');
        presentList.innerHTML = '';
        absentList.innerHTML = '';

        students.forEach(student => {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.textContent = `${student.name} (${student.matric_no})`;

            if (presentStudents.has(student.id)) {
                presentList.appendChild(li);
            } else {
                absentList.appendChild(li);
            }
        });
    }

    document.getElementById('scanFingerprint')?.addEventListener('click', () => {
        const now = new Date();
        if (now > deadline) {
            Swal.fire('Attendance Closed', 'Late submission not allowed', 'error');
            return;
        }

        const random = students[Math.floor(Math.random() * students.length)];

        if (presentStudents.has(random.id)) {
            Swal.fire('Already Marked', `${random.name} already marked`, 'info');
            return;
        }

        fetch('{{ route('attendance.mark') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                fingerprint: random.fingerprint,
                service: serviceName,
                service_time: deadline.toISOString()
            })
        })
        .then(async res => {
            const data = await res.json().catch(() => ({ error: 'Invalid JSON from server' }));

            if (!res.ok || data.error) {
                Swal.fire('Error', data.error || 'Something went wrong', 'error');
                return;
            }

            Swal.fire(
                data.is_late ? 'Marked Late' : 'Marked Present',
                `${data.student.name} marked ${data.is_late ? 'late' : 'on time'}`,
                data.is_late ? 'warning' : 'success'
            );

            presentStudents.add(data.student.id);
            updateLists();
        })
        .catch(err => {
            Swal.fire('Network Error', err.message, 'error');
        });
    });

    setInterval(() => {
        const status = document.getElementById('status');
        if (new Date() > deadline) {
            status.classList.replace('bg-success', 'bg-danger');
            status.textContent = 'Attendance Closed';
            document.getElementById('scanFingerprint')?.setAttribute('disabled', 'true');
        }
    }, 1000);

    window.addEventListener('DOMContentLoaded', updateLists);
</script>

@include('components.admin-footer')
