  @include('components.admin-header')

  <!-- ======= Header ======= -->
@include('components.admin-top-header')
  <!-- ======= Sidebar ======= -->
 @include('components.admin-side-header')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Students Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Students</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">All Students</h5>
                  <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fullscreenModal">
                      <i class="bi bi-plus"></i> Add Student
                  </a>
              </div>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Passport</th>
                    <th>
                      <b>N</b>ame
                    </th>
                    <th>Matric Number</th>
                    <th>Department</th>
                    <th>Last Modified Date</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($students as $student)
                  <tr>
                    <td>
                        <img src="{{ $student->passport }}" alt="Profile Image" width="50" height="50">
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->matric_no }}</td>
                    <td>{{ $student->department }}</td>
                    <td>{{ $student->updated_at }}</td>
                    <td>
                      <a href="{{ url('admin/student-info', $student->id) }}">
                        <button class="btn btn-info">View</button>
                    </a>
                      <button class="btn btn-danger delete-btn" data-id="{{ $student->id }}">Delete</button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>
    <!-----model---->
<div class="modal fade" id="fullscreenModal" tabindex="-1">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add a new student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="card">
        <div class="card-body">
          <p>Kindly provide all the details of the student</p>
          <form class="row g-3 needs-validation" id="userForm">
            <div class="col-md-4">
              <input type="text" class="form-control" name="name" placeholder="Name" required>
            </div>

            <div class="col-md-4">
              <input type="text" class="form-control" name="matric_no" placeholder="Matric No" required>
            </div>

            <div class="col-md-4">
              <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>


            <div class="col-md-4">
              <select class="form-control" id="department" name="department" required>
                <option value="">-- Select Department --</option>
               <option value="Computer Science">Computer Science</option>
                <option value="Electrical/Electronic Engineering">Electrical/Electronic Engineering</option>
                <option value="Civil Engineering">Civil Engineering</option>
                <option value="Architecture">Architecture</option>
                <option value="Business Administration">Business Administration</option>
                <option value="Accountancy">Accountancy</option>
                <option value="Science Laboratory Technology">Science Laboratory Technology</option>
                <option value="Fine and Applied Arts">Fine & Applied Arts</option>
                <option value="Agricultural Technology">Agricultural Technology</option>
                <option value="Computer Engineering">Computer Engineering</option>
                <option value="Estate Management">Estate Management</option>
                <option value="Quantity Surveying">Quantity Surveying</option>
              </select>
            </div>

            <div class="col-md-4">
              <select class="form-control" id="school" name="school" required>
                <option value="">-- Select School --</option>
                <option value="science">School of Science</option>
                <option value="engineering">School of Engineering</option>
                <option value="business">School of Business & Management Studies</option>
                <option value="environmental">School of Environmental Studies</option>
              </select>
            </div>

            <div class="col-md-4">
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <div class="col-md-4">
              <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
            </div>

            <!-- Webcam Section -->
            <div class="col-md-4">
              <div id="my_camera" class="mb-2" style="width: 320px; height: 240px; border: 1px solid black;"></div>
              <input type="hidden" name="passport" id="passport">
              <button type="button" class="btn btn-primary" onclick="take_snapshot()">Capture Image</button>
              <img id="captured_image" src="" alt="Captured Passport Image" style="display: none; width: 200px; margin-top: 10px;" />
            </div>

            <!-- Fingerprint Capture Section -->
            <div class="col-12">
              <button type="button" id="fingerprintBtn" class="btn btn-primary"><i class="bi bi-fingerprint"></i>Capture Fingerprint</button>
              <input type="hidden" name="fingerprint" id="fingerprintData">
              <div id="fingerprintMessage" class="mt-2"></div>
            </div>

            <!-- Add a preview image element somewhere near your preview -->
<div id="fingerprintPreviewWrap" style="display:flex;gap:12px;align-items:center;">
  <img id="fingerprintImg" alt="Fingerprint preview" style="width:160px;height:240px;object-fit:cover;border:1px solid #e1e1e1;display:none;">
  <pre id="fingerprintPreview" style="background:#f8f9fa;padding:10px;max-width:420px;white-space:pre-wrap;word-break:break-all;"></pre>
</div>


            <!-- Debug Preview -->
         <!--    <div class="col-12">
              <label class="form-label">Preview:</label>
              <pre id="fingerprintPreview" style="background:#f8f9fa;padding:10px;"></pre>
            </div> -->

            <div class="text-center">
              <button type="submit" id="registerBtn" class="btn btn-success">
                <span id="btnText">Register</span>
                <span id="btnLoader" style="display: none;">Loading...</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div><!-- End Full Screen Modal -->

  </main><!-- End #main -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
     $(document).ready(function () {
      // ---------------------------
      // Initialize Webcam for Passport Capture
      // ---------------------------
      Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
      });
      Webcam.attach('#my_camera');

      // Capture Passport Image
      window.take_snapshot = function () {
        Webcam.snap(function (data_uri) {
          $('#passport').val(data_uri);
          $('#captured_image').attr('src', data_uri).show();
          Swal.fire({
            title: 'Image Captured!',
            text: 'Your passport image has been taken.',
            imageUrl: data_uri,
            imageWidth: 200,
            imageHeight: 200,
            imageAlt: 'Captured Image'
          });
        });
      };

      // ---------------------------
      // Fingerprint Capture Functions
      // ---------------------------
      // $('#fingerprintBtn').on('click', function () {
      //   $('#fingerprintMessage').html('Capturing fingerprint...');

      //   $.ajax({
      //     url: '/fingerprint/capture',
      //     type: 'POST',
      //     data: {_token: '{{ csrf_token() }}'},
      //     success: function(response) {
      //       if (response.success) {
      //         $('#fingerprintData').val(response.data);
      //         $('#fingerprintPreview').text(response.data);
      //         $('#fingerprintMessage').html('<span class="text-success">' + response.message + '</span>');
      //       } else {
      //         $('#fingerprintMessage').html('<span class="text-danger">' + response.message + '</span>');
      //       }
      //     },
      //     error: function(xhr) {
      //       $('#fingerprintMessage').html('<span class="text-danger">Error: ' + xhr.responseJSON.message + '</span>');
      //     }
      //   });
      // });

      // ---------------------------
      // Form Submission Handler
      // ---------------------------
      $('#userForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        // Show loading spinner and disable the button
        $("#btnText").hide();
        $("#btnLoader").show();
        $("#registerBtn").attr("disabled", true);

        // Gather form data
        var formData = {
          name: $("input[name='name']").val(),
          matric_no: $("input[name='matric_no']").val(),
          email: $("input[name='email']").val(),
          school: $("#school").val(),
          password: $("input[name='password']").val(),
          password_confirmation: $("input[name='password_confirmation']").val(),
          department: $("#department").val(),
          passport: $("#passport").val(),
          fingerprint: $("#fingerprintData").val()
        };

        $.ajax({
          url: "{{ route('student.store') }}", // Adjust this route for your backend
          type: "POST",
          data: formData,
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          success: function (response) {
            Swal.fire({
              icon: 'success',
              title: 'Registration Successful!',
              text: response.success
            }).then(function () {
              window.location.reload();
            });
          },
          error: function (xhr) {
            Swal.close();
            if (xhr.status === 422) { // Validation error
              let errors = xhr.responseJSON.errors;
              let errorMessages = "";
              $.each(errors, function (key, messages) {
                errorMessages += messages[0] + "<br>";
              });
              Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: errorMessages
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON.message || 'Something went wrong. Please try again.'
              });
            }
          },
          complete: function () {
            $("#btnText").show();
            $("#btnLoader").hide();
            $("#registerBtn").attr("disabled", false);
          }
        });
      });
    });

$(document).ready(function() {
    $('.delete-btn').click(function() {
        var studentId = $(this).data('id'); // Get student ID
        
        // Use SweetAlert for passkey input instead of a prompt
        Swal.fire({
            title: 'Enter Passkey',
            input: 'text',
            inputLabel: 'Please enter the passkey to delete this student:',
            inputAttributes: {
                autocapitalize: 'off',
                maxlength: 8
            },
            showCancelButton: true,
            confirmButtonText: 'Delete Student',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) {
                    return 'Passkey is required!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var passkey = result.value; // Get the entered passkey
                
                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the student record.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with AJAX request to delete student
                        $.ajax({
                            url: '/admin/students/' + studentId,  // Assuming the route is /students/{id}
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',  // CSRF token for security
                                passkey: passkey,  // Passkey for validation
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        'The student has been deleted.',
                                        'success'
                                    );
                                    // Remove the row from the table
                                    $('button[data-id="' + studentId + '"]').closest('tr').remove();
                                } else {
                                    // Show backend error message from response
                                    Swal.fire(
                                        'Error!',
                                        response.message || 'An error occurred, please try again.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                // Show error if there's an issue with the AJAX request
                                var errorMessage = xhr.responseJSON?.message || 'There was an issue deleting the student. Please try again.';
                                Swal.fire(
                                    'Error!',
                                    errorMessage,
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        });
    });
});

// realistic mock fingerprint generator + click handler
(function () {
  // Generate a pseudo-real fingerprint PNG as a dataURL
  function generateFakeFingerprintDataUrl(width = 320, height = 480) {
    const canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    const ctx = canvas.getContext('2d');

    // background
    ctx.fillStyle = '#fafafa';
    ctx.fillRect(0, 0, width, height);

    // base smudge
    const grd = ctx.createLinearGradient(0, 0, 0, height);
    grd.addColorStop(0, '#f5f5f6');
    grd.addColorStop(1, '#ededee');
    ctx.fillStyle = grd;
    ctx.fillRect(0, 0, width, height);

    // fingerprint parameters
    const centerX = width / 2 + (Math.random() - 0.5) * 20;
    const centerY = height * 0.45 + (Math.random() - 0.5) * 30;
    const maxRadius = Math.min(width, height) * 0.45;
    const loops = 28 + Math.floor(Math.random() * 8); // number of ridges
    const jitter = 6; // jitter amount
    const baseAlpha = 0.14;

    // draw many arcs/curves to simulate ridges
    ctx.lineWidth = 1.6;
    ctx.lineCap = 'round';
    for (let i = 0; i < loops; i++) {
      const radius = (i / loops) * maxRadius + 6;
      const points = 90; // resolution of the curve
      ctx.beginPath();
      for (let p = 0; p <= points; p++) {
        // angle runs across semicircle-ish, but we create oval loops
        const t = (p / points) * Math.PI * 2;
        // make fingerprint-style elongated loops by scaling y
        const x = centerX + Math.cos(t) * (radius + Math.sin(i * 0.6 + p * 0.2) * (jitter * 0.3));
        const y = centerY + Math.sin(t) * (radius * 0.65 + Math.cos(i * 0.4 + p * 0.3) * (jitter * 0.25));
        if (p === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
      }
      // stroke with slight darkness variation per loop
      ctx.strokeStyle = `rgba(15,15,15,${(baseAlpha + Math.random() * 0.06).toFixed(3)})`;
      ctx.stroke();
    }

    // add finer short strokes to mimic friction ridges
    ctx.lineWidth = 0.8;
    for (let s = 0; s < 1200; s++) {
      const rx = Math.random() * width;
      const ry = Math.random() * height;
      const len = 4 + Math.random() * 12;
      const ang = (Math.random() - 0.5) * 1.5;
      ctx.beginPath();
      ctx.moveTo(rx, ry);
      ctx.lineTo(rx + Math.cos(ang) * len, ry + Math.sin(ang) * len);
      ctx.strokeStyle = `rgba(20,20,20,${(0.02 + Math.random() * 0.06).toFixed(3)})`;
      ctx.stroke();
    }

    // subtle gaussian-like blur imitation: draw translucent white and black overlay
    ctx.globalCompositeOperation = 'soft-light';
    ctx.fillStyle = 'rgba(255,255,255,0.02)';
    ctx.fillRect(0, 0, width, height);
    ctx.fillStyle = 'rgba(0,0,0,0.02)';
    ctx.fillRect(0, 0, width, height);
    ctx.globalCompositeOperation = 'source-over';

    // add noise (grain)
    const imageData = ctx.getImageData(0, 0, width, height);
    const d = imageData.data;
    for (let i = 0; i < d.length; i += 4) {
      const n = (Math.random() - 0.5) * 20; // noise intensity
      d[i] = Math.min(255, Math.max(0, d[i] + n));
      d[i + 1] = Math.min(255, Math.max(0, d[i + 1] + n));
      d[i + 2] = Math.min(255, Math.max(0, d[i + 2] + n));
      // keep alpha
    }
    ctx.putImageData(imageData, 0, 0);

    // vignette to give depth
    const vGrad = ctx.createRadialGradient(centerX, centerY, maxRadius * 0.2, centerX, centerY, maxRadius * 1.1);
    vGrad.addColorStop(0, 'rgba(0,0,0,0)');
    vGrad.addColorStop(1, 'rgba(0,0,0,0.25)');
    ctx.fillStyle = vGrad;
    ctx.fillRect(0, 0, width, height);

    // finalize
    return canvas.toDataURL('image/png');
  }

  // click handler - generates mock fingerprint and fills input + preview
$('#fingerprintBtn').off('click').on('click', function () {
  $('#fingerprintMessage').html('Capturing fingerprint...');

  // mimic real capture time (0.8s - 1.6s)
  const wait = 800 + Math.floor(Math.random() * 900);

  setTimeout(() => {
    const dataUrl = generateFakeFingerprintDataUrl(320, 480);
    const base64 = dataUrl.split(',')[1]; // full PNG base64

    // TRUNCATE base64 to 200 chars (per your request)
    const truncatedBase64 = base64.length > 200 ? base64.substring(0, 200) : base64;

    // store only truncated base64 in hidden input
    $('#fingerprintData').val(truncatedBase64);

    // show image preview (full image for UX)
    $('#fingerprintImg').attr('src', dataUrl).show();

    // show truncated preview text (200 chars)
    $('#fingerprintPreview').text(truncatedBase64 + (base64.length > 200 ? '...' : ''));

    $('#fingerprintMessage').html('<span class="text-success">Fingerprint captured successfully 100% captured.</span>');
  }, wait);
});

})();


</script>



  @include('components.admin-footer')