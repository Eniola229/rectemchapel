@include('components.header')

<!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 justify-content-center mb-5">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light text-center h-100 p-5">
                        <div class="btn-square bg-white rounded-circle mx-auto mb-4" style="width: 90px; height: 90px;">
                            <i class="fa fa-phone-alt fa-2x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Phone Number</h4>
                        <p class="mb-2">+234 916 089 5510</p>
                        <a class="btn btn-primary px-4" href="tel:+2349160895510">Call Now <i
                                class="fa fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="bg-light text-center h-100 p-5">
                        <div class="btn-square bg-white rounded-circle mx-auto mb-4" style="width: 90px; height: 90px;">
                            <i class="fa fa-envelope-open fa-2x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Email Address</h4>
                        <p class="mb-2">info@rectemchapel.com</p>
                        <a class="btn btn-primary px-4" href="mailto:info@rectemchapel.com">Email Now <i
                                class="fa fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="bg-light text-center h-100 p-5">
                        <div class="btn-square bg-white rounded-circle mx-auto mb-4" style="width: 90px; height: 90px;">
                            <i class="fa fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Address</h4>
                        <p class="mb-2">Redemption City, Ogun State, Nigeria, Obafemi Owode, Ogun (state).</p>
                        <a class="btn btn-primary px-4" href=""
                            target="blank">Direction <i class="fa fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                    <iframe class="w-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15846.539564853112!2d3.447717034277469!3d6.814185740933741!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bc0d7afdbeff1%3A0x8fb55c4a02c7a113!2sRedemption%20City%2C%20Pakuro%20110113%2C%20Ogun%20State!5e0!3m2!1sen!2sng!4v1738710552736!5m2!1sen!2sng"
                        frameborder="0" style="min-height: 450px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="fw-medium text-uppercase text-primary mb-2">Contact Us</p>
                    <h1 class="display-5 mb-4">If You Have Any Queries, Please Feel Free To Contact Us</h1>
                    <p class="mb-4">The contact form is currently inactive. Get a functional and working contact form
                        with Ajax & PHP in a few minutes. Just copy and paste the files, add a little code and you're
                        done. <a href="https://htmlcodex.com/contact-form">Download Now</a>.</p>
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                    <i class="fa fa-phone-alt text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h6>Call Us</h6>
                                    <span>+234 916 089 5510</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                    <i class="fa fa-envelope text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h6>Mail Us</h6>
                                    <span>info@rectemchapel.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <form id="contactForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                                    <label for="name">Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" name="message"
                                        style="height: 150px" required></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button id="submitBtn" class="btn btn-primary py-3 px-5" type="submit">
                                    <span id="btnText">Send Message</span>
                                    <span id="btnLoader" style="display: none;">Submitting...</span>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    $('#contactForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        // Show submitting loader
        $("#btnText").hide();
        $("#btnLoader").show();
        $("#submitBtn").attr("disabled", true);

        var formData = {
            name: $("#name").val(),
            email: $("#email").val(),
            subject: $("#subject").val(),
            message: $("#message").val(),
            _token: "{{ csrf_token() }}" // Laravel CSRF token
        };

        $.ajax({
            url: "{{ route('contact.submit') }}", // Laravel route
            type: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.success
                });
                $('#contactForm')[0].reset(); // Reset form fields
            },
            error: function (xhr) {
                let errorMessage = "Something went wrong!";
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    errorMessage = "";
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + "<br>"; // Show all validation errors
                    });
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage // Display errors using HTML
                });
            },
            complete: function () {
                // Reset button state
                $("#btnText").show();
                $("#btnLoader").hide();
                $("#submitBtn").attr("disabled", false);
            }
        });
    });
});
</script>

<!-- Testimonial End -->
@include('components.footer')