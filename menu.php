<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu - Pie EXPRESS</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      text-align: center;
      background-color: #f8f8f8;
    }
    /* Nav Bar Styles */
    .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: rgba(1, 50, 32, 1);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
            z-index: 1000;
            height: 80px;
            padding: 10px 20px;
        }
        .navbar.scrolled {
            background-color: rgba(1, 50, 32, 0.8);
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            width: 90px;
            height: auto;
            max-height: 100%;
        }
        .logo span {
            color: white;
            font-size: 24px;
            font-weight: 600;
        }
    
    .nav-links {
      display: flex;
      gap: 20px;
      margin-right: 50px;
    }
    .nav-links a {
      color: white;
      text-decoration: none;
      padding: 8px 12px;
      font-weight: 600;
      border-radius: 5px;
    }
    .order-now {
      background-color: #FFC107;
      color: #013220;
      padding: 10px 15px;
      border-radius: 5px;
    }
    .carousel {
            position: relative;
            width: 100%;
            height: 650px; /* Adjust this value to reduce height */
            margin: 50px auto 0;
            overflow: hidden;
        }

        .carousel-container {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
        }

        .slide {
            min-width: 100%;
            height: 100%;
            display: none;
            position: relative;
        }

        .slide::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Adjust for more or less darkness */
            z-index: 1; /* Keeps it below the text */
        }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the image fills the container while maintaining aspect ratio */
        }

        .carousel-text {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 30px;
            font-weight: bold;
            text-align: left;
            width: 40%;
            z-index: 2; /* Ensures text is above the dark overlay */
        }


        .carousel-text p {
            font-size: 18px;
            font-weight: 300;
        }
        .carousel-text.left {
            left: 5%;
        }
        .carousel-text.right {
            right: 5%;
            text-align: right;
        }
        .prev, .next-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .prev {
            left: 10px;
        }
        .next-btn {
            right: 10px;
        }
        .order-now-overlay {
            display: inline-block;
            background-color: #FFC107;
            color: #013220;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
            position: relative; /* Keeps it aligned within the text block */
        }
    /* Menu Page Content */
    .menu-container {
      margin-top: -30px; /* Adjust to prevent overlap with the fixed nav bar */
      padding: 20px;
    }
    .menu-category {
      margin: 20px auto 40px;
      max-width: 1000px;
      background-color: #fff;
      padding: 50px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-align: left;
    }
    .menu-category h2 {
      margin-bottom: 20px;
      color: #013220;
      border-bottom: 2px solid #FFC107;
      padding-bottom: 5px;
    }
    .menu-items {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .menu-item {
      background-color: #fafafa;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      width: 220px;
      text-align: center;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .menu-item img {
      width: 100%;
      height: auto;
      display: block;
    }
    .item-info {
      padding: 10px;
    }
    .item-info h3 {
      margin: 10px 0 5px;
      font-size: 18px;
      color: #013220;
    }
    .item-info .price {
      font-size: 16px;
      color: #555;
      font-weight: bold;
    }
    @media (max-width: 768px) {
      .menu-item {
        width: 45%;
      }
    }
    @media (max-width: 480px) {
      .menu-item {
        width: 90%;
      }
    }
    .photo-background {
                position: relative;
                width: 100%;
                min-height: 250px; /* Minimal height; adjust as needed */
                background: url('img/food-7.jpg') no-repeat center center; /* Replace with your actual image path */
                background-size: cover;
                }
                 .photo-background h2 {
                  font-family: 'Poppins', sans-serif;
                  font-size: 50px; /* Increased size */
                  /* No color property, so it retains the original color */
                  z-index: 2;
                  margin: 0;
                  padding-top: 50px; /* Adjust spacing as needed */
                  color: #FFC107;
                }
                .photo-background .overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5); /* Lowered exposure overlay */
                z-index: 1;
                }

                .order-now-overlay2 {
                position: absolute;
                top: 60%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #FFC107;
                color: #013220;
                padding: 12px 20px;
                font-size: 18px;
                font-weight: 600;
                border-radius: 5px;
                text-decoration: none;
                z-index: 2; /* Ensures the button appears above the overlay */
                }
                .slider_wrap {
                margin-top: 10px; /* Reduced from 20px */
                margin-left: 20px; /* Moves Featured Food to the right */
            }

            /* Make "Featured Food" and "Our Menu" appear in a row */
            .row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start; /* Align items to the top */
                flex-wrap: wrap; /* Ensure responsiveness */
            }

            .col-md-4 {
                flex: 1; /* Make it take space dynamically */
                max-width: 50%; /* Control width */
            }

            .col-md-8 {
                flex: 1;
                max-width: 55%;
            }

            /* Adjust image alignment in the service slider */
            #service-slider .image {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 5px; /* Reduced space */
            }

            /* Increase the size of the image in the photo carousel */
            #service-slider img {
                max-width: 300px; /* Increased from 120px */
                height: auto;
                border-radius: 10px;
            }

            /* Align text with images */
            #service-slider h3, 
            #service-slider p {
                text-align: center;
                margin: 5px 0;
            }

            .heading {
                font-size: 26px;
                color: #013220; /* Original color kept */
            }

            .heading_space {
                width: 50px;
                border-top: 2px solid #FFC107; /* Original color kept */
                margin-bottom: 20px;
            }

            .branch_widget {
              list-style: none;
              padding: 0;
              display: flex;
              flex-wrap: wrap; /* Ensures responsiveness */
              justify-content: center; /* Centers items horizontally */
              gap: 20px; /* Space between branches */
          }

          .branch_widget li {
              font-size: 18px;
              color: #333;
              display: flex;
              align-items: center;
              background-color: #f9f9f9;
              padding: 10px 15px;
              border-radius: 8px;
              width: 100%;
              max-width: 400px; /* Limits width per row item */
              justify-content: center; /* Centers text inside the item */
          }

          .branch_widget li a {
              display: flex;
              align-items: center;
              text-decoration: none;
              color: #013220;
              font-weight: bold;
              justify-content: left; /* Centers content inside the link */
          }

          .location-icon {
              width: 20px;
              height: auto;
              margin-right: 10px; /* Space between icon and text */
              align-items: left;
          }

          /* Responsive Design */
          @media (max-width: 768px) {
              .branch_widget {
                  flex-direction: column; /* Stack items on small screens */
                  gap: 10px;
              }

              .branch_widget li {
                  max-width: 100%;
              }
          }
    #footer {
                background-color: #013220;
                color: #fff;
                padding: 40px 20px;
                text-align: center;
              }
              .footer-container {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                flex-wrap: wrap;
                justify-content: space-around;
                gap: 20px;
              }
              #footer .contact-info,
              #footer .feedback,
              #footer .social-media {
                flex: 1;
                min-width: 250px;
              }
              #footer .contact-info p {
                margin: 10px 0;
                font-size: 16px;
              }
              #footer .feedback h3 {
                margin-bottom: 15px;
                font-size: 20px;
              }
              #footer .feedback form input,
              #footer .feedback form textarea {
                width: 100%;
                margin-bottom: 10px;
                padding: 10px;
                border: none;
                border-radius: 5px;
              }
              #footer .feedback form button {
                padding: 10px 20px;
                background-color: #FFC107; /* Mustard Yellow */
                color: #013220;
                border: none;
                border-radius: 5px;
                cursor: pointer;
              }
              #footer .social-media a {
                color: #FFC107;
                margin: 0 10px;
                font-size: 24px;
                text-decoration: none;
              }

  </style>
</head>
<body>
  <!-- Nav Bar -->
  <nav class="navbar" id="navbar">
        <div class="logo">
            <a class="navbar-brand" href="index.html"><img src="img/logo.png" alt="logo" class="img-responsive"></a> 
        </div>
        <div class="nav-links">
            <a href="#ordernow" class="order-now">Order Now</a>
            <a href="menu.php">Menu</a>
            <a href="#services">Delivery</a>
            <a href="#footer">Contacts</a>
        </div>
    </nav>
  <div class="carousel">
        <button class="prev" onclick="prevSlide()">&#10094;</button>
        <div class="carousel-container" id="carousel">
            <div class="slide active">
                <img src="img/bg1.jpg">
                <div class="carousel-text left">
                    <h1>PIE EXPRESS</h1>
                    <p>Making a healthier Philippines, One kuchay Pie at a time!<br>TO OUR HEALTH REVOLUTION!</p>
                    <a href="https://pieexpressph.storehub.me/" class="order-now-overlay">Order Now</a> <!-- Added Button -->
                </div>
            </div>
            <div class="slide">
                <img src="img/bg2.JPG">
                <div class="carousel-text right">
                    <h1>PIE EXPRESS</h1>
                    <p>Making a healthier Philippines.</p>
                    <a href="https://pieexpressph.storehub.me/" class="order-now-overlay">Order Now</a> <!-- Added Button -->
                </div>
            </div>
            <div class="slide">
                <img src="img/bg4.jpg">
                <div class="carousel-text left">
                    <h1>PIE EXPRESS</h1>
                    <p>Bringing sustainable, healthy, and good quality products to all.</p>
                    <a href="https://pieexpressph.storehub.me/" class="order-now-overlay">Order Now</a> <!-- Added Button -->
                </div>
            </div>
            <div class="slide">
                <img src="img/bg3.jpg">
                <div class="carousel-text right">
                    <h1>PIE EXPRESS</h1>
                    <p>Customers deserve the healthy and best quality products and service.<br>Compassion for PIE EXPRESS and to its employees.</p>
                    <a href="https://pieexpressph.storehub.me/" class="order-now-overlay">Order Now</a> <!-- Added Button -->
                </div>
            </div>
        </div>
        <button class="next-btn" onclick="nextSlide()">&#10095;</button>
            </div>

  <!-- Menu Listing -->
  <div class="menu-container">
    <!-- Healthy Drinks Section -->
    <section class="menu-category" id="healthy-drinks">
      <h2>Healthy Drinks</h2>
      <div class="menu-items">
        <div class="menu-item">
          <img src="img/menu/strawberry.jpg" alt="Green Detox Smoothie">
          <div class="item-info">
            <h3>Strawberry</h3>
            <p class="price">Php 99.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/lycheee.jpg" alt="Green Detox Smoothie">
          <div class="item-info">
            <h3>Lychee</h3>
            <p class="price">Php 99.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/mango.jpg" alt="Green Detox Smoothie">
          <div class="item-info">
            <h3>Mango</h3>
            <p class="price">Php 99.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/greenapple.jpg" alt="Green Detox Smoothie">
          <div class="item-info">
            <h3>Green Apple</h3>
            <p class="price">Php 99.00</p>
          </div>
        </div>
        <!-- Additional Healthy Drinks items here -->
      </div>
    </section>
    
    <!-- Healthy Sauges Section -->
    <section class="menu-category" id="healthy-sauges">
      <h2>Healthy Appitizers</h2>
      <div class="menu-items">
        <div class="menu-item">
          <img src="img/menu/dumplings.jpg" alt="Chicken Sausage">
          <div class="item-info">
            <h3>Chicken Sausage</h3>
            <p class="price">Php 200.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/kuchaypork.jpg" alt="Chicken Sausage">
          <div class="item-info">
            <h3>Chicken Sausage</h3>
            <p class="price">Php 200.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/beancurd.jpg" alt="Chicken Sausage">
          <div class="item-info">
            <h3>Chicken Sausage</h3>
            <p class="price">Php 200.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/kamote nachos.jpg" alt="Turkey Sausage">
          <div class="item-info">
            <h3>Turkey Sausage</h3>
            <p class="price">Php 220.00</p>
          </div>
        </div>
        <!-- Additional Healthy Sauges items here -->
      </div>
    </section>
    
    <section class="menu-category" id="healthy-dumplings">
      <h2>Healthy Pies</h2>
      <div class="menu-items">
        <div class="menu-item">
          <img src="img/menu/allmeatpizza.jpg" alt="Steamed Veggie Dumplings">
          <div class="item-info">
            <h3>Steamed Veggie Dumplings</h3>
            <p class="price">Php 180.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/original.jpg" alt="Chicken Dumplings">
          <div class="item-info">
            <h3>Chicken Dumplings</h3>
            <p class="price">Php 200.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/cheese.jpg" alt="Chicken Dumplings">
          <div class="item-info">
            <h3>Chicken Dumplings</h3>
            <p class="price">Php 200.00</p>
          </div>
        </div>
        <div class="menu-item">
          <img src="img/menu/creamytuna.jpg" alt="Chicken Dumplings">
          <div class="item-info">
            <h3>Chicken Dumplings</h3>
            <p class="price">Php 200.00</p>
          </div>
        </div>
        
        <!-- Additional Healthy Dumplings items here -->
      </div>
    </section>
  </div>

  <div class="photo-background" id="ordernow">
              <div class="overlay"></div>
              <h2>Try Our deals!</h2>
              <a href="https://pieexpressph.storehub.me/" class="order-now-overlay2">Order Now</a>
            </div>

            <section id="services" class="padding-bottom">
            <div class="container">
              <div class="row">
                <!-- Carousel Section: Left Side -->
                <div class="col-md-4">
                   <h2 class="heading">Our Store Location</h2>
                   <hr class="heading_space">
                   <div class="slider_wrap">
                     <div id="service-slider" class="owl-carousel">
                       <div class="item">
                         <div class="item_inner">
                           <div class="image">
                             <img src="img/loc1.jpg" alt="Featured Food 1">
                           </div>
                           <h3>Hamonado Pie</h3>
                           <p>Enjoy Delicious Food!</p>
                         </div>
                       </div>
                       <div class="item">
                         <div class="item_inner">
                           <div class="image">
                             <img src="img/loc2.jpg" alt="Featured Food 2">
                           </div>
                           <h3>Ham and Cheese Pie</h3>
                           <p>Enjoy Delicious Food!</p>
                         </div>
                       </div>
                       <div class="item">
                         <div class="item_inner">
                           <div class="image">
                             <img src="img/loc3.jpg" alt="Featured Food 3">
                           </div>
                           <h3>Original Pie</h3>
                           <p>Enjoy Delicious Food!</p>
                         </div>
                       </div>
                       <div class="item">
                         <div class="item_inner">
                           <div class="image">
                             <img src="img/loc4.jpg" alt="Featured Food 4">
                           </div>
                           <h3>Classic Pie</h3>
                           <p>Enjoy Delicious Food!</p>
                         </div>
                       </div>
                       <div class="item">
                         <div class="item_inner">
                           <div class="image">
                             <img src="img/loc5.jpg" alt="Featured Food 4">
                           </div>
                           <h3>Classic Pie</h3>
                           <p>Enjoy Delicious Food!</p>
                         </div>
                       </div>
                     </div>
                   </div>
                </div>
          
                <!-- Menu Section: Right Side -->
                <div class="col-md-8">
    <h2 class="heading">Branches</h2>
    <hr class="heading_space">
    <ul class="branch_widget">
        <li>
            <a href="https://www.google.com/maps/search/Festival+Mall+Alabang+Muntinlupa/@14.4175463,121.0385127,17z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI1MDIxMS4wIKXMDSoJLDEwMjExNDUzSAFQAw%3D%3D" target="_blank">
                <img src="img/location-icon.png" alt="Location" class="location-icon">
                Pie Express - Festival Mall Alabang Muntinlupa
            </a>
        </li>
        <li>
            <a href="https://www.google.com/maps/place/Shell/@14.3943212,121.0022968,14z/data=!4m10!1m2!2m1!1sShell+South+Luzon+Expressway+Muntinlupa!3m6!1s0x3397d0ff47062607:0x9011ca0196c8d9b7!8m2!3d14.3943236!4d121.0383469!15sCidTaGVsbCBTb3V0aCBMdXpvbiBFeHByZXNzd2F5IE11bnRpbmx1cGEiA4gBAVopIidzaGVsbCBzb3V0aCBsdXpvbiBleHByZXNzd2F5IG11bnRpbmx1cGGSAQtnYXNfc3RhdGlvbuABAA!16s%2Fg%2F11bc7lw6x3?entry=ttu&g_ep=EgoyMDI1MDIxMS4wIKXMDSoJLDEwMjExNDUzSAFQAw%3D%3D" target="_blank">
                <img src="img/location-icon.png" alt="Location" class="location-icon">
                Pie Express - Shell South Luzon Expressway Muntinlupa
            </a>
        </li>
        <li>
            <a href="https://www.google.com/maps/place/SM+Southmall/@14.4332666,121.0079916,17z/data=!3m1!4b1!4m6!3m5!1s0x3397d1dd991a126b:0x67862091bd5d31e2!8m2!3d14.4332614!4d121.0105665!16s%2Fm%2F02z5cjy?authuser=0&hl=en&entry=ttu&g_ep=EgoyMDI1MDIxMS4wIKXMDSoJLDEwMjExNDUzSAFQAw%3D%3D" target="_blank">
                <img src="img/location-icon.png" alt="Location" class="location-icon">
                Pie Express - SM South mall Las Pinas
            </a>
        </li>
        <li>
            <a href="https://maps.app.goo.gl/dSGxsh2vQWDBUdm46" target="_blank">
                <img src="img/location-icon.png" alt="Location" class="location-icon">
                Pie Express - Pamplona 3 Las Pinas
            </a>
        </li>
        <li>
            <a href="https://maps.app.goo.gl/ToaVZSdWmBQbzyuT7" target="_blank">
                <img src="img/location-icon.png" alt="Location" class="location-icon">
                Pie Express - Muntinlupa City Hall Canteen
            </a>
        </li>
    </ul>
</div>
              </div>
            </div>
          </section>

  <footer id="footer">
              <div class="footer-container">
                <div class="contact-info">
                  <p><i class="fas fa-phone-alt"></i> 0961 625 3718</p>
                  <p><i class="fas fa-envelope"></i> customercare@pieexpressph.com</p>
                  <p><i class="fas fa-clock"></i> Working Hours: 9am - 5pm</p>
                </div>
                <div class="feedback">
                  <h3>Feedback</h3>
                  <form>
                    <input type="text" placeholder="Your Name" required>
                    <input type="email" placeholder="Your Email" required>
                    <textarea placeholder="Your Feedback" required></textarea>
                    <button type="submit">Submit</button>
                  </form>
                </div>
                <div class="social-media">
                  <a href="https://www.facebook.com/pieexpressph" target="_blank"><i class="fab fa-facebook-f"></i></a>
                  <a href="https://www.instagram.com/pieexpressph?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank"><i class="fab fa-instagram"></i></a>
                  <a href="www.pieexpressph.com" target="_blank"><i class="fas fa-globe"></i></a>
                </div>
              </div>
            </footer>

          
  <script>
  let index = 0;
        const slides = document.querySelectorAll(".slide");
        function showSlide(n) {
            slides.forEach((slide, i) => {
                slide.style.display = i === n ? "block" : "none";
            });
        }
        function nextSlide() {
            index = (index + 1) % slides.length;
            showSlide(index);
        }
        function prevSlide() {
            index = (index - 1 + slides.length) % slides.length;
            showSlide(index);
        }
        setInterval(nextSlide, 3000);
        showSlide(index);
        window.addEventListener("scroll", function() {
            document.getElementById("navbar").classList.toggle("scrolled", window.scrollY > 50);
        });

        $(document).ready(function () {
        console.log("Document ready. Initializing carousel..."); // Debugging line
        $("#service-slider").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            margin: 10,
            nav: true,
            navText: ["&#10094;", "&#10095;"], // Adds navigation arrows
            dots: true // Enables pagination dots
        });
        console.log("Carousel initialized."); // Debugging line
         });


      

    </script>
</body>
</html>