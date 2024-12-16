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
   <section class="flex">
      <a href="dashboard.php" class="logo"></a>

      <form action="search_page.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="search here..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="search-btn" class="fas fa-search"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
         $select_profile->execute([$tutor_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <h3><?= $fetch_profile['name']; ?></h3>
         
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         <?php
         } else {
         ?>
         <h3>please login or register</h3>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
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
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">

   <h3>Admin RUB <span>Beta</span></h3>

      <?php
      $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
      $select_profile->execute([$tutor_id]);
      if ($select_profile->rowCount() > 0) {
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3><?= $fetch_profile['name']; ?></h3>
      <a href="update.php" class="btn">update profile</a>
      <?php
      } else {
      ?>
      <h3>please login or register</h3>
      <div class="flex-btn">
         <a href="login.php" class="option-btn">login</a>
         <a href="register.php" class="option-btn">register</a>
      </div>
      <?php
      }
      ?>
   </div>

   <nav class="navbar">
      <a href="dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="premium.php"><i class="fa-solid fa-bars-staggered"></i><span>buku premium</span></a>
      <a href="gratis.php"><i class="fa-solid fa-bars-staggered"></i><span>buku gratis</span></a>
      <a href="comments.php"><i class="fas fa-comment"></i><span>comments</span></a>
      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
   </nav>
</div>

<!-- side bar section ends -->