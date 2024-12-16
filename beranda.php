<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}


if (isset($_POST['login_submit'])) {
    // Mengambil dan membersihkan input
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['password']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);


    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {

        setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
        header('location:home.php');
        exit;
    } else {

        $message[] = 'Email atau password salah!';
    }
}


if(isset($_POST['register_submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   
   if($select_user->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         
         $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
         $verify_user->execute([$email, $pass]);
         $row = $verify_user->fetch(PDO::FETCH_ASSOC);
         
         if($verify_user->rowCount() > 0){
            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:beranda.php');
         }
      }
   }

}

?>


<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== FAVICON ===============-->
      <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

      <!--=============== REMIXICONS ===============-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

      <!--=============== SWIPER CSS ===============-->
      <link rel="stylesheet" href="css/swiper-bundle.min.css">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="css/styles.css">

      <!--==================== HEADER ====================-->
      <header class="header" id="header">
         <nav class="nav container">
            <a href="#" class="nav__logo">
               <i class="ri-book-read-line"></i> RuB
            </a>
         
            <div class="nav__menu">
               <ul class="nav__list">

                  <li class="nav__item">
                     <a href="beranda.php   " class="nav__link">
                        <span>Beranda</span>
                     </a>
                  </li>

                  <li class="nav__item">
                    <a href="#newbook" class="nav__link">
                       <span>Buku Baru</span>
                    </a>
                 </li>

                  <li class="nav__item">
                     <a href="#testimonial" class="nav__link">
                        <span>Testimoni</span>
                     </a>
                  </li>

                  <li class="nav__item">
                    <a href="#harga"class="nav__link">
                       <span>Harga</span>
                    </a>
                 </li>

                 <li class="nav__item">
                    <a href="#features" class="nav__link">
                       <span>about</span>
                    </a>
                 </li>

               </ul>
            </div>
            <div class="nav__actions">

                <i class="ri-login-circle-line search-button" id="login-button"></i>

                <i class="ri-user-line login-butoon"  id="register-button"></i>

               <i class="ri-moon-line change-theme" id="theme-button"></i>
            </div>
         </nav>
      </header>

      <!--==================== MAIN ====================-->
      <main class="main">

      
<!--==================== REGISTER ====================-->
<div class="register grid" id="register-content">
    <form action="" method="POST" class="register__form grid" enctype="multipart/form-data">
        <h1>Mulai <span>Sekarang</span></h1>
        <p class="register__tittle">Daftarkan Akunmu</p>

        <div class="register__group grid">
            <!-- Input Nama -->
            <div>
                <label for="register-nama" class="register__label">Nama Lengkap</label>
                <input type="text" placeholder="Nama..." id="register-nama" name="name" class="register__input" required>
            </div>

            <!-- Input Email -->
            <div>
                <label for="register-email" class="register__label">Email</label>
                <input type="email" placeholder="Email..." id="register-email" name="email" class="register__input" required>
            </div>

            <!-- Input Password -->
            <div>
                <label for="register-pass" class="register__label">Password</label>
                <input type="password" placeholder="Password..." id="register-pass" name="pass" class="register__input" required>
            </div>

            <!-- Input Confirm Password -->
            <div>
                <label for="register-cpass" class="register__label">Konfirmasi Password</label>
                <input type="password" placeholder="Konfirmasi Password..." id="register-cpass" name="cpass" class="register__input" required>
            </div>
        </div>

        <!-- Input Gambar -->
        <div>
            <label for="register-pass" class="register__img"></label>
            <input type="file" name="image" accept="image/*" required class="box">
        </div>

        <!-- Checkbox dan Link -->
        <div>
            <span class="register__signup">
                <input type="checkbox" name="agree" id="agree">
                Saya telah membaca dan setuju dengan <a href="#">Ketentuan Layanan</a> dan <a href="#">Kebijakan Privasi</a> RuB.com
            </span>

            <span class="register__masuk">
                Sudah Punya akun? <a href="#">Masuk sekarang</a>
            </span>

            <!-- Tombol Daftar -->
            <button type="submit" name="register_submit" class="register__button button">Daftar Akun</button>
        </div>
    </form>
    <i class="ri-close-line register__close" id="register-close"></i>
</div>





    <div class="login grid" id="login-content">
        <form action="" method="POST" class="login__form grid">
            <span>ReadUrBook</span>
            <h3 class="login__tittle">Masuk</h3>
    
            <div class="login__group grid">
                <div>
                    <label for="login-email" class="login__label"></label>
                    <input type="email" placeholder="Email..." id="login-email" name="email" class="login__input" required>
                </div>
                <div>
                    <label for="login-pass" class="login__label"></label>
                    <input type="password" placeholder="Password..." id="login-pass" name="password" class="login__input" required>
                </div>
            </div>
            <div>
                <span class="login__signup">
                    Tidak punya akun? <a href="#">Sign up</a>
                </span>
    
                <a href="#" class="login__forgot">
                    Lupa password?
                </a>
                <button type="submit" name="login_submit" class="login__button button">Log In</button>
            </div>
            <i class="ri-close-line login__close" id="login-close"></i>
        </form>
    </div>
    

         <!--==================== HOME ====================-->
         <section class="home section" id="home">
            <div class="home__container container grid">
                <div class="home__data">
                    <h1 class="home__tittle">
                        Cari & <br>
                        Pilih Buku
                    </h1>
                    <p class="home__description"> 
                        Temukan e-book terbaik dari favorit Anda 
                        Penulis, jelajahi ratusan buku dengan semua 
                        kategori yang mungkin
                    </p>
        
                    <a href="#" class="button">Jelajahi sekarang</a>
                </div>
                <div class="home__images"> 
                    <div class="home__swiper swiper">
                        <div class="swiper-wrapper">
                            <article class="home__article swiper-slide">
                                <img src="images/book-2.png" alt="" class="home__img">
                            </article>
        
                            <article class="home__article swiper-slide">
                                <img src="images/book-3.png" alt="" class="home__img">
                            </article>
        
                            <article class="home__article swiper-slide">
                                <img src="images/book-1.png" alt="" class="home__img">
                            </article>
        
                            <article class="home__article swiper-slide">
                                <img src="images/book-4.png" alt="" class="home__img">
                            </article>
                            <article class="home__article swiper-slide">
                                <img src="images/book-6.png" alt="" class="home__img">
                            </article>
                            <article class="home__article swiper-slide">
                                <img src="images/book-8.png" alt="" class="home__img">
                            </article>
                            <article class="home__article swiper-slide">
                                <img src="images/book-9.png" alt="" class="home__img">
                            </article>
                            

                        </div>
                    </div>
                </div>
            </div>
        </section>

         <!--==================== Buku Terbaru ====================-->
         <section class="featured section" id="newbook">
            <h2 class="section__tittle">
                Buku Terbaru
            </h2>
            <div class="featured__container container">
                <div class="featured__swiper swiper">                 
                <div class = "swiper-wrapper">
                <article class="featured__card swiper-slide">
                    <img src="images/book-1.png" alt="" class="featured__img">

                    <h2 class="featured__tittle">Nama Buku</h2>
                    <div class="featured__prices">
                        <span class="featured__discount"></span>
                        <span class="featured__price"></span>
                    </div>

                    <buutton class="button">Berlangganan</buutton>

                    <div class="featured__actions">
                        <button><i class="ri-search-line"></i></button>
                        <button><i class="ri-heart-line"></i></button>
                        <button><i class="ri-eye-line"></i></button>
                    </div>
                </article>

                <article class="featured__card swiper-slide">
                    <img src="images/book-5.png" alt="" class="featured__img">

                    <h2 class="featured__tittle">Nama Buku</h2>
                    <div class="featured__prices">
                        <span class="featured__discount"></span>
                        <span class="featured__price"></span>
                    </div>

                    <buutton class="button">Berlangganan</buutton>

                    <div class="featured__actions">
                        <button><i class="ri-search-line"></i></button>
                        <button><i class="ri-heart-line"></i></button>
                        <button><i class="ri-eye-line"></i></button>
                    </div>
                </article>

                <article class="featured__card swiper-slide">
                    <img src="images/book-4.png" alt="" class="featured__img">

                    <h2 class="featured__tittle">Nama Buku</h2>
                    <div class="featured__prices">
                        <span class="featured__discount"></span>
                        <span class="featured__price"></span>
                    </div>

                    <buutton class="button">Berlangganan</buutton>

                    <div class="featured__actions">
                        <button><i class="ri-search-line"></i></button>
                        <button><i class="ri-heart-line"></i></button>
                        <button><i class="ri-eye-line"></i></button>
                    </div>
                </article>

                <article class="featured__card swiper-slide">
                    <img src="images/book-3.png" alt="" class="featured__img">

                    <h2 class="featured__tittle">Nama Buku</h2>
                    <div class="featured__prices">
                        <span class="featured__discount"></span>
                        <span class="featured__price"></span>
                    </div>

                    <buutton class="button">Berlangganan</buutton>

                    <div class="featured__actions">
                        <button><i class="ri-search-line"></i></button>
                        <button><i class="ri-heart-line"></i></button>
                        <button><i class="ri-eye-line"></i></button>
                    </div>
                </article>

                <article class="featured__card swiper-slide">
                    <img src="images/book-2.png" alt="" class="featured__img">

                    <h2 class="featured__tittle">Nama Buku</h2>
                    <div class="featured__prices">
                        <span class="featured__discount"></span>
                        <span class="featured__price"></span>
                    </div>

                    <buutton class="button">Berlangganan</buutton>

                    <div class="featured__actions">
                        <button><i class="ri-search-line"></i></button>
                        <button><i class="ri-heart-line"></i></button>
                        <button><i class="ri-eye-line"></i></button>
                    </div>
                </article>
                    </div>
                    <div class="swiper-button-prev">
                        <i class="ri-skip-left-line"></i>
                        
                    </div>

                    <div class="swiper-button-next">
                        <i class="ri-skip-right-line"></i>
                    </div>
                </div>
            </div>
         </section>

         <!--==================== TESTIMONIAL ====================-->
         <section class="testimonial section" id="testimonial">
            <h2 class="section__tittle">
                Opini Pengguna
            </h2>

            <div class="testimonial__container container">
                <div class="testimonial__swiper swiper">
                    <div class = "swiper-wrapper">
                        <article class="testimonial__card swiper-slide">
                            <img src="images/testimonial-1.jpg" alt="image" class="testimonial__img">
                            <h2 class="testimonial__tittle">Tapasya</h2>
                            <p class="testimonial__description">
                                lore ipsum manchester united selalu dihati
                            </p>

                            <div class="testimonial__stars">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                            </div>
                        </article>
                        <article class="testimonial__card swiper-slide">
                            <img src="images/testimonial-2.jpg" alt="" class="testimonial__img">
                            <h2 class="testimonial__tittle">Randi Si Montage</h2>
                            <p class="testimonial__description">
                                lore ipsum manchester united selalu dihati
                            </p>

                            <div class="testimonial__stars">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                            </div>
                        </article>
                        <article class="testimonial__card swiper-slide">
                            <img src="images/testimonial-3.jpg" alt="" class="testimonial__img">
                            <h2 class="testimonial__tittle">Rodrygo</h2>
                            <p class="testimonial__description">
                                lore ipsum manchester united selalu dihati
                            </p>

                            <div class="testimonial__stars">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-half-fill"></i>
                            </div>
                        </article>
                        <article class="testimonial__card swiper-slide">
                            <img src="images/testimonial-4.jpg" alt="" class="testimonial__img">
                            <h2 class="testimonial__tittle">Si Jungler</h2>
                            <p class="testimonial__description">
                                lore ipsum manchester united selalu dihati
                            </p>

                            <div class="testimonial__stars">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-half-fill"></i>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
         </section>

         
 <!--==================== HARGA ====================-->
         <section class="pricing-section" id = "harga">
                <div class="pricing-plans">
                    <div class="plan-header">
                        <h1>ReadUrBook</h1>
                    </div>
                    <div class="plan">
                        <h2 class="plan-title">GRATIS</h2>
                        <p class="plan-price">Rp0</p>
                        <p class="plan-description">Tanpa dipungut biaya</p>
                        <button class="btn btn-free">Mulai sekarang</button>
                    </div>
    
                    <!-- Premium Plan -->
                    <div class="plan premium">
                        <h2 class="plan-title">PREMIUM</h2>
                        <p class="plan-price">Rp99.000/bulan</p>
                        <p class="plan-discount"><p1>Diskon 50% </p1><span class="old-price">Rp199.000</span></p>
                        <button class="btn btn-premium">Upgrade</button>
                    </div>
                </div>
                <!-- Features -->
                <div class="features-table">
                    <div class="feature-row">
                        <span class ="bold">Akses</span >
                        <p1>Terbatas</p1>
                        <p class="highlight">Semua</p>
                    </div>
                    <div class="feature-row">
                        <span class ="bold">Pelayanan</span>
                        <p1>Tidak Diutamakan</p1>
                        <p class="highlight">Bisa Curhat </p>
                    </div>
                    <div class="feature-row">
                        <span class ="bold">Request</span>
                        <p1>oo Tidak Bisa</p1>
                        <p class="highlight">Boleh</p>
                    </div>
                    <div class="feature-row">
                        <span class ="bold">Tambah Favorit</span>
                        <p1>Sikit</p1>
                        <p class="highlight">Unlimited</p>
                    </div>
                    <div class="feature-row">
                        <span class ="bold">Iklan</span>
                        <p1>1 Slide 1 iklan</p1>
                        <p class="highlight">No iklan</p>
                    </div>
                </div>
            </div>
        </section>
          <!--==================== ABout US ====================-->
          <section class="features" id = "features">
            <img class="features-img" src="images/c581e2e9-28e7-471c-bb63-5ca1669d5251.png"/>
            <div class="feature-text">
      
                <h6>Our Features</h6>
                <h1>Why Choose Us</h1>
                <div>
                    <h5><p>01</p> Dukungan Pelanggan inshaallah 24/7:</h5>
                    <p>Tim dukungan kami selalu siap membantu Anda dengan pertanyaan atau masalah.</p>
                </div>
                <div>
                    <h5><p>02</p>Koleksi Buku Inshaallah Lengkap: </h5>
                    <p>Kami menawarkan beragam koleksi buku, mulai dari sastra klasik hingga buku terbaru.</p>
                </div>
                <div>
                    <h5><p>03</p> Antarmuka Pengguna yang Inshallah Ramah:</h5>
                    <p> Platform kami mudah dinavigasi, memberikan pengalaman membaca yang lancar.</p>
                </div>
            </div>
        </section>

        <!-- Team Start -->
    <section class="team section" id="team">
        <h2 class="section__tittle">
            Our Webiste Builder
        </h2>
        <div class="team__container container">
          <div class="team__swiper swiper">
              <div class = "swiper-wrapper">
                  <article class="team__card swiper-slide">
                      <img src="images/team-1.jpg" alt="image" class="team__img">
                      <h2 class="team__tittle">Tapasya</h2>
                      <p class="team__description">
                          lore ipsum manchester united selalu dihati
                      </p>
                  </article>
                  <article class="team__card swiper-slide">
                    <img src="images/team-2.jpg" alt="image" class="team__img">
                    <h2 class="team__tittle">Tapasya</h2>
                    <p class="team__description">
                        lore ipsum manchester united selalu dihati
                    </p>
                </article>
                <article class="team__card swiper-slide">
                  <img src="images/team-5.jpg" alt="image" class="team__img">
                  <h2 class="team__tittle">Tapasya</h2>
                  <p class="team__description">
                      lore ipsum manchester united selalu dihati
                  </p>
              </article>
              <article class="team__card swiper-slide">
                <img src="images/team-3.jpg" alt="image" class="team__img">
                <h2 class="team__tittle">Tapasya</h2>
                <p class="team__description">
                    lore ipsum manchester united selalu dihati
                </p>
            </article>
            <article class="team__card swiper-slide">
              <img src="images/team-4.jpg" alt="image" class="team__img">
              <h2 class="team__tittle">Tapasya</h2>
              <p class="team__description">
                  lore ipsum manchester united selalu dihati
              </p>
          </article>
          <article class="team__card swiper-slide">
            <img src="images/team-5.jpg" alt="image" class="team__img">
            <h2 class="team__tittle">Tapasya</h2>
            <p class="team__description">
                lore ipsum manchester united selalu dihati
            </p>
        </article>
                </div>
              </div>
          </div>
        </section>
      <!-- Team End -->

      </main>


      <!--==================== FOOTER ====================-->
      <footer class="footer">
        <div class="footer__container container grid">
            <!-- Logo Section -->
            <div>
                <a href="#" class="footer__logo">
                    <i class="ri-book-line"></i> ReadUrBook
                </a>
            </div>
    
            <!-- About Section -->
            <div>
                <h3 class="footer__title">About</h3>
                <ul class="footer__links">
                    <li><a href="#" class="footer__link">Awards</a></li>
                    <li><a href="#" class="footer__link">FAQs</a></li>
                    <li><a href="#" class="footer__link">Privacy policy</a></li>
                    <li><a href="#" class="footer__link">Terms of services</a></li>
                </ul>
            </div>
    
            <!-- Company Section -->
            <div>
                <h3 class="footer__title">Company</h3>
                <ul class="footer__links">
                    <li><a href="#" class="footer__link">Blogs</a></li>
                    <li><a href="#" class="footer__link">Community</a></li>
                    <li><a href="#" class="footer__link">Our team</a></li>
                    <li><a href="#" class="footer__link">Help center</a></li>
                </ul>
            </div>
    
            <!-- Contact Section -->
            <div>
                <h3 class="footer__title">Contact</h3>
                <ul class="footer__links">
                    <li>
                        <address class="footer_info">
                            <p>chensteiger</p> <br>
                            <p>Universitas Andalas</p><br>
                        </address>
                    </li>
                    <li>
                        <address class="footer_info">
                        <p>Rub@gmail.com</p> <br>
                        <p>0000000</p>
                        </address>
                    </li>
                </ul>
            </div>
    
        </div>
    </footer>
    



      <!--========== SCROLL UP ==========-->
      <a href="#" class="scrollup" id="scroll-up">
        <i class="ri-arrow-up-line"></i>
      </a>
      

      <!--=============== SCROLLREVEAL ===============-->
      <script src=""></script>

      <!--=============== SWIPER JS ===============-->
      <script src="js/swiper-bundle.min.js"></script>

      <!--=============== MAIN JS ===============-->
      <script src="js/main.js"></script>
   </body>
</html>

