<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   $tutor_id = '';
   header('location:login.php');
}

if (isset($_POST['submit'])) {

   // Filter dan sanitasi input
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   // Menangani file gambar (thumb)
   $image = $_FILES['image']['name'];  // Nama asli file
   $image = filter_var($image, FILTER_SANITIZE_STRING);  // Sanitasi nama file
   $image_tmp_name = $_FILES['image']['tmp_name'];  // Nama sementara file
   $image_folder = '../uploaded_files/'.$image;  // Lokasi folder penyimpanan dengan nama file asli

   // Menangani file PDF jika ada (optional)
   $pdf_file = isset($_FILES['pdf_file']) ? $_FILES['pdf_file']['name'] : null;
   if ($pdf_file) {
       $pdf_file_tmp = $_FILES['pdf_file']['tmp_name'];
       $pdf_file_folder = '../uploaded_files/'.$pdf_file;
   }

   // Query untuk menambahkan playlist (dengan date menggunakan fungsi NOW())
   $add_playlist = $conn->prepare("INSERT INTO `premium`(tutor_id, title, description, thumb, pdf_file, date) VALUES(?,?,?,?,?, NOW())");
   $add_playlist->execute([$tutor_id, $title, $description, $image, $pdf_file]);

   // Pindahkan file gambar ke folder tujuan
   move_uploaded_file($image_tmp_name, $image_folder);

   // Pindahkan file PDF ke folder jika ada
   if ($pdf_file) {
       move_uploaded_file($pdf_file_tmp, $pdf_file_folder);
   }

   $message[] = 'Buku Premium Sudah Ditambahkan!';  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah Buku</title>

   <!-- Font awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form">
   <h1 class="heading">Tambah Buku</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <input type="text" name="title" maxlength="100" required placeholder="Judul Buku" class="box">
      <textarea name="description" class="box" required placeholder="Deskripsi Buku" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Gambar Buku</p>
      <input type="file" name="image" accept="image/*" required class="box">
      <p>Upload PDF</p>
      <input type="file" name="pdf_file" accept=".pdf" class="box">
      <input type="submit" value="Tambahkan" name="submit" class="btn">
   </form>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
