<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Chat Dalí - Chat GPT</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/main.css'>
</head>
<body>

<?php
// Chat GPT Api Keyi api.txt dosyası oluşturarak içerisine ekleyin. //
$api = file_get_contents('api.txt');
if((!empty($_POST['question']) && $_POST['ara'])) {
  $question = $_POST['question'];
  $question = json_encode($question);
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.openai.com/v1/images/generations',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "prompt": '.$question.',
    "n": 1,
    "size": "1024x1024"
  }',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer '.$api,
      'Content-Type: application/json'
    ),
  ));
  
  $response = curl_exec($curl);
  curl_close($curl);
  $response = json_decode($response, true);
  
  if (isset($response['data'][0]['url'])) {
      $imageUrl = $response['data'][0]['url'];
      $reply = '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" alt="'.$_POST['question'].'" class="w-25 bg-light p-2 text-dark TextStyle"/></a>';
  }else{
    $reply = "<span class='text-white'>Beklenen yanıt formatı alınamadı.</span>";
  }
}?>
<div class="bg text-center">
    <div class="container">
        <div class="centered">
        <p class="firstLine"> Chat Dalí <img src="assets/img/dali-moustache.png"></p>
        <p class="secondLine">"Düşünüz Görsel Olsun!"</p>
        <form method="POST">
            <p> 
              <input class="InputStyle" placeholder="Görselinizi betimleyin." value="<?php echo $_POST['question'];?>" autocomplete="off" name="question" type="text" require> 
              <div class="clearfix"></div>
              <small class="TextStyle">Örnek: Mavi gözlü beyaz kedi.</small>
            </p>
            <button type="submit" name="ara" value="ara" class="btn btn-warning InputStyleBTN"> Oluştur!</button>
        </form>
        <?php if((!empty($_POST['question']) && $_POST['ara'])) {?>
        <div class="clearfix"></div><br>
        <p> <?php echo $reply; ?> </p>
        <?php }?>
        </div>
    </div>
</div>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src='assets/js/bootstrap.min.js'></script>
</body>
</html>