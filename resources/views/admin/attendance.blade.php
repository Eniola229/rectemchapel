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
            <h4>Service: {{ $time->service ?? 'None Scheduled' }}</h4>
            <div>
                <button class="btn btn-primary" id="scanFingerprint" {{ $attendanceClosed ? 'disabled' : '' }}>
                    <i class="bi bi-fingerprint"></i> <span id="scanText">Scan Fingerprint</span>
                </button>

                <button class="btn btn-warning" id="checkoutBtn" {{ $attendanceClosed ? 'disabled' : '' }}>
                    <i class="bi bi-box-arrow-right"></i> Checkout
                </button>

                <p id="scanStatus" class="mt-2"></p>
            </div>
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
document.addEventListener('DOMContentLoaded', () => {
    const scanBtn = document.getElementById('scanFingerprint');
    const scanText = document.getElementById('scanText');
    const scanStatus = document.getElementById('scanStatus');
    const checkoutBtn = document.getElementById('checkoutBtn');

    const presentStudents = new Set(@json($presentIds));
    const students = @json($students);

    function updateLists() {
        const presentList = document.getElementById('presentList');
        const absentList = document.getElementById('absentList');

        presentList.innerHTML = '';
        absentList.innerHTML = '';

        if (!students.length) {
            presentList.innerHTML = '<li class="list-group-item">No students available</li>';
            absentList.innerHTML = '<li class="list-group-item">No students available</li>';
            return;
        }

        students.forEach(student => {
            const li = document.createElement('li');
            li.classList.add('list-group-item');
            li.textContent = student.name;

            if (presentStudents.has(student.id)) {
                presentList.appendChild(li);
            } else {
                absentList.appendChild(li);
            }
        });
    }

    updateLists();

    // MARK ATTENDANCE
    scanBtn.addEventListener('click', async () => {
        scanBtn.disabled = true;
        scanText.textContent = 'Scanning...';
        scanStatus.innerHTML = '<span class="text-info">Capturing fingerprint...</span>';

        try {
            const captureRes = await fetch('{{ route("fingerprint.capture") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });
            const captureData = await captureRes.json();

            if (!captureData.success) {
                scanStatus.innerHTML = `<span class="text-danger">${captureData.message}</span>`;
                scanBtn.disabled = false;
                scanText.textContent = 'Scan Fingerprint';
                return;
            }

            const fingerprintBase64 = captureData.data;

            const markRes = await fetch('{{ route("attendance.mark") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    fingerprint: fingerprintBase64,
                    service: '{{ $time->service ?? "" }}',
                    service_time: '{{ $time->time ?? "" }}'
                })
            });

            const markData = await markRes.json();

            if (markData.error) {
                scanStatus.innerHTML = `<span class="text-danger">${markData.error}</span>`;
                Swal.fire('Error', markData.error, 'error');
            } else {
                const student = markData.student;
                const statusText = markData.is_late ? 'Late' : 'Present';
                scanStatus.innerHTML = `<span class="text-success">${student.name} marked ${statusText}</span>`;
                Swal.fire('Success', `${student.name} marked ${statusText}`, 'success');

                presentStudents.add(student.id);
                updateLists();
            }

        } catch (err) {
            scanStatus.innerHTML = `<span class="text-danger">Network error: ${err.message}</span>`;
            Swal.fire('Error', `Network error: ${err.message}`, 'error');
        } finally {
            scanBtn.disabled = false;
            scanText.textContent = 'Scan Fingerprint';
        }
    });

    // CHECKOUT
    checkoutBtn.addEventListener('click', async () => {
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Scanning...';

        try {
            const captureRes = await fetch('{{ route("fingerprint.capture") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });
            const captureData = await captureRes.json();

            if (!captureData.success) {
                scanStatus.innerHTML = `<span class="text-danger">${captureData.message}</span>`;
                checkoutBtn.disabled = false;
                checkoutBtn.textContent = 'Checkout';
                return;
            }

            const fingerprintBase64 = captureData.data;

            const res = await fetch('{{ route("attendance.checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    fingerprint: fingerprintBase64,
                    service: '{{ $time->service ?? "" }}',
                    service_time: '{{ $time->time ?? "" }}'
                })
            });

            const data = await res.json();

            if (data.error) {
                Swal.fire('Error', data.error, 'error');
                scanStatus.innerHTML = `<span class="text-danger">${data.error}</span>`;
            } else {
                const student = data.student;
                scanStatus.innerHTML = `<span class="text-success">${student.name} checked out successfully</span>`;
                Swal.fire('Success', `${student.name} checked out`, 'success');
            }

        } catch (err) {
            scanStatus.innerHTML = `<span class="text-danger">Network error: ${err.message}</span>`;
            Swal.fire('Error', `Network error: ${err.message}`, 'error');
        } finally {
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = 'Checkout';
        }
    });

});
</script>

@include('components.admin-footer')
