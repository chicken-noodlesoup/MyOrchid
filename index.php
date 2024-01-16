
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MY ORCHID</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img\logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/jquery-3.7.0.js" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="lib/dropzone/dropzone.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />
    <link href="lib/select2/select2.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar Start -->
    
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary">My Orchid</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <li class=" smooth-menu active"></li>
                <li class=" smooth-menu"><a href="#about" class="nav-item nav-link">About</a></li>
                <li class=" smooth-menu"><a href="#features" class="nav-item nav-link">Features</a></li>
                <li class=" smooth-menu"><a href="#contact" class="nav-item nav-link">Contact</a></li>
                <li class=" smooth-menu"><a href="page_login.php"id = "login-show" 
                    class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Login<i class="fa fa-arrow-right ms-3"></i></a></li>
            </div>
            
        </div>
    </nav>
    
    <!-- Navbar End -->

   <!-- Static Start -->
<section id="Static" class="Static">
    <div class="container-fluid p-0">
        <div class="position-relative" style="overflow: hidden;">
            <img class="img-fluid" style="height: 89vh; width: 100%; object-fit: cover;" src="img/welcome-banner.jpg" alt="">
        </div>
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(53, 53, 53, .7);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="static-text"> 
                            <h5 class="text-white text-uppercase mb-3">WELCOME TO My Orchid</h5>
                            <h1 class="display-3 text-white mb-4">DISCOVER THE WORLD OF ORCHID</h1>
                            <p class="fs-5 fw-medium text-white mb-4 pb-2">LEARN, RECORD, AND WATCH IT BLOOMS</p>
                        </div>
                        <a href="#about"class="btn btn-primary py-3 px-5 me-3" >Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Static End -->




    <!-- About Start -->
    <section id="About" class="about">
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 ps-lg-0" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="img/orchid-1.jpg" style="object-fit: cover;" alt="">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0">
                        <div class="section-title text-start">
                            <h1 class="display-5 mb-4">About Us</h1>
                        </div>
                        <p class="mb-4 pb-2" style="text-align: justify;">Welcome to My Orchid, your go-to destination for all things orchids! 
                            We're a dedicated team of orchid lovers, committed to creating a space where enthusiasts can connect, learn, and flourish. 
                            Our platform offers valuable resources and a user-friendly interface to make orchid management an enjoyable experience for everyone. 
                            Join us in cultivating a vibrant community that celebrates the beauty and diversity of these extraordinary plants.</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- About End -->


    <section id="features">
    <div class="container-xxl py-5">
        <div class="container">
            <div class="section-title text-center">
                <h1 class="display-5 mb-5">Features</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item">
                        <div class="p-4 text-center border border-5 border-light ">
                            <h4 class="mb-3">User Registration And Login</h4>
                            <p>Create Personalized Experiences Through User Authentication</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item">
                        <div class="p-4 text-center border border-5 border-light ">
                            <h4 class="mb-3">Orchid Species Exploration</h4>
                            <p>Easily Browse And Search Various Orchid Species</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item">
                        <div class="p-4 text-center border border-5 border-light ">
                            <h4 class="mb-3">Orchid Care Guides</h4>
                            <p>Provide Valuable Instructions For Nurturing Different Orchid Types</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item">
                        <div class="p-4 text-center border border-5 border-light ">
                            <h4 class="mb-3">Detailed Species Information</h4>
                            <p>Access Details, Including Care Requirements, Photos And Descriptions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item">
                        <div class="p-4 text-center border border-5 border-light ">
                            <h4 class="mb-3">Personal Orchid Collection</h4>
                            <p>Manage And Curate Your Own Collection Of Orchids</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item">
                        <div class="p-4 text-center border border-5 border-light ">
                            <h4 class="mb-3">Community Interaction</h4>
                            <p>Engage In Discussions To Share Knowledge And Insights About Orchid</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>




<section id="contact">
<div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
    <div class="container Ques px-lg-0">
        <div class="row g-0 mx-lg-0">
            <div class="col-lg-8 Ques-text py-5 wow fadeIn mx-auto" data-wow-delay="0.5s">
                <div class="p-lg-5 pe-lg-0">
                    <div class="section-title text-center">
                        <h1 class="display-5 mb-4">Send a message</h1>
                    </div>
                    <p class="mb-4 pb-2">Welcome to our contact page! If you have any questions or inquiries, feel free to reach out to us. We're here to help you.</p>
                    <form id="contactForm" method="POST">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control border-0" id="name" name="name" placeholder="Your Name" style="height: 55px;">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="email" class="form-control border-0" id="email" name="email" placeholder="Your Email" style="height: 55px;">
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control border-0" id="subject" name="subject" placeholder="Subject" style="height: 55px;">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control border-0" id="note" name="note" placeholder="Special Note"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</section>


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="copyright">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">MyOrchid</a>, All Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-0 back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/dropzone/dropzone.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
    <script src="lib/select2/select2.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
    <script src="js/script2.js"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.smooth-scroll').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var contactForm = document.getElementById('contactForm');

        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(contactForm);

            $.ajax({
                type: 'POST',
                url: 'message.php', // Replace with the actual PHP script file
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);

                    // Assuming your response contains a status and message
                    if (response.status === 'success') {
                        // Show success message using alertify
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(response.message);
                        $('#contactForm')[0].reset();

                    } else {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.error(response.message);
                    }
                },
                error: function (error) {
                    console.error(error);

                    // Handle error, e.g., show an error message to the user
                    alertify.error('An error occurred. Please try again.');
                }
            });

        });
    });

    </script>

</body>

</html>