<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
}

// Cek apakah parameter 'get_id' ada di URL
if (isset($_GET['get_id'])) {
    $jurnal_id = $_GET['get_id'];

    // Ambil data dari tabel playlist berdasarkan ID jurnal
    $sql = "SELECT * FROM playlist WHERE id = :id AND pdf_file IS NOT NULL AND pdf_file != ''";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $jurnal_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdf_path = "uploaded_files/" . htmlspecialchars($row['pdf_file']);
        
    } else {
        echo "Jurnal tidak ditemukan.";
        exit;
    }
}    
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>liked videos</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<body>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Jurnal</title>
    <style>

        embed {
            display: block;
            margin-top: 10px;
            width: 100%;
            height: 850px;
            padding-right:-10%
        }
    </style>
</head>
<body>
<?php include 'components/user_header.php'; ?>

	<script type="text/javascript" src="js/main.js"></script>
	<script src="js/script.js"></script>
</body>

</html>
<style>

embed {
    display: block;
    margin-top: 10px;
    width: 100%;
    height: 850px;
    padding-right: 50px; 
}


</style>



<?php if (!empty($pdf_path)) : ?>
    <embed src="<?= $pdf_path ?>#toolbar=0" type="application/pdf" width="100%" height="600px" />
<?php endif; ?>
</body>
</html>
