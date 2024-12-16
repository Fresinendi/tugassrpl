<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}


$select_premium = $conn->prepare("SELECT * FROM `premium` WHERE tutor_id = ?");
$select_premium->execute([$tutor_id]);
$total_premium = $select_premium->rowCount();


$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();


$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->execute([$tutor_id]);
$total_comments = $select_comments->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">


      <div class="box">
         <h3><?= $total_premium; ?></h3>
         <p>total buku</p>
         <a href="add_premium.php" class="btn">Tambah Buku Premium</a>
      </div>

      <div class="box">
         <h3><?= $total_playlists; ?></h3>
         <p>total buku</p>
         <a href="add_gratis.php" class="btn">Tambah Buku Gratis</a>
      </div>


      <div class="box">
         <h3><?= $total_comments; ?></h3>
         <p>total comments</p>
         <a href="comments.php" class="btn">view comments</a>
      </div>


   </div>

</section>











<script src="../js/admin_script.js"></script>

</body>
</html>