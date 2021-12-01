<?php
require 'src/Exception.php'; //Mail gönderirken bir hata ortaya çıkarsa hata mesajlarını görebilmek için gerekli. Şart değil
require 'src/PHPMailer.php'; //Mail göndermek için gerekli.
require 'src/SMTP.php'; //SMTP ile mail göndermek için gerekli.
require 'Settings.php'; //SMTP ile mail göndermek için gerekli.

use PHPMailer\PHPMailer\PHPMailer; //Kullanılacak sınıfın (PHPMailer) yolu belirtiliyor ve projeye dahil ediliyor

/*Variables*/
$Success = "";
$Error = "";
$subject = "";
$message = "";
$sendAdress = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (!(empty($_POST['subject']) && (!empty($_POST['message'])) && (!empty($_POST['sendAdress'])))) {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sendAdress = $_POST['sendAdress'];

    $mail = new PHPMailer(); //PHPMailer sınıfı kuruluyor

    $mail->Host = $Host; //SMPT mail sunucusu. 
    $mail->Username = $Username; //Tanımlanan web sunucusuna ait mail hesabı kullanıcı adı. 
    $mail->Password = $Password; //Mail hesabı şifre
    $mail->Port = $Port; //Mail sunucu mail gönderme portu.
    $mail->SMTPSecure = 'tls'; //Veri gizliliği yöntemi.

    $mail->isSMTP(); //SMPT kullanarak mail gönderilecek
    $mail->SMTPAuth = true; //SMPT kimlik doğrulanmasını etkinleştir

    $mail->isHTML(false); //Mail içeriğinde HTML etiketlerinin algılanmasına izin vermek. 

    $mail->CharSet = "UTF-8"; //Türkçe karakter desteği mevcut
    $mail->setLanguage('tr', 'language/'); //Hata mesajlarını tr dili ile yazdır.
    //$mail->SMTPDebug  = 2; //işlem sürecini göster. Hataları belirlemenizi kolaylaştırır

    $mail->setFrom($Username); //Tanımlanan web sunucusuna ait bir gönderen mail adresi 
    //$mail->addReplyTo('test@hotmail.com', 'Bilal Baydur'); 
    $mail->addAddress($sendAdress); 
    //$mail->addCC('test@hotmail.com', 'Bilal Baydur'); 
    //$mail->addBCC('test@gmail.com', 'Bilal Baydur'); 
    
    $mail->Subject = $subject; //Mail konusu
    $mail->Body = $message; //Mail içeriği

    //$mail->addAttachment('dokuman.jpg', 'resim_ismi.jpg'); //Mail içerisinde ek dosya gönderimi sağlar.
    
    $response = $mail->send(); //Maili gönder ve sonucu değişkene aktar
    if ($response) { //Mail gönderildi mi
      $Success = 'Mail başarıyla gönderildi';
    }
    else {
      $Error = 'Mail gönderilemedi. Mail hata mesajı: ' . $mail->ErrorInfo; //Mail gönderilemezse sebebini belirten hata mesajını ekrana yazdır
    }
  }
}

?>


<html>      
    <head>  
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Php Mailer</title>
    </head>
    <body>          
        <nav class="navbar navbar-dark bg-dark navbar-expand-lg ">
            <div class="container">
              <a class="navbar-brand" href="#">Bilal Baydur</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

            </div>
          </nav>   
          <div class="container p-5">
            <?php
              if (!empty($Success)) {
                echo "<div class=\"alert alert-success\" role=\"alert\">
                                  " . $Success . "
                                </div>";
              }

              if (!empty($Error)) {
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                                  " . $Error . "
                              </div>";
              }
            ?>
            <form  method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                  <label for="sendAdress" class="form-label">Alıcı</label>
                  <?php echo "<input type=\"text\" class=\"form-control\" id=\"sendAdress\" name=\"sendAdress\" value=\"".$sendAdress."\" required>"; ?> 
                  <div class="invalid-feedback">
                    Alıcı alanını boş bırakamazsınız!
                  </div>                
                </div>        
                <div class="mb-3">
                  <label for="subject" class="form-label">Konu</label>
                  <?php echo "<input type=\"text\" class=\"form-control\" id=\"subject\" name=\"subject\" value=\"".$subject ."\" required>"; ?> 
                  <div class="invalid-feedback">
                    Konu alanını boş bırakamazsınız!
                  </div>                
                </div>
                <div class="mb-3">
                  <label for="message" class="form-label">Mesaj</label>
                  <?php echo "<textarea  class=\"form-control\" id=\"message\" name=\"message\" rows=\"10\" required>".$message."</textarea>"; ?>
                  <div class="invalid-feedback">
                    Mesaj alanını boş bırakamazsınız!
                  </div>
                </div>
 
                <button type="submit" class="btn btn-primary">Gönder</button>
              </form>


          </div>    
  

          <script type="text/JavaScript">
          // Example starter JavaScript for disabling form submissions if there are invalid fields
          (function () {
            'use strict'
          
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')
          
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
              .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                  if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                  }
          
                  form.classList.add('was-validated')
                }, false)
              })
          })()
        </script> 
        <script  src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>