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
              </select>
            </div>

            <div class="col-md-4">
              <select class="form-control" id="school" name="school" required>
                <option value="">-- Select School --</option>
                <option value="science">School of Science</option>
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

            <!-- Debug Preview -->
            <div class="col-12">
              <label class="form-label">Preview:</label>
              <pre id="fingerprintPreview" style="background:#f8f9fa;padding:10px;"></pre>
            </div>

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
      // Mock version of FingerprintSDK
      if (typeof FingerprintSDK === 'undefined') {
        var FingerprintSDK = {
          isAvailable: function () {
            return true;
          },
          captureFingerprint: function (successCallback, errorCallback) {
            setTimeout(() => {
              const mockData = "MOCKED_BASE64_FINGERPRINT_" + Math.random().toString(36).substring(2, 10);
              successCallback(mockData);
            }, 1000);
          }
        };
      }

      // Capture function using mock or real SDK
      function captureExternalFingerprint() {
        $('#fingerprintMessage').html('Capturing fingerprint...');
        if (typeof FingerprintSDK !== 'undefined' && FingerprintSDK.isAvailable()) {
          FingerprintSDK.captureFingerprint(function (fingerprintData) {
            $('#fingerprintData').val(fingerprintData);
            $('#fingerprintPreview').text(fingerprintData);
            $('#fingerprintMessage').html('<span class="text-success">Fingerprint captured successfully!</span>');
          }, function (error) {
            $('#fingerprintMessage').html('<span class="text-danger">Error capturing fingerprint: ' + error.message + '</span>');
          });
        } else {
          $('#fingerprintMessage').html('<span class="text-danger">External fingerprint scanner is not available or SDK not loaded.</span>');
        }
      }

      // Click handler
      $('#fingerprintBtn').on('click', function () {
        captureExternalFingerprint();
      });
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



</script>



  @include('components.admin-footer')