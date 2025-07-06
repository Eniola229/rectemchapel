<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RECTEM CHAPEL | E-Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
  <!-- Include PDF.js library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>


  <style>
   body {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        animation: bgSlideshow 12s infinite;
    }

    @keyframes bgSlideshow {
        0% { background-image: url('https://images.pexels.com/photos/590493/pexels-photo-590493.jpeg'); }
        33% { background-image: url('https://images.pexels.com/photos/46274/pexels-photo-46274.jpeg'); }
        66% { background-image: url('https://images.pexels.com/photos/415071/pexels-photo-415071.jpeg'); }
        100% { background-image: url('https://images.pexels.com/photos/590493/pexels-photo-590493.jpeg'); }
    }

    .navbar, .footer { background-color: #007bff; color: #fff; }
    .btn-custom { background-color: #ffc107; color: #000; }
    .btn-custom:hover { background-color: #e0a800; color: #fff; }
    .gallery img { width: 100%; height: auto; cursor: pointer; margin-bottom: 15px; }
    .filter-section { margin-bottom: 20px; }

/* Modal backdrop */
.modal-backdrop.show {
    opacity: 0.5; /* More subtle backdrop */
}

/* Modal content */
.modal-content {
    border-radius: 12px;
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1); /* Softer shadow, larger radius for rounded corners */
}

/* Modal header */
.modal-header {
    background-color: #343a40;
    color: #f8f9fa;
    padding: 1.25rem;
    border-bottom: none;
    border-radius: 12px 12px 0 0; /* Rounded top corners */
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Title styling */
.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

/* Close button */
.btn-close-white {
    background-color: transparent;
    border: none;
    font-size: 1.5rem;
    color: #f8f9fa;
}

/* Modal body */
.modal-body {
    padding: 2rem 2rem 1rem;
    text-align: center;
}

/* Image styling */
.modal-body img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Soft shadow around image */
}

/* Details section */
.details p {
    font-size: 1.1rem;
    color: #495057;
    margin-bottom: 1rem;
}

.details strong {
    color: #212529;
}

/* Colored labels */
.details .text-primary {
    color: #007bff; /* Primary blue */
}

.details .text-secondary {
    color: #6c757d; /* Secondary gray */
}

.details .text-success {
    color: #28a745; /* Success green */
}

.details .text-warning {
    color: #ffc107; /* Warning yellow */
}

/* Modal footer */
.modal-footer {
    padding: 1rem 2rem;
    background-color: #f8f9fa;
    display: flex;
    justify-content: space-between;
    border-top: none;
    border-radius: 0 0 12px 12px; /* Rounded bottom corners */
}

/* Footer buttons */
.modal-footer .btn {
    font-weight: 500;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.modal-footer .btn:hover {
    transform: scale(1.05);
}

/* Close button hover effect */
.modal-footer .btn-danger:hover {
    background-color: #dc3545;
}

/* Download button hover effect */
.modal-footer .btn-primary:hover {
    background-color: #007bff;
}

/* Small screen responsiveness */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 20px;
        width: 100%;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem;
    }
}

/* Container styling */
.gallery-container {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); /* Adjusted for better responsiveness */
    padding: 1rem;
    margin-top: 20px; /* Adds some space above the gallery */
}

/* Hover scale effect */
.hover-scale {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    border-radius: 8px; /* Smooth rounded corners */
}

/* Card hover effect */
.hover-scale:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Slightly stronger shadow */
}

/* Image overlay style */
.image-overlay {
    background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0) 60%, rgba(0,0,0,0.6) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* Show overlay on hover */
.card:hover .image-overlay {
    opacity: 1;
}

/* Text inside overlay */
.card-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 1.1rem; /* Increase title font size */
    font-weight: 600; /* Bold title */
}

/* Bottom section text */
.card-footer .small {
    font-size: 0.875rem; /* Smaller text */
}

/* Badge styling */
.badge {
    font-size: 0.875rem; /* Slightly smaller badge text */
    font-weight: 500; /* Make badges less bold */
}

/* Button styling */
.view-details {
    transition: background-color 0.3s ease;
}

/* Button hover effect */
.view-details:hover {
    background-color: #007bff;
    color: white;
}

/* Mobile-friendly adjustments */
@media (max-width: 576px) {
    .gallery-container {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Even smaller for small screens */
    }
}
#pdfCanvas {
    height: 30px !important;
    width: 100% !important;
    object-fit: contain;
}

/* Apply margin-top: 5% on mobile screens */
@media (max-width: 768px) { /* Adjust breakpoint as needed */
  .customHead {
    margin-top: 10%;
  }
}

  </style>
</head>
<body>
  <!-- Navigation / Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="#">RECTEM CHAPEL | E‑Library</a>
  
  <div class="ml-auto">
    <button class="btn btn-custom" data-toggle="modal" data-target="#uploadModal">Upload Material</button>
  </div>
</nav>


  <!-- Main Content -->
  <div class="container mt-4 p-2">
    <!-- Filter Section -->
    <div class="row filter-section">
      <!-- Department Filter -->
      <div class="col-md-6 customHead" >
        <label for="departmentSelect" class="text-white">Department</label>
        <select id="departmentSelect" class="form-control">
          <option value="">All Departments</option>
          <optgroup label="ND Courses">
            <option value="COMPUTER ENGINEERING">COMPUTER ENGINEERING</option>
            <option value="CIVIL ENGINEERING">CIVIL ENGINEERING</option>
            <option value="ELECTRICAL/ELECTRONIC ENGINEERING">ELECTRICAL/ELECTRONIC ENGINEERING</option>
            <option value="ARCHITECTURAL TECHNOLOGY">ARCHITECTURAL TECHNOLOGY</option>
            <option value="ESTATE MANAGEMENT & VALUATION">ESTATE MANAGEMENT & VALUATION</option>
            <option value="QUANTITY SURVEYING">QUANTITY SURVEYING</option>
            <option value="ACCOUNTING">ACCOUNTING</option>
            <option value="BUSINESS ADMINISTRATOR & MANAGEMENT">BUSINESS ADMINISTRATOR & MANAGEMENT</option>
            <option value="COMPUTER SCIENCE">COMPUTER SCIENCE</option>
            <option value="SCIENCE LABORATORY TECHNOLOGY">SCIENCE LABORATORY TECHNOLOGY</option>
            <option value="NON">NON</option>
          </optgroup>
          <optgroup label="HND Courses">
            <option value="ACCOUNTANCY">ACCOUNTANCY</option>
            <option value="BIOCHEMISTRY">BIOCHEMISTRY</option>
            <option value="MICRO BIOLOGY">MICRO BIOLOGY</option>
            <option value="QUANTITY SURVEYING">QUANTITY SURVEYING</option>
            <option value="AIRTIFICIAL INTELIGENCY">AIRTIFICIAL INTELIGENCY</option>
            <option value="ARCHITECTURAL TECHNOLOGY">ARCHITECTURAL TECHNOLOGY</option>
            <option value="BUSINESS ADMINISTRATOR & MANAGEMENT">BUSINESS ADMINISTRATOR & MANAGEMENT</option>
            <option value="ESTATE MANAGEMENT & VALUATION">ESTATE MANAGEMENT & VALUATION</option>
            <option value="NETWORKING & CLOUD COMPUTING">NETWORKING & CLOUD COMPUTING</option>
            <option value="SOFTWARE & WEB DEVELOPMENT">SOFTWARE & WEB DEVELOPMENT</option>
            <option value="CYBER SECURITY & DATA PROTECTION">CYBER SECURITY & DATA PROTECTION</option>
             <option value="NON">NON</option>
          </optgroup>
        </select>
      </div>
      <!-- Year Filter -->
      <div class="col-md-6">
        <label for="yearSelect" class="text-white">Level</label>
        <select id="yearSelect" class="form-control">
          <option value="">All Levels</option>
          <option value="ND1">ND1</option>
          <option value="ND2">ND2</option>
          <option value="HND1">HND1</option>
          <option value="HND2">HND2</option>
           <option value="NON">NON</option>
        </select>
      </div>
    </div>

    <div class="col-md-6">
        <label for="yearSelect" class="text-white text-bold">Search</label>
        <input type="text" id="search" class="form-control" placeholder="Search for material by entering Title, Level, Department, Couse etc ">
    </div>

    <!-- Gallery Section -->
    <div class="row gallery" id="materialGallery">
      <!-- Gallery items will be dynamically injected here -->
    </div>
  </div>

  <!-- Image Modal for Viewing Materials -->
  <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <img id="modalImage" src="" alt="" class="img-fluid">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" id="prevImage" class="btn btn-secondary">Previous</button>
          <button type="button" id="nextImage" class="btn btn-secondary">Next</button>
          <button type="button" class="btn btn-custom" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Upload Material Modal -->
  <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="uploadForm" enctype="multipart/form-data">
           <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="modal-header">
            <h5 class="modal-title">Upload Material</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <!-- Form Fields -->
              <div class="form-group">
                <label for="materialName">Your Name</label>
                <input type="text" class="form-control" id="materialName" name="name" required>
              </div>
              <div class="form-group">
                <label for="materialCourse">Course</label>
                <input type="text" class="form-control" id="materialCourse" name="course" required>
              </div>
              <!-- Department Select with ND and HND Groups -->
              <div class="form-group">
                <label for="materialDepartment">Department</label>
                <select id="materialDepartment" name="department" class="form-control" required>
                  <option value="">Select Department</option>
                  <optgroup label="ND Courses">
                    <option value="COMPUTER ENGINEERING">COMPUTER ENGINEERING</option>
                    <option value="CIVIL ENGINEERING">CIVIL ENGINEERING</option>
                    <option value="ELECTRICAL/ELECTRONIC ENGINEERING">ELECTRICAL/ELECTRONIC ENGINEERING</option>
                    <option value="ARCHITECTURAL TECHNOLOGY">ARCHITECTURAL TECHNOLOGY</option>
                    <option value="ESTATE MANAGEMENT & VALUATION">ESTATE MANAGEMENT & VALUATION</option>
                    <option value="QUANTITY SURVEYING">QUANTITY SURVEYING</option>
                    <option value="ACCOUNTING">ACCOUNTING</option>
                    <option value="BUSINESS ADMINISTRATOR & MANAGEMENT">BUSINESS ADMINISTRATOR & MANAGEMENT</option>
                    <option value="COMPUTER SCIENCE">COMPUTER SCIENCE</option>
                    <option value="SCIENCE LABORATORY TECHNOLOGY">SCIENCE LABORATORY TECHNOLOGY</option>
                     <option value="NON">NON</option>
                  </optgroup>
                  <optgroup label="HND Courses">
                    <option value="ACCOUNTANCY">ACCOUNTANCY</option>
                    <option value="BIOCHEMISTRY">BIOCHEMISTRY</option>
                    <option value="MICRO BIOLOGY">MICRO BIOLOGY</option>
                    <option value="QUANTITY SURVEYING">QUANTITY SURVEYING</option>
                    <option value="AIRTIFICIAL INTELIGENCY">AIRTIFICIAL INTELIGENCY</option>
                    <option value="ARCHITECTURAL TECHNOLOGY">ARCHITECTURAL TECHNOLOGY</option>
                    <option value="BUSINESS ADMINISTRATOR & MANAGEMENT">BUSINESS ADMINISTRATOR & MANAGEMENT</option>
                    <option value="ESTATE MANAGEMENT & VALUATION">ESTATE MANAGEMENT & VALUATION</option>
                    <option value="NETWORKING & CLOUD COMPUTING">NETWORKING & CLOUD COMPUTING</option>
                    <option value="SOFTWARE & WEB DEVELOPMENT">SOFTWARE & WEB DEVELOPMENT</option>
                    <option value="CYBER SECURITY & DATA PROTECTION">CYBER SECURITY & DATA PROTECTION</option>
                     <option value="NON">NON</option>
                  </optgroup>
                </select>
              </div>
              <div class="form-group">
                <label for="materialTitle">Title</label>
                <input type="text" class="form-control" id="materialTitle" name="title" required>
              </div>
                <div class="form-group">
                    <label for="materialFile">File (Image or PDF)</label>
                    <input type="file" class="form-control-file" id="materialFile" name="file" accept="image/*,application/pdf" required>
                </div>
              <div class="form-group">
                <label for="materialYear">Level</label>
                <select id="materialYear" name="year" class="form-control" required>
                  <option value="">Select Level</option>
                  <option value="ND1">ND1</option>
                  <option value="ND2">ND2</option>
                  <option value="HND1">HND1</option>
                  <option value="HND2">HND2</option>
                   <option value="NON">NON</option>
                </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-custom">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>


<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-dark text-white border-0 rounded-top">
                <h5 class="modal-title fw-semibold" id="detailsModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Image or PDF Preview will be dynamically injected here -->
                <div id="detailsModalMediaContainer" class="text-center">
                    <!-- Image -->
                    <img id="detailsModalImage" src="" alt="Document Image" class="img-fluid rounded-3 shadow-sm mb-4 d-none">
                    <!-- PDF Preview -->
                    <iframe id="pdfIframe" class="img-fluid rounded-3 shadow-sm mb-4 d-none" style="height: 500px; width: 100%;" frameborder="0"></iframe>
                </div>
                <div class="details text-muted">
                    <p><strong class="text-dark">Uploaded by:</strong> <span id="detailsModalName" class="text-primary"></span></p>
                    <p><strong class="text-dark">Department:</strong> <span id="detailsModalDepartment" class="text-secondary"></span></p>
                    <p><strong class="text-dark">Course:</strong> <span id="detailsModalCourse" class="text-success"></span></p>
                    <p><strong class="text-dark">Level:</strong> <span id="detailsModalYear" class="text-warning"></span></p>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 rounded-bottom py-3">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <a id="downloadButton" href="#" download class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i> Download Document
                </a>
            </div>
        </div>
    </div>
</div>







  <!-- jQuery, Bootstrap JS, SweetAlert2 -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {
      let materialsData = [];

      // Render gallery items based on materialsData array
function renderGallery() {
    $('#materialGallery').empty().addClass('gallery-container');

    materialsData.forEach((item, index) => {
        const isImage = item.file_url.match(/\.(jpg|jpeg|png)$/i);
        const isPdf = item.file_url.match(/\.pdf$/i);

        let itemHtml = `
        <div class="col-12 col-sm-6 col-lg-12 col-xl-12 mb-4 material-item" data-department="${item.department}" data-year="${item.year}">
            <div class="card h-100 shadow-sm border-0 overflow-hidden hover-scale">
                <div class="card-img-top position-relative overflow-hidden">`;

        if (isImage) {
            itemHtml += `
                <img src="${item.file_url}" alt="${item.title}" 
                     class="img-fluid gallery-item" 
                     data-index="${index}" 
                     style="height: 250px; object-fit: cover;">`;
        } else if (isPdf) {
            itemHtml += `
                <div class="pdf-thumbnail" id="pdf-thumbnail-${index}" 
                     style="height: 250px; display: flex; justify-content: center; align-items: center;">
                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                    <p class="spinner-border spinner-border-sm" role="status"></p>
                </div>`;
        }

        itemHtml += `
                    <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-3">
                        <div class="top-section">
                            <span class="badge bg-primary mb-2">${item.department}</span>
                            <span class="badge bg-secondary">Level ${item.year}</span>
                        </div>
                        <div class="bottom-section text-white">
                            <h5 class="card-title mb-1">${item.title}</h5>
                            <p class="small mb-0 opacity-75">Uploaded by ${item.name}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-3 pb-2">
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>${item.course}</span>
                        <span>Level ${item.year}</span>
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100 view-details" 
                            data-index="${index}">
                        <i class="fas fa-info-circle me-2"></i>View Details
                    </button>
                </div>
            </div>
        </div>`;

        // Append HTML to materialGallery first
        $('#materialGallery').append(itemHtml);

        // Call loadPdfPreview after ensuring the element exists
        if (isPdf) {
            setTimeout(() => {
                loadPdfPreview(item.file_url, index);
            }, 500); // Add slight delay to ensure it loads correctly
        }
    });
}

function loadPdfPreview(pdfUrl, index) {
    const pdfThumbnailDiv = document.getElementById(`pdf-thumbnail-${index}`);

    if (!pdfThumbnailDiv) {
        console.error("PDF container not found.");
        return;
    }

    if (!window.pdfjsLib) {
        console.error("PDF.js library is not loaded.");
        return;
    }

    console.log("Loading PDF:", pdfUrl); // Debugging

    pdfjsLib.getDocument(pdfUrl).promise
        .then(pdf => {
            return pdf.getPage(1);
        })
        .then(page => {
            const scale = 0.5;
            const viewport = page.getViewport({ scale });

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            page.render({ canvasContext: context, viewport }).promise.then(() => {
                requestAnimationFrame(() => {
                    pdfThumbnailDiv.innerHTML = ''; // Clear text
                    pdfThumbnailDiv.appendChild(canvas);
                });
            });
        })
        .catch(error => {
            console.error("Error loading PDF:", error);
            pdfThumbnailDiv.innerHTML = `
                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                <p class="text-muted">Failed to load PDF</p>
            `;
        });
}


// This function is used to load the PDF into the modal's canvas
function loadPdfPreviews(pdfUrl) {
    const canvas = document.getElementById('pdfCanvas');
    const context = canvas.getContext('2d');

    // Load the PDF.js document
    const loadingTask = pdfjsLib.getDocument(pdfUrl);
    loadingTask.promise.then(pdf => {
        // Get the first page of the PDF
        pdf.getPage(1).then(page => {
            // Calculate scale to fit the fixed height (50px)
            const scale = 50 / page.getViewport({ scale: 1 }).height;
            const viewport = page.getViewport({ scale: scale });

            // Set the canvas size
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            // Render the page into the canvas
            page.render({
                canvasContext: context,
                viewport: viewport
            }).promise.then(() => {
                // Optionally, you can adjust the canvas size here if needed
            });
        });
    }).catch(error => {
        console.error('Error loading PDF:', error);
    });
}

// $(document).on('click', '.view-details', function() {
//     let index = $(this).data('index');
//     let item = materialsData[index];

//     // Set modal title and other text information
//     $('#detailsModalTitle').text(item.title);
//     $('#detailsModalName').text(item.name);
//     $('#detailsModalDepartment').text(item.department);
//     $('#detailsModalCourse').text(item.course);
//     $('#detailsModalYear').text(item.year);

//     // Handle Image or PDF
//     const fileUrl = item.file_url;
//     const isImage = fileUrl.match(/\.(jpg|jpeg|png)$/i);
//     const isPdf = fileUrl.match(/\.pdf$/i);

//     // Hide both image and PDF preview initially
//     $('#detailsModalImage').addClass('d-none');
//     $('#detailsModalPdf').addClass('d-none');

//     // Reset the canvas before loading a new PDF
//     $('#pdfCanvas').get(0).getContext('2d').clearRect(0, 0, $('#pdfCanvas').width(), $('#pdfCanvas').height());

//     if (isImage) {
//         // If it's an image, show the image
//         $('#detailsModalImage').removeClass('d-none').attr('src', fileUrl);
//     } else if (isPdf) {
//         // If it's a PDF, show the first page preview
//         $('#detailsModalPdf').removeClass('d-none');
//         loadPdfPreviews(fileUrl); // Load the PDF preview
//     }

//     // Set download link to the file
//     $('#downloadButton').attr('href', fileUrl);

//     // Show the modal
//     $('#detailsModal').modal('show');
// });


  $(document).on('click', '.view-details', function() {
      let index = $(this).data('index');
      let item = materialsData[index];

      // Set modal title and other text information
      $('#detailsModalTitle').text(item.title);
      $('#detailsModalName').text(item.name);
      $('#detailsModalDepartment').text(item.department);
      $('#detailsModalCourse').text(item.course);
      $('#detailsModalYear').text(item.year);

      // Handle Image or PDF
      const fileUrl = item.file_url;
      const isImage = fileUrl.match(/\.(jpg|jpeg|png)$/i);
      const isPdf = fileUrl.match(/\.pdf$/i);

      // Hide both image and PDF preview initially
      $('#detailsModalImage').addClass('d-none');
      $('#pdfIframe').addClass('d-none');

      // Show Image if it's an image
      if (isImage) {
          $('#detailsModalImage').removeClass('d-none').attr('src', fileUrl);
      }

      // Show PDF if it's a PDF
      if (isPdf) {
          $('#pdfIframe').removeClass('d-none').attr('src', fileUrl);
      }

      // Set download link to the file
      $('#downloadButton').attr('href', fileUrl);

      // Show the modal
      $('#detailsModal').modal('show');
  });



      // Load all materials from the backend; by default, all materials are shown
      function loadMaterials() {
        $.ajax({
          url: '/getMaterials',
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            materialsData = response;
            renderGallery();
          },
          error: function(xhr) {
            console.error('Error loading materials:', xhr);
          }
        });
      }

      loadMaterials();

      function searchGallery() {
          let searchText = $('#search').val().toLowerCase();

          $('.material-item').each(function() {
            let itemText = $(this).text().toLowerCase();
            let matches = false;

            // Check text content
            if (itemText.includes(searchText)) {
              matches = true;
            } else {
              // Check data attributes
              $.each(this.dataset, function(key, value) {
                if (value.toLowerCase().includes(searchText)) {
                  matches = true;
                  return false; // Exit loop early if a match is found
                }
              });
            }

            if (matches) {
              $(this).show();
            } else {
              $(this).hide();
            }
          });
        }

        // Trigger search on input
        $('#search').on('keyup', searchGallery);


      // Filter gallery items based on department and year selections
      function filterGallery() {
        let selectedDepartment = $('#departmentSelect').val();
        let selectedYear = $('#yearSelect').val();
        
        $('.material-item').each(function() {
          let dept = $(this).data('department');
          let year = $(this).data('year');
          if ((selectedDepartment === "" || selectedDepartment === dept) &&
              (selectedYear === "" || selectedYear === year)) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      }

      $('#departmentSelect, #yearSelect').change(filterGallery);

      // Image Modal Functionality
      let currentIndex = 0;
      $(document).on('click', '.gallery-item', function() {
        currentIndex = parseInt($(this).data('index'));
        showModalImage(currentIndex);
      });

      function showModalImage(index) {
        let visibleItems = $('.material-item:visible .gallery-item');
        if (visibleItems.length === 0) return;
        if (index >= visibleItems.length) index = 0;
        let src = $(visibleItems[index]).attr('src');
        $('#modalImage').attr('src', src);
        $('#imageModal').modal('show');
      }

      $('#prevImage').click(function() {
        let visibleItems = $('.material-item:visible .gallery-item');
        currentIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
        showModalImage(currentIndex);
      });

      $('#nextImage').click(function() {
        let visibleItems = $('.material-item:visible .gallery-item');
        currentIndex = (currentIndex + 1) % visibleItems.length;
        showModalImage(currentIndex);
      });


      // Upload Material Form Submission using jQuery & SweetAlert2
$('#uploadForm').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var submitButton = $(this).find('button[type="submit"]');

    // Disable the button and show a loading spinner
    submitButton.prop('disabled', true);
    var originalText = submitButton.text();
    submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

    $.ajax({
        url: '/uploadMaterial',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Upload Successful',
                text: response.message || 'Material uploaded successfully!'
            });
            $('#uploadModal').modal('hide');
            loadMaterials();
        },
        error: function(xhr) {
            let errorMsg = 'An error occurred!';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMsg = Object.values(xhr.responseJSON.errors).join("\n");
            } else {
                errorMsg = xhr.responseText;
            }
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: errorMsg
            });
        },
        complete: function() {
            submitButton.prop('disabled', false);
            submitButton.text(originalText);
        }
    });
});

    });
  </script>
</body>
</html>
