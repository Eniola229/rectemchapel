  @include('components.admin-header')

  <!-- ======= Header ======= -->
@include('components.admin-top-header')
  <!-- ======= Sidebar ======= -->
 @include('components.admin-side-header')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>TIME DATA</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">TIME</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">All TIME</h5>
                  <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fullscreenModal">
                      <i class="bi bi-plus"></i> Take TIME
                  </a>
              </div>

            <table class="table datatable" id="scheduleTable">
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Course</th>
                  <th>Time</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data will be loaded dynamically -->
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
                      <h5 class="modal-title">Add a new time</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                      <div class="card">
                          <div class="card-body">
                            <p>Kindly provide all required details</p>
                  <form class="row g-3 needs-validation" id="userForm">
                        <div class="col-md-4 position-relative">
                          <label class="form-label">Day</label>
                          <select class="form-control" name="day" required>
                            <option>MONDAY</option>
                            <option>TUESDAY</option>
                            <option>WEDNESSDAY</option>
                            <option>THURSDAY</option>
                            <option>FRIDAY</option>
                            <option>SATURDAY</option>
                            <option>SUNDAY</option>
                          </select>
                        </div>

                        <div class="col-md-4 position-relative">
                          <label class="form-label">Time</label>
                          <input type="time" class="form-control" name="time" required>
                        </div>

                        <div class="col-md-4 position-relative">
                          <label class="form-label">Service</label>
                          <input type="text" class="form-control" name="service" placeholder="Enter Service" required>
                        </div>

                        <div class="col-12">
                          <button type="submit" id="registerBtn" class="btn btn-success">
                            <span id="btnText">Add</span>
                            <span id="btnLoader" style="display: none;">Loading...</span>
                          </button>
                          <button type="button" id="cancelEdit" style="display: none;" class="btn btn-secondary">Cancel</button>
                        </div>
                      </form>

                          </div>
                        </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
  </main><!-- End #main -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $('#userForm').on('submit', function (e) {
        e.preventDefault();

        $('#registerBtn').attr('disabled', true);
        $('#btnText').hide();
        $('#btnLoader').show();

        $.ajax({
            url: "{{ route('schedule.store') }}", // Route we'll define
            type: "POST",
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                alert('Schedule added successfully!');
                $('#userForm')[0].reset();
            },
            error: function (xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong.'));
            },
            complete: function () {
                $('#registerBtn').attr('disabled', false);
                $('#btnText').show();
                $('#btnLoader').hide();
            }
        });
    });
</script>
<script>
$(document).ready(function () {
    fetchSchedules();

    function fetchSchedules() {
        $.get("{{ route('schedule.all') }}", function (data) {
            let rows = '';
            data.forEach(item => {
                rows += `
                    <tr data-id="${item.id}">
                        <td>${item.day}</td>
                        <td>${item.service}</td>
                        <td>${item.time}</td>
                        <td>
                            <button class="btn btn-primary btn-sm edit-btn" data-id="${item.id}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $('#scheduleTable tbody').html(rows);
        });
    }

    // Open modal and populate form for editing
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.get(`/admin/schedule/${id}`, function (data) {
            $('select[name="day"]').val(data.day);
            $('input[name="time"]').val(data.time);
            $('select[name="service"]').val(data.service);

            $('#userForm').attr('data-editing', id);
            $('#btnText').text('Update');
            $('#cancelEdit').show();

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('fullscreenModal'));
            modal.show();
        });
    });

    // Cancel edit mode
    $('#cancelEdit').on('click', function () {
        $('#userForm')[0].reset();
        $('#userForm').removeAttr('data-editing');
        $('#btnText').text('Add');
        $(this).hide();
    });

    // Submit form for add/update
    $('#userForm').on('submit', function (e) {
        e.preventDefault();

        const id = $(this).attr('data-editing');
        const method = id ? 'PUT' : 'POST';
        const url = id ? `/admin/schedule/update/${id}` : "{{ route('schedule.store') }}";

        $('#registerBtn').attr('disabled', true);
        $('#btnText').hide();
        $('#btnLoader').show();

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function () {
                $('#userForm')[0].reset();
                $('#userForm').removeAttr('data-editing');
                $('#btnText').text('Add');
                $('#cancelEdit').hide();

                // Hide modal
                var modalEl = document.getElementById('fullscreenModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                fetchSchedules();
            },
            complete: function () {
                $('#registerBtn').attr('disabled', false);
                $('#btnText').show();
                $('#btnLoader').hide();
            }
        });
    });

    // Delete
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure to delete this schedule?')) {
            $.ajax({
                url: `/admin/schedule/delete/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function () {
                    fetchSchedules();
                }
            });
        }
    });
});
</script>


  @include('components.admin-footer')