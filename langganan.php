<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $msg]);

   if($select_contact->rowCount() > 0){
      $message[] = 'kirim pesan berhasil';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $msg]);
      $message[] = 'kiriman pesan berhasil!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/1.png" alt="">
      </div>

      <form action="" method="post">
   <h3>Upgrade ke Premium</h3>

   <input type="text" placeholder="Nama Lengkap..." required maxlength="100" name="name" class="box">
   
   <input type="number" placeholder="Nomor HP..." required maxlength="15" name="phone" class="box">
 
   <input type="submit" value="Upgrade Sekarang" class="inline-btn" name="submit">
</form>


   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>no hp</h3>
         <a href="tel:999999999">999999999</a>
         <a href="tel:08976543251">0897654321</a>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>email</h3>
         <a href="mailto:shaikhanas@gmail.com">Kel7@gmail.com</a>
         <a href="mailto:anasbhai@gmail.com">RuB@gmail.com</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>address</h3>
         <a href="#">Universitas Andalas</a>
      </div>


   </div>

</section>

<!-- contact section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>