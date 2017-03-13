<!DOCTYPE>
<html>
<head>
<title>Chat - Customer Module</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js'></script>
<script src='https://code.jquery.com/jquery-2.1.4.min.js'></script>


<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Nunito:400,300,700'>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="original_style.css">

</head>
 <?
 require('connect.php');
session_start();

function loginForm(){
    echo'


      <div class="container" id="loginform">
      <div class="form-container ">
        <form class="login-form" action="index.php" method="post">
          <h3 class="title">Hello.</h3>
          <div class="form-group" id="username">
            <input class="form-input" tooltip-class="username-tooltip" placeholder="Username" id="name" name="name" required>
            <span id="username-tool"class="tooltip username-tooltip">Whats your username?</span>
          </div>
          <div class="form-group" id="password">
            <input type="password" class="form-input" tooltip-class="password-tooltip" placeholder="Password" id="pass" name="pass" required>
            <span class="tooltip password-tooltip">Whats your password?</span>
          </div>

          <div class="form-group">
            <input type="submit" name="enter" id="enter" class="login-button" value="Login">
          </div>
        </form>

      </div>
    </div>

    ';
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if($_POST['name'] != "" && $_POST['pass'] != "")
    {
        $username = mysqli_escape_string($db,$_POST['name']);
        $password = mysqli_escape_string($db,$_POST['pass']);
         
        $sql="SELECT email FROM login WHERE email='$username' and password='$password'";
	    $result = mysqli_query($db,$sql)
        or die("Error: ".mysqli_error($db));
	    $row=mysqli_fetch_array($result);
	   
	    $count=mysqli_num_rows($result);
	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count==1)
	{
		//session_register("myusername");
		$_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
	}
	else
	{
		echo "<script>alert('Incorrect Email ID or Password')</script>";
	}
	
}
else{
    echo "<script>alert('Please enter both the fields');</script>";
}

        

    }
    

?>
<?
if(isset($_GET['logout'])){

    //Simple exit message
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
    fclose($fp);

    session_destroy();
    header("Location: index.php"); //Redirect the user
}
?>
<?php
if(!isset($_SESSION['name'])){

    loginForm();
}
else{
?>
<div id="wrapper">
    <div id="menu">
        <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
        <p class="logout"><a id="exit" href="#"><button class="login-button1">Logout</button></a></p>
        <div style="clear:both"></div>
    </div>
    <div id="chatbox">
    	<?php
if(file_exists("log.html") && filesize("log.html") > 0){
    $handle = fopen("log.html", "r");
    $contents = fread($handle, filesize("log.html"));
    fclose($handle);

    echo $contents;
}
?>
    </div>
<div class="form-container1">
    <form name="message" action="" class="login-form" >
        <div >
        <input name="usermsg" type="text" id="usermsg" size="63" class="msg" />
        </div>
        <input name="submitmsg" type="submit"  id="submitmsg" value="Send" class="login-button1" />
    </form>
</div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to end the session?");
		if(exit==true){window.location = 'index.php?logout=true';}
	});

	$("#submitmsg").click(function(){
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});
		$("#usermsg").attr("value", "");
		return false;
	});
});

setInterval(function () { loadLog(); },1000);
function loadLog(){
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){
				$("#chatbox").html(html); //Insert chat log into the #chatbox div

				//Auto-scroll
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				}
		  	},
		});

	}


</script>

<?php
}
?>
</body>
<script src="query.js"></script>
</html>
