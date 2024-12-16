<?php

include '../components/connect.php';

// Cek apakah tutor sudah login
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Ambil ID playlist dari URL
$get_id = isset($_GET['get_id']) ? $_GET['get_id'] : '';
$get_id = filter_var($get_id, FILTER_SANITIZE_STRING);

// Proses update playlist
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

   // Update data di database
   $update_playlist = $conn->prepare("UPDATE `playlist` SET title = ?, description = ?, thumb = ? WHERE id = ? AND tutor_id = ?");
   $update_playlist->execute([$title, $description, $rename_image, $get_id, $tutor_id]);

   // Redirect ke halaman gratis.php setelah berhasil
   header('location:gratis.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Buku</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-form">

   <h1 class="heading">Update Buku</h1>

   <?php
      // Mengambil data playlist berdasarkan ID yang diberikan
      $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ?");
      $select_playlist->execute([$get_id, $tutor_id]);

      if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
   ?>
            <form action="" method="post" enctype="multipart/form-data">
               <input type="hidden" name="old_image" value="<?= $fetch_playlist['thumb']; ?>">
               
               <p>Judul Buku <span>*</span></p>
               <input type="text" name="title" maxlength="100" required placeholder="Masukkan judul buku" value="<?= $fetch_playlist['title']; ?>" class="box">

               <p>Deskripsi Buku <span>*</span></p>
               <textarea name="description" class="box" required placeholder="Tulis deskripsi buku" maxlength="1000" cols="30" rows="10"><?= $fetch_playlist['description']; ?></textarea>

               <p>Thumbnail Buku <span>*</span></p>
               <div class="thumb">
                  <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
               </div>
               <input type="file" name="image" accept="image/*" class="box">

               <input type="submit" value="Update Buku" name="submit" class="btn">
            </form>
   <?php
         }
      } else {
         echo '<p class="empty">Data buku tidak ditemukan!</p>';
      }
   ?>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
