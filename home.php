<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}


$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

      
    <section class="home-grid">
      <h1 class="heading">quick options</h1>
      <div class="box-container">

      <?php
if ($user_id != '') {
?>
  <div class="box">
    <h3 class="title">Likes and Comments</h3> 
    <p><span>Total komen:</span> <span><?= $total_comments; ?></span></p>
    <a href="comments.php" class="inline-btn">lihat komen <i class="ri-message-3-line"></i></a>
    <p><span>Saved buku:</span><span><?= $total_bookmarked; ?></span></p>
    <a href="bookmark.php" class="inline-btn">Lihat album <i class="ri-bookmark-3-fill"></i></a>
  </div>

<?php
}
?>

        <div class="box">
          <h3 class="title">Top Kategori</h3>
          <div class="flex">
            <a href="#"><i class="ri-aliens-line"></i></i><span>Fiksi</span></a>
            <a href="#"><i class="ri-computer-line"></i><span>Teknologi</span></a>
            <a href="#"><i class="ri-reactjs-line"></i></i><span>Pemrograman  </span></a>
            <a href="#"
              ><i class="fas fa-chart-line"></i><span>science</span></a
            >
          </div>
        </div>

        <div class="box">
          <h3 class="title">Buku Terpopuler</h3>
          <div class="flex">
            <a href="#"><i class="ri-pass-pending-line"></i><span>Sastra Dunia</span></a>
            <a href="#"><i class="ri-book-shelf-line"></i><span>Novel</span></a>
            <a href="#"><i class="ri-mental-health-line"></i><span>Biografi</span></a>
            <a href="#"><i class="ri-glasses-2-fill"></i><span>Kecerdasan buatan</span></a>
          </div>
        </div>
      </div>
    </section>

<!-- Section Courses -->
<section class="courses">
    <h1 class="heading">Semua Buku</h1>

    <div class="box-container">
        <?php
        // Mengambil semua playlist buku premium terlebih dahulu
        $select_courses = $conn->prepare("SELECT * FROM `premium` ORDER BY date DESC");
        $select_courses->execute();

        // Menampilkan setiap playlist buku premium
        if ($select_courses->rowCount() > 0) {
            while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                $course_id = $fetch_course['id'];
        ?>
        <div class="box">
            <!-- Menampilkan label Premium di atas gambar -->
            <div class="label">Premium</div>
            <!-- Menampilkan gambar thumbnail buku premium -->
            <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">

            <div>
                <!-- Menampilkan judul dan tanggal buku premium -->
                <h3 class="title"><?= $fetch_course['title']; ?></h3>
                <span><?= $fetch_course['date']; ?></span>
            </div>

            <!-- Tautan ke halaman detail buku premium -->
            <a href="premium.php?get_id=<?= $course_id; ?>" class="inline-btn">Lihat Buku </a>
        </div>
        <?php
            }
        }

        $select_courses = $conn->prepare("SELECT * FROM `playlist` ORDER BY date DESC");
        $select_courses->execute();


        if ($select_courses->rowCount() > 0) {
            while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                $course_id = $fetch_course['id'];
        ?>
        <div class="box">

            <div class="label">Gratis</div>

            <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">

            <div>

                <h3 class="title"><?= $fetch_course['title']; ?></h3>
                <span><?= $fetch_course['date']; ?></span>
            </div>

            <a href="buku.php?get_id=<?= $course_id; ?>" class="inline-btn">Lihat Buku </a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty"></p>';
        }
        ?>
    </div>
</section>


    <!-- custom js file link  -->
    <script src="js/script.js"></script>
  </body>
</html>
