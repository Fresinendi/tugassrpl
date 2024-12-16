<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>bookmarks</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">

   <h1 class="heading">buku yang disimpan</h1>

   <div class="box-container">

      <?php
         // Mengambil playlist yang telah dibookmark oleh user
         $select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
         $select_bookmark->execute([$user_id]);
         if($select_bookmark->rowCount() > 0){
            while($fetch_bookmark = $select_bookmark->fetch(PDO::FETCH_ASSOC)){
               // Mengambil playlist yang sesuai dengan bookmark tanpa memeriksa status
               $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? ORDER BY date DESC");
               $select_courses->execute([$fetch_bookmark['playlist_id']]);
               if($select_courses->rowCount() > 0){
                  while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){

                  $course_id = $fetch_course['id'];

                  

                  // Mengambil data tutor untuk playlist
                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="buku.php?get_id=<?= $course_id; ?>" class="inline-btn">Lihat Buku</a>
      </div>
      <?php
               }
            }else{
               echo '<p class="empty">tidak ada buku di save</p>';
            }
         }
      }else{
         echo '<p class="empty">belum ada buku yang di save</p>';
      }
      ?>

   </div>

</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
