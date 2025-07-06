<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    $('#subscribeBtn').click(function () {
        let email = $("#newsletterEmail").val();

        if (!email) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please enter a valid email address.'
            });
            return;
        }

        // Show loader
        $("#subscribeText").hide();
        $("#subscribeLoader").show();
        $("#subscribeBtn").attr("disabled", true);

        $.ajax({
            url: "{{ route('newsletter.subscribe') }}", // Laravel route
            type: "POST",
            data: {
                email: email,
                _token: "{{ csrf_token() }}" // Laravel CSRF token
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Subscribed!',
                    text: response.success
                });
                $("#newsletterEmail").val(''); // Clear input field
            },
            error: function (xhr) {
                let errorMessage = "Something went wrong!";
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    errorMessage = errors.email ? errors.email[0] : errorMessage;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            },
            complete: function () {
                // Reset button state
                $("#subscribeText").show();
                $("#subscribeLoader").hide();
                $("#subscribeBtn").attr("disabled", false);
            }
        });
    });
});
</script>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark footer mt-5 py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Info</h5>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Redemption City, Ogun State, Nigeria, Obafemi Owode, Ogun (state).</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+234 916 089 5510</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@rectemchapel.com</p>
                    <div class="d-flex pt-3">
                        <a class="btn btn-square btn-primary rounded-circle me-2" href="https://www.instagram.com/rectemchapel/"><i
                                class="fab fa-instagram"></i></a>
                        <a class="btn btn-square btn-primary rounded-circle me-2" href="https://web.facebook.com/therectemchapel"><i
                                class="fab fa-facebook"></i></a>
                        <a class="btn btn-square btn-primary rounded-circle me-2" href="https://africtv.com.ng/account/@RECTEMCHAPEL"><i
                                class="fab">AfricTv</i></a>                    
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <a class="btn btn-link" href="{{ url('about') }}">About</a>
                    <a class="btn btn-link" href="{{ url('departments') }}">Departments</a>
                    <a class="btn btn-link" href="{{ url('gallery') }}">Gallery</a>
                    <a class="btn btn-link" href="{{ url('contact') }}">Contact</a>
                    <a class="btn btn-link" href="">Online Giving</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Service Hours</h5>
                    <p class="mb-1">Sundays</p>
                    <h6 class="text-light">8:00 am</h6>
                    <p class="mb-1">Tuesday</p>
                    <h6 class="text-light">5:30-7:00 pm</h6>
                    <p class="mb-1">Wednesday (Joint Student Devotion)</p>
                    <h6 class="text-light">7:30 am</h6>
                    <p class="mb-1">Thursday</p>
                    <h6 class="text-light">5.30-7.00pm</h6>
                </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Newsletter</h5>
                        <div class="position-relative w-100">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="email" id="newsletterEmail"
                                name="email" placeholder="Your email" required>
                            <button type="button" id="subscribeBtn"
                                class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">
                                <span id="subscribeText">SignUp</span>
                                <span id="subscribeLoader" style="display: none;">Subscribing...</span>
                            </button>
                        </div>
                    </div>

            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container text-center">
            <p class="mb-2">Copyright &copy; <a class="fw-semi-bold" href="#">2025</a>, All Right Reserved.
            </p>
            <p class="mb-0">Designed and Developed
                By: <a href="">RECTEM CHAPEL MEDIA</a> </p>
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i
            class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>