<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>



<?php
session_start();
$db = new PDO("mysql:dbname=SMASH", "root", "root");
if($_SESSION["status"]=="log_in"){
    $user_id = $_SESSION["id"];
    if(isset($user_id)){
     $query = "SELECT * FROM user_info WHERE user_id='$user_id'";
     $rows = $db->query($query);
     $valid = false;
     $email = "";
     foreach ($rows as $row) {
       if(is_null($row)){
         $valid = false;
         break;
       }
       else{
         $email = $row['user_email'];
         ?>
         <h1 id='heading'>내 정보 변경하기</h1>
         <div class="userinfo">
           <p>아이디 <?=$row["user_id"]?></p>
           <p>이메일 <?=$row['user_email']?></p>
         </div>

          <div class="modifier">
            <ul>
              <li><a href="modify_info.php?modify=pw">비밀번호 변경하기</a></li>
              <li><a href="modify_info.php?modify=em">이메일 변경하기</a></li>
              <li><a href="mypage.php">돌아가기</a></li>
            </ul>
          </div>
          <br>
          <hr>
          <br>
        <?php
         $valid = true;
       }
     }
    }
    else{
      echo "접근할 수 없습니다.";
    }
}
?>


<?php
if($_GET['modify']==='pw'){
  ?>



  <form class="pw_modify" action="modify_info.php?modify=pw&put=true" method="post">
    <p>이전 비밀번호:<input type="password" name="pre_pw" value=""></p>
    <p>새로운 비밀번호:<input type="password" name="new_pw" value=""></p>
    <input type="submit" name="" value="변경하기">
  </form>



<?php
$valid2 = false;
$pre_pw = $_POST['pre_pw'];
$new_pw = $_POST['new_pw'];

if($_GET['put']==='true' && isset($pre_pw) && isset($new_pw)){
  $query2 = "SELECT * FROM user_info WHERE user_id='$user_id'";
  $pw_rows = $db->query($query2);
  // $pw_rows->bindParam(':user_id',$user_id,PDO::PARAM_INT);
  // $pw_rows->execute();
  foreach ($pw_rows as $pw_row) {
    if($pre_pw === $pw_row['user_pw']){
      $valid2 = true;
      break;
    }
  }
  if($valid2 === true){
    $query_update = "UPDATE user_info SET user_pw = '$new_pw' WHERE user_id='$user_id'";
    $db->query($query_update);
    echo "<script>alert('비밀번호가 변경되었습니다.');</script>";
  }
  else{
    echo "<script>alert('비밀번호가 일치하지 않습니다.');</script>";
  }
}

}
else if($_GET['modify']==='em'){
  ?>
  <form class="em_modify" action="modify_info.php?modify=em&put=true" method="post">
    <p>이전 이메일:<?=$email?></p>
    <p>새로운 이메일:<input type="text" name="new_em" value=""></p>
    <input type="submit" name="" value="변경하기">
  </form>
  <?php
  $new_em = $_POST['new_em'];
  if($_GET['put']==='true'){
    $query3 = "UPDATE user_info SET user_email ='$new_em' WHERE user_id='$user_id'";
    $db->query($query3);
    echo "<script>alert('이메일이 변경되었습니다.');</script>";
  }
}
?>
<br>
</body>
</html>
