<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Maunong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            padding: 2rem 0;
        }

        .service-tile {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            cursor: pointer;
        }

        .service-tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .service-icon {
            font-size: 3.5rem;
            color: #667eea;
            margin-bottom: 1.5rem;
        }

        .service-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .service-description {
            color: #6c757d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .service-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .service-btn:hover {
            transform: scale(1.05);
            color: white;
            text-decoration: none;
        }

        .header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem 2rem;
            margin-bottom: 3rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .logo i {
            color: #667eea;
            font-size: 1.5rem;
        }

        .barangay-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }

        .login-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: white;
            color: #667eea;
            text-decoration: none;
        }

        .services-grid {
            transition: all 0.5s ease;
        }

        .sub-services {
            display: none;
        }

        .back-btn {
            background: linear-gradient(45deg, #6c757d, #495057);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #495057, #343a40);
        }

        .help-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
        }

        .help-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .help-text {
            color: rgba(255,255,255,0.8);
            margin-bottom: 1.5rem;
        }

        .contact-info {
            color: white;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <!-- Header -->
        <div class="header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="logo">
                    <i class="fas fa-building"></i>
                </div>
                <h1 class="barangay-title">Barangay Maunong</h1>
            </div>
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </div>

        <!-- Main Services Grid -->
        <div id="main-services" class="services-grid">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="service-tile">
                        <div class="service-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="service-title">Resident Registration</h3>
                        <p class="service-description">
                            Register as a new resident of Barangay Maunong. Quick and easy online registration process.
                        </p>
                        <a href="{{ route('public.residents.register') }}" class="service-btn">
                            Register Now <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="service-tile" onclick="showTrackingServices()">
                        <div class="service-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="service-title">Tracking Services</h3>
                        <p class="service-description">
                            Track the status of your requests using your reference or tracking number.
                        </p>
                        <button class="service-btn">
                            Track Services <i class="fas fa-search ms-2"></i>
                        </button>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="service-tile" onclick="showOnlineServices()">
                        <div class="service-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3 class="service-title">Online Services</h3>
                        <p class="service-description">
                            Access various barangay services online. Announcements, inquiries, and more.
                        </p>
                        <button class="service-btn">
                            View Services <i class="fas fa-external-link-alt ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tracking Services Sub-menu -->
        <div id="tracking-services" class="sub-services">
            <button class="back-btn" onclick="showMainServices()">
                <i class="fas fa-arrow-left me-2"></i>Back to Main Services
            </button>

            <div class="row g-4">
                <div class="col-lg-6 col-md-6">
                    <div class="service-tile">
                        <div class="service-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h3 class="service-title">Registration Tracking</h3>
                        <p class="service-description">
                            Track the status of your resident registration using your reference number.
                        </p>
                        <a href="{{ route('residents.track') }}" class="service-btn">
                            Track Registration <i class="fas fa-search ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="service-tile">
                        <div class="service-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3 class="service-title">Document Request Tracking</h3>
                        <p class="service-description">
                            Track the status of your document requests using your tracking number.
                        </p>
                        <a href="{{ route('document-requests.track') }}" class="service-btn">
                            Track Document <i class="fas fa-search ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Online Services Sub-menu -->
        <div id="sub-services" class="sub-services">
            <button class="back-btn" onclick="showMainServices()">
                <i class="fas fa-arrow-left me-2"></i>Back to Main Services
            </button>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="service-tile">
                        <div class="service-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h3 class="service-title">Barangay Document Request</h3>
                        <p class="service-description">
                            Request barangay certificates, clearances, and other official documents online.
                        </p>
                        <a href="{{ route('document-requests.request') }}" class="service-btn">
                            Request Document <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="service-tile">
                        <div class="service-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="service-title">Announcements</h3>
                        <p class="service-description">
                            Stay updated with the latest barangay announcements, events, and important notices.
                        </p>
                        <a href="#" class="service-btn">
                            View Announcements <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="service-tile">
                        <div class="service-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3 class="service-title">Inquiries & Concerns</h3>
                        <p class="service-description">
                            Submit your questions, concerns, or feedback to the barangay officials online.
                        </p>
                        <a href="#" class="service-btn">
                            Submit Inquiry <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="help-section">
            <h2 class="help-title">Need Help?</h2>
            <p class="help-text">
                If you need assistance with any of our services, please don't hesitate to contact us.
            </p>
            <div class="contact-info">
                <i class="fas fa-phone me-2"></i>
                Contact Number: (02) 123-4567
                <br>
                <i class="fas fa-envelope me-2 mt-2"></i>
                Email: info@barangaymaunong.gov.ph
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showOnlineServices() {
            showSubServices('sub-services');
        }

        function showTrackingServices() {
            showSubServices('tracking-services');
        }

        function showSubServices(targetId) {
            const mainServices = document.getElementById('main-services');
            const targetServices = document.getElementById(targetId);

            // Hide other sub-services
            const allSubServices = document.querySelectorAll('.sub-services');
            allSubServices.forEach(service => {
                if (service.id !== targetId) {
                    service.style.display = 'none';
                }
            });

            mainServices.style.opacity = '0';
            mainServices.style.transform = 'translateX(-100%)';

            setTimeout(() => {
                mainServices.style.display = 'none';
                targetServices.style.display = 'block';
                targetServices.style.opacity = '0';
                targetServices.style.transform = 'translateX(100%)';

                setTimeout(() => {
                    targetServices.style.transition = 'all 0.5s ease';
                    targetServices.style.opacity = '1';
                    targetServices.style.transform = 'translateX(0)';
                }, 50);
            }, 250);
        }

        function showMainServices() {
            const mainServices = document.getElementById('main-services');
            const allSubServices = document.querySelectorAll('.sub-services');

            // Hide all sub-services
            allSubServices.forEach(service => {
                service.style.opacity = '0';
                service.style.transform = 'translateX(100%)';
            });

            setTimeout(() => {
                allSubServices.forEach(service => {
                    service.style.display = 'none';
                });

                mainServices.style.display = 'block';
                mainServices.style.opacity = '0';
                mainServices.style.transform = 'translateX(-100%)';

                setTimeout(() => {
                    mainServices.style.transition = 'all 0.5s ease';
                    mainServices.style.opacity = '1';
                    mainServices.style.transform = 'translateX(0)';
                }, 50);
            }, 250);
        }
    </script>
</body>
</html>
