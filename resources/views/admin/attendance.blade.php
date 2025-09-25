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

        <!-- Matric Number Check -->
        <div class="mt-3">
            <input type="text" id="matricNumber" class="form-control d-inline-block w-auto" placeholder="Enter Matric Number">
            <button class="btn btn-success" id="matricCheckin">Check In</button>
            <button class="btn btn-warning" id="matricCheckout">Check Out</button>
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
    const matricInput = document.getElementById('matricNumber');
    const matricCheckinBtn = document.getElementById('matricCheckin');
    const matricCheckoutBtn = document.getElementById('matricCheckout');

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
            if (presentStudents.has(student.id)) presentList.appendChild(li);
            else absentList.appendChild(li);
        });
    }

    updateLists();

    // SCAN ATTENDANCE
    scanBtn.addEventListener('click', async () => {
        scanBtn.disabled = true;
        scanText.textContent = 'Loading...';
        scanStatus.innerHTML = '<span class="text-info">Capturing fingerprint...</span>';

        try {
            const captureRes = await fetch('{{ route("fingerprint.capture") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({})
            });

            const captureText = await captureRes.text();
            let captureData;
            try { captureData = JSON.parse(captureText); } 
            catch { throw new Error('Invalid response from fingerprint capture'); }

            if (!captureData.success) throw new Error(captureData.message);
            const fingerprintBase64 = captureData.data;

            const markRes = await fetch('{{ route("attendance.mark") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({
                    fingerprint: fingerprintBase64,
                    service: '{{ $time->service ?? "" }}',
                    service_time: '{{ $time->time ?? "" }}'
                })
            });

            const markText = await markRes.text();
            let markData;
            try { markData = JSON.parse(markText); } 
            catch { throw new Error('Invalid response from attendance mark'); }

            if (markData.error) throw new Error(markData.error);

            const student = markData.student;
            const statusText = markData.is_late ? 'Late' : 'Present';
            scanStatus.innerHTML = `<span class="text-success">${student.name} marked ${statusText}</span>`;
            Swal.fire('Success', `${student.name} marked ${statusText}`, 'success');
            presentStudents.add(student.id);
            updateLists();
        } catch(err) {
            scanStatus.innerHTML = `<span class="text-danger">${err.message}</span>`;
            Swal.fire('Error', err.message, 'error');
        } finally {
            scanBtn.disabled = false;
            scanText.textContent = 'Scan Fingerprint';
        }
    });

    // CHECKOUT
    checkoutBtn.addEventListener('click', async () => {
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Loading...';

        try {
            const captureRes = await fetch('{{ route("fingerprint.capture") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({})
            });

            const captureText = await captureRes.text();
            let captureData;
            try { captureData = JSON.parse(captureText); } 
            catch { throw new Error('Invalid response from fingerprint capture'); }

            if (!captureData.success) throw new Error(captureData.message);
            const fingerprintBase64 = captureData.data;

            const res = await fetch('{{ route("attendance.checkout") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({
                    fingerprint: fingerprintBase64,
                    service: '{{ $time->service ?? "" }}',
                    service_time: '{{ $time->time ?? "" }}'
                })
            });

            const resText = await res.text();
            let data;
            try { data = JSON.parse(resText); } 
            catch { throw new Error('Invalid response from checkout'); }

            if (data.error) throw new Error(data.error);

            scanStatus.innerHTML = `<span class="text-success">${data.student.name} checked out successfully</span>`;
            Swal.fire('Success', `${data.student.name} checked out`, 'success');

        } catch(err) {
            scanStatus.innerHTML = `<span class="text-danger">${err.message}</span>`;
            Swal.fire('Error', err.message, 'error');
        } finally {
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = 'Checkout';
        }
    });

    // MATRIC NUMBER CHECK IN / CHECK OUT
    async function handleMatric(action) {
        const matric = matricInput.value.trim();
        if(!matric) return Swal.fire('Error','Please enter a matric number','error');

        const btn = action==='checkin'? matricCheckinBtn : matricCheckoutBtn;
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Loading...';

        try {
            const url = action==='checkin' ? '{{ route("attendance.mark.matric") }}' : '{{ route("attendance.checkout.matric") }}';
            const res = await fetch(url, {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify({
                    matric_number: matric,
                    service: '{{ $time->service ?? "" }}',
                    service_time: '{{ $time->time ?? "" }}'
                })
            });

            const text = await res.text();
            let data;
            try { data = JSON.parse(text); } 
            catch { throw new Error('Invalid JSON response from server'); }

            if(data.error) throw new Error(data.error);

            Swal.fire('Success', `${data.student.name} ${action==='checkin'?'checked in':'checked out'}`, 'success');
            if(action==='checkin') presentStudents.add(data.student.id);
            updateLists();
            matricInput.value = '';
        } catch(err) {
            Swal.fire('Error', err.message, 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }

    matricCheckinBtn.addEventListener('click', ()=>handleMatric('checkin'));
    matricCheckoutBtn.addEventListener('click', ()=>handleMatric('checkout'));
});
</script>

@include('components.admin-footer')
