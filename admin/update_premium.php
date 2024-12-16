<?php

include '../components/connect.php';

// Cek apakah tutor sudah login
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Ambil ID premium dari URL
$get_id = isset($_GET['get_id']) ? $_GET['get_id'] : '';
$get_id = filter_var($get_id, FILTER_SANITIZE_STRING);

// Proses update premium
if(isset($_POST['submit'])){

   // Data yang diupdate
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);

   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $old_image = $_POST['old_image'];

   // Jika ada file baru diupload
   if(!empty($_FILES['image']['name'])){
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $image_ext = pathinfo($image, PATHINFO_EXTENSION);
      $rename_image = uniqid() . '.' . $image_ext;

      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = '../uploaded_files/' . $rename_image;

      // Hapus gambar lama
      if($old_image != ''){
         unlink('../uploaded_files/'.$old_image);
      }

      // Pindahkan gambar baru
      move_uploaded_file($image_tmp_name, $image_folder);

   } else {
      // Jika gambar tidak diupdate, gunakan gambar lama
      $rename_image = $old_image;
   }

   // Update data di database premium
   $update_premium = $conn->prepare("UPDATE `premium` SET title = ?, description = ?, thumb = ? WHERE id = ? AND tutor_id = ?");
   $update_premium->execute([$title, $description, $rename_image, $get_id, $tutor_id]);

   // Redirect ke halaman premium.php setelah berhasil
   header('location:premium.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Buku Premium</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-form">

   <h1 class="heading">Update Buku Premium</h1>

   <?php
      // Mengambil data premium berdasarkan ID yang diberikan
      $select_premium = $conn->prepare("SELECT * FROM `premium` WHERE id = ? AND tutor_id = ?");
      $select_premium->execute([$get_id, $tutor_id]);

      if($select_premium->rowCount() > 0){
         while($fetch_premium = $select_premium->fetch(PDO::FETCH_ASSOC)){
   ?>
            <form action="" method="post" enctype="multipart/form-data">
               <input type="hidden" name="old_image" value="<?= $fetch_premium['thumb']; ?>">
               
               <p>Judul Buku <span>*</span></p>
               <input type="text" name="title" maxlength="100" required placeholder="Masukkan judul buku" value="<?= $fetch_premium['title']; ?>" class="box">

               <p>Deskripsi Buku <span>*</span></p>
               <textarea name="description" class="box" required placeholder="Tulis deskripsi buku" maxlength="1000" cols="30" rows="10"><?= $fetch_premium['description']; ?></textarea>

               <p>Thumbnail Buku <span>*</span></p>
               <div class="thumb">
                  <img src="../uploaded_files/<?= $fetch_premium['thumb']; ?>" alt="">
               </div>
               <input type="file" name="image" accept="image/*" class="box">

               <input type="submit" value="Update Buku" name="submit" class="btn">
            </form>
   <?php
         }
      } else {
         echo '<p class="empty">Data buku premium tidak ditemukan!</p>';
      }
   ?>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
