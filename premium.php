<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
  $get_id = $_GET['get_id'];
}else{
  $get_id = '';
  header('location:home.php');
}

if(isset($_POST['save_list'])){

  if($user_id != ''){
     
     $list_id = $_POST['list_id'];
     $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

     $select_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
     $select_list->execute([$user_id, $list_id]);

     if($select_list->rowCount() > 0){
        $remove_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
        $remove_bookmark->execute([$user_id, $list_id]);
        $message[] = 'playlist removed!';
     }else{
        $insert_bookmark = $conn->prepare("INSERT INTO `bookmark`(user_id, playlist_id) VALUES(?,?)");
        $insert_bookmark->execute([$user_id, $list_id]);
        $message[] = 'buku tersimpan';
     }

  }else{
     $message[] = 'login terlebih dahulu';
  }

}


if(isset($_POST['add_comment'])){

  if($user_id != ''){

     $id = unique_id();
     $comment_box = $_POST['comment_box'];
     $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);
     $content_id = $_POST['content_id'];
     $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

     $select_comment = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND user_id = ? AND comment = ?");
     $select_comment->execute([$content_id, $user_id, $comment_box]);

     if($select_comment->rowCount() > 0){
        $message[] = 'komen sudah ditambahkan';
     }else{
        $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, comment) VALUES(?, ?, ?, ?)");
        $insert_comment->execute([$id, $content_id, $user_id, $comment_box]);
        $message[] = 'komen baru ditambahkan';
     }

  }else{
     $message[] = 'please login first!';
  }

}

if(isset($_POST['delete_comment'])){

  $delete_id = $_POST['comment_id'];
  $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

  $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
  $verify_comment->execute([$delete_id]);

  if($verify_comment->rowCount() > 0){
     $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
     $delete_comment->execute([$delete_id]);
     $message[] = 'delete komen berhasil';
  }else{
     $message[] = 'komen sudah didelete';
  }

}


if(isset($_POST['update_now'])){

  $update_id = $_POST['update_id'];
  $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
  $update_box = $_POST['update_box'];
  $update_box = filter_var($update_box, FILTER_SANITIZE_STRING);

  $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND comment = ?");
  $verify_comment->execute([$update_id, $update_box]);

  if($verify_comment->rowCount() > 0){
     $message[] = 'komen sudah ditambahkan';
  }else{
     $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
     $update_comment->execute([$update_box, $update_id]);
     $message[] = 'komen sudah di edit';
  }

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Deskripsi Buku</title>

    <!-- font awesome cdn link  -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
    />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
  <?php include 'components/user_header.php'; ?>

    <section class="playlist">
        <h1 class="heading">Deskripsi Buku</h1>

        <div class="row">
            <?php
            $select_playlist = $conn->prepare("SELECT * FROM `premium` WHERE id = ? LIMIT 1");
            $select_playlist->execute([$get_id]);
            if ($select_playlist->rowCount() > 0) {
                $fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC);
                $playlist_id = $fetch_playlist['id'];


                $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
                $select_tutor->execute([$fetch_playlist['tutor_id']]);
                $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);


            $select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
            $select_bookmark->execute([$user_id, $playlist_id]);

      ?>

      <div class="col">
         <form action="" method="post" class="save-list">
            <input type="hidden" name="list_id" value="<?= $playlist_id; ?>">
            <?php
               if($select_bookmark->rowCount() > 0){
            ?>
            <button type="submit" name="save_list"><i class="fas fa-bookmark"></i><span>Tersimpan</span></button>
            <?php
               }else{
            ?>
               <button type="submit" name="save_list"><i class="far fa-bookmark"></i><span>Simpan Buku</span></button>
            <?php
               }
            ?>
         </form>
         <div class="thumb">
            <img src="uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
         <a href="pdfpremium.php?get_id=<?= $get_id; ?>" class="inline-btn">Baca Buku</a>

      </div>

      <div class="col">
         <div class="tutor">


         </div>
         <div class="details">
            <h3><?= $fetch_playlist['title']; ?></h3>
            <p><?= $fetch_playlist['description']; ?></p>
            <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
      </div>

      <?php
         }else{
            echo '<p class="empty">buku tidak ditemukan</p>';
         }  
      ?>

   </div>

</section>
    <?php
   if(isset($_POST['edit_comment'])){
      $edit_id = $_POST['comment_id'];
      $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);
      $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? LIMIT 1");
      $verify_comment->execute([$edit_id]);
      if($verify_comment->rowCount() > 0){
         $fetch_edit_comment = $verify_comment->fetch(PDO::FETCH_ASSOC);
?>
<section class="edit-comment">
   <h1 class="heading">edti comment</h1>
   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['id']; ?>">
      <textarea name="update_box" class="box" maxlength="1000" required placeholder="please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
      <div class="flex">
         <a href="buku.php?get_id=<?= $get_id; ?>" class="inline-option-btn">cancel edit</a>
         <input type="submit" value="update now" name="update_now" class="inline-btn">
      </div>
   </form>
</section>
<?php
   }else{
      $message[] = 'komen tidak ditemukan';
   }
}
?>

    <section class="comments">

<h1 class="heading">Komentar</h1>

<form action="" method="post" class="add-comment">
   <input type="hidden" name="content_id" value="<?= $get_id; ?>">
   <textarea name="comment_box" required placeholder="write your comment..." maxlength="1000" cols="30" rows="10"></textarea>
   <input type="submit" value="add comment" name="add_comment" class="inline-btn">
</form>

<h1 class="heading">komentar pengguna</h1>


<div class="show-comments">
   <?php
      $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
      $select_comments->execute([$get_id]);
      if($select_comments->rowCount() > 0){
         while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){   
            $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_commentor->execute([$fetch_comment['user_id']]);
            $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
   ?>
   <div class="box" style="<?php if($fetch_comment['user_id'] == $user_id){echo 'order:-1;';} ?>">
      <div class="user">
         <img src="uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_commentor['name']; ?></h3>
            <span><?= $fetch_comment['date']; ?></span>
         </div>
      </div>
      <p class="text"><?= $fetch_comment['comment']; ?></p>
      <?php
         if($fetch_comment['user_id'] == $user_id){ 
      ?>
      <form action="" method="post" class="flex-btn">
         <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
         <button type="submit" name="edit_comment" class="inline-option-btn">edit comment</button>
         <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">delete comment</button>
      </form>
      <?php
      }
      ?>
   </div>
   <?php
    }
   }else{
      echo '<p class="empty">belum ada komen ditambahkan</p>';
   }
   ?>
   </div>

</section>


    <!-- custom js file link  -->
    <script src="js/script.js"></script>
  </body>
</html>
