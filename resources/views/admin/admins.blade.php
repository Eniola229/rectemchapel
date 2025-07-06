  @include('components.admin-header')

  <!-- ======= Header ======= -->
@include('components.admin-top-header')
  <!-- ======= Sidebar ======= -->
 @include('components.admin-side-header')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Admins Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Admin</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">All Admin</h5>
                  <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fullscreenModal">
                      <i class="bi bi-plus"></i> Add Admin
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
                    <th>Role</th>
                    <th>Email</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($admins as $admin)
                  <tr>
                    <td>
                        <img src="{{ $admin->avatar }}" alt="Profile Image" width="50" height="50">
                    </td>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->role }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('m/d/Y, h:i A') }}</td>
                    <td>
                       <button class="btn btn-danger delete-admin-btn" data-id="{{ $admin->id }}">Delete</button>
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
                      <h5 class="modal-title">Add a new admin</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                      <div class="card">
                          <div class="card-body">
                            <p>Kindly provide all the details of the admin</p>
                                <form class="row g-3 needs-validation" id="userForm">
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>

                                    <div class="col-md-4 position-relative">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label">Role</label>
                                        <select class="form-control" name="role" required>
                                            <option value="" disabled selected>Select a role</option>
                                            <option value="admin">ADMIN</option>
                                            <option value="super">SUPER</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>

                                    <div class="col-md-4 position-relative">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation" required>
                                    </div>

                                    <!-- Webcam Section -->
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label">Passport</label>
                                        <div id="my_camera" class="mb-2" style="width: 320px; height: 240px; border: 1px solid black;"></div>
                                        <input type="hidden" name="passport" id="passport">
                                        <button type="button" class="btn btn-primary" onclick="take_snapshot()">Capture Image</button>
                                        <!-- Captured Image Preview -->
                                        <img id="captured_image" src="" alt="Captured Passport Image" style="display: none; width: 200px; margin-top: 10px;" />
                                    </div>

                                    <button type="submit" id="registerBtn" class="btn btn-success">
                                        <span id="btnText">Register</span>
                                        <span id="btnLoader" style="display: none;">Loading...</span>
                                    </button>
                                </form>


                          </div>
                        </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div><!-- End Full Screen Modal-->
  </main><!-- End #main -->

<script>
$(document).ready(function () {
    // Initialize Webcam
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach('#my_camera');

    // Capture Image
    window.take_snapshot = function () {
        Webcam.snap(function (data_uri) {
            $('#passport').val(data_uri); // Store base64 image in hidden input
            $('#captured_image').attr('src', data_uri).show(); // Show the captured image preview
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

    // Handle Form Submission
    $('#userForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        // Use a normal system prompt to ask for the passkey
        var passkey = prompt("Enter passkey to proceed:");
        if (passkey !== "20250502") {
            alert("Incorrect passkey!");
            return false; // Stop the submission if the passkey is incorrect
        }

        // Show loading spinner
        $("#btnText").hide();
        $("#btnLoader").show();
        $("#registerBtn").attr("disabled", true);

        var formData = {
            name: $("input[name='name']").val(),
            email: $("input[name='email']").val(),
            role: $("input[name='role']").val(),
            password: $("input[name='password']").val(),
            password_confirmation: $("input[name='password_confirmation']").val(),
            passport: $("#passport").val()
        };

        $.ajax({
            url: "{{ route('admin.store') }}", // Laravel route to store admin
            type: "POST",
            data: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: response.success
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr) {
                Swal.close(); // Close the loading spinner

                if (xhr.status === 422) {
                    // Laravel validation error
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";

                    $.each(errors, function (key, messages) {
                        errorMessages += messages[0] + "<br>"; // Show first error for each field
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error!',
                        html: errorMessages // Use HTML to format multiple errors
                    });
                } else {
                    // Other errors (e.g., server error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON.message || 'Something went wrong. Please try again.'
                    });
                }
            },
            complete: function () {
                // Reset button state
                $("#btnText").show();
                $("#btnLoader").hide();
                $("#registerBtn").attr("disabled", false);
            }
        });
    });
});

$(document).ready(function () {
    // Handle Delete Button Click
    $('.delete-admin-btn').on('click', function () {
        var adminId = $(this).data('id'); // Get admin ID from data attribute
        var passkey = prompt('Enter passkey to confirm deletion:'); // Prompt for passkey

        // If passkey is not provided or incorrect, exit
        if (!passkey || passkey !== '20250502') {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Passkey',
                text: 'The passkey entered is incorrect. You cannot delete this admin.',
            });
            return;
        }

        // Ask for confirmation before proceeding
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the admin!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to delete the admin
                $.ajax({
                    url: '/admin/admin/' + adminId, // Adjust with your correct route
                    type: 'DELETE',
                    data: {
                        passkey: passkey, // Pass passkey for validation
                        _token: '{{ csrf_token() }}' // CSRF Token for security
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'The admin has been deleted successfully.',
                            }).then(() => {
                                window.location.reload(); // Refresh the page to reflect the changes
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Something went wrong.',
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Failed to delete admin.',
                        });
                    }
                });
            }
        });
    });
});

</script>



  @include('components.admin-footer')