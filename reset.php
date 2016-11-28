<?php
/**
 * Created by Maddish
 */
// Start PHP session
session_start();
require_once 'classes/class.ldap.php';
$user_home = new LDAP();
if($user_home->is_logged_in())
{
        $user_home->redirect('index.php');
}
$urltoken=isset($_GET['token'])?$_GET['token']:'';
$error=isset($_GET["error"])?$_GET["error"]:'';
$msg1=$msg2=$msg3=$msg="";
switch($error){
  case "1":
    $msg1 ="
    <span class='error'><i class='fa fa-exclamation-triangle icon checkko alert-danger'></i> Las dos  
contraseñas no coincide con que has insertado</span>";
  break;
  case "2":
    $msg2="
    <span class='error'><i class='fa fa-exclamation-triangle icon checkko alert-danger'></i>Nombre de usuario no válido
  </span>";
    break;
  case "3":
    $msg3="
  <span class='error'><i class='fa fa-exclamation-triangle icon checkko alert-danger'></i>Código de verificación no válido</span>";
    break;
  case "4":
    $msg="
    <div class='alert alert-error'>
    <button class='close' data-dismiss='alert'>×</button>
    Código de verificación caducado 
    </div>";
    break;
  case "5":
    $msg="
    <div class='alert alert-error'>
    <button class='close' data-dismiss='alert'>×</button>
    Ha habido un error. No se ha podido restablecer la contraseña. 
    </div>";
    break;
  case "":
    $msg1=$msg2=$msg3=$msg="";
    break;
  default:
}
require_once('header.php');

?>
<div class="container-fluid" id="login">

<?php 
                echo $msg;
    		echo '<form action="proc/reset-ps.php" method="POST" class="form-signin">
		<h2 class="form-signin-heading">Introduce los datos</h2>
		<hr>
              <label for="username">Nombre de usuario: </label><input id="username" type="text" name="username" required/><p> ' . $msg2 .'</p>
                <label for="usercode">Código de verificación: </label><input id="usercode" type="text" name="usercode" required/><p> ' . $msg3 .'</p>
                <label for="password1">Nueva contraseña: </label><input id="password1" type="password" name="password1" required/>
                <label for="password2">Repetir contraseña: </label><input id="password2" type="password" name="password2" required/><p> ' . $msg1 .'</p>
                <input type="hidden" name="token" value="'.$urltoken.'" />
                <input type="submit" name="submit" value="Submit" class="btn btn-large btn-primary" />


    		</form>';
?>
	</div><!--container-->
	<?php include 'footer.php';?>
	</body>
</html>
