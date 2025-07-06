  @include('components.admin-header')

  <!-- ======= Header ======= -->
@include('components.admin-top-header')
  <!-- ======= Sidebar ======= -->
 @include('components.admin-side-header')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Attendance Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Attendance</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">All Attendance</h5>
                  <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fullscreenModal">
                      <i class="bi bi-plus"></i> Take Attendance
                  </a>
              </div>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      <b>N</b>ame
                    </th>
                    <th>Service</th>
                    <th>Time</th>
                    <th>Early or Late?</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                       <button class="btn btn-danger delete-admin-btn" data-id="">Late</button>
                    </td>
                  </tr>
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
                                        <input type="text" class="form-control" name="role" required>
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
  @include('components.admin-footer')