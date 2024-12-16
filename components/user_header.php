<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
      <!--=============== FAVICON ===============-->
      <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

      <!--=============== REMIXICONS ===============-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

   <section class="flex">

      <a href="home.php" class="logo"> <h1>Read Ur Book</h1> <span>Beta</span></a>

      <form action=".php" method="post" class="search-form">
         <input type="text" name="search_course" placeholder="cari buku..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_course_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
            <h3><?= $fetch_profile['name']; ?></h3>
            <span>student</span>
            <a href="profile.php" class="btn">view profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="inline-delete-btn">logout</a>
         <?php
         } else {
         ?>
            <h3>login dlu baru bisa akses</h3>
         <?php
         }
         ?>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
   </div>

   <div class="profile">
      <?php
      $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $select_profile->execute([$user_id]);
      if ($select_profile->rowCount() > 0) {
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span>student</span>
         <a href="profile.php" class="btn">view profile</a>
      <?php
      } else {
      ?>
         <h3>Please login to access this section.</h3>
      <?php
      }
      ?>
   </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="bukupremium.php"><i class="ri-book-line"></i><span>buku premium</span></a>
      <a href="bukugratis.php"><i class="ri-book-fill"></i><span>buku gratis</span></a>
      <a href="langganan.php"><i class="ri-vip-crown-line"></i></i><span>Upgrade</span></a>
   </nav>

</div>

<!-- side bar section ends -->