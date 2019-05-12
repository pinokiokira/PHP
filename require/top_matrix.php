<?php
  //->juni [req REQ_021] - 2014-09-24 - check existance of image
  function jget_http_response_code($url) {
    $headers = get_headers(trim($url));
    return substr($headers[0], 9, 3);
  }
  //<-juni [req REQ_021] - 2014-09-24

 if (isset($_SESSION["timeout_lock"]) && $_SESSION["timeout_lock"]==true){
     echo "<script>window.top.location.href='logout.php'</script>";
}
?>

<?php
    $helpcount="0";
    $helpsql = "Select count(help_id) as helpcount from help WHERE to_type = 'Team' AND to_employee_master={$_SESSION["empmaster_id"]} AND help.status = 'unread'";

    $helpresult = mysql_query($helpsql);
    if ($helpresult && mysql_num_rows($helpresult)>0){
        $helprow = mysql_fetch_assoc($helpresult);
        $helpcount = $helprow["helpcount"];
    }
      $sql_countmessage = "Select id  from employee_messages where emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}') and readd='no' ";
      $res_countmessage=mysql_query($sql_countmessage);
      $countmessage=mysql_num_rows($res_countmessage);

  $_SESSION["PANELLOGO"] = 'images/softpointlogo.png';

  ?>

<style>
.userloggedinfo img {
    margin: 5px;
}
#clientSpan {
    color: rgba(255, 255, 255, 0.5);
    float: right;
    font-family: arial,sans-serif !important;
    font-size: 13px;
    line-height: 20px !important;
    margin-right: 20px;
    margin-top: 2px;
}
#loading-header {
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    position: fixed;
    display: block;
    opacity: 0.7;
    background-color: #fff;
    z-index: 99;
    text-align: center;
  }

  #loading-image-header {
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 100;
  }
  #control_nav_div{
    width:100%;
    height:50px;
    background-color: black;
  }
  #mobile-nav-toggle i{
    color:white;
    margin-left:0px;
    margin-top:9px;
  }
  #mobile-nav-toggle{
    margin-top:5px;
    margin-left:30px;
    font-size: 30px;
  }
</style>
<script>

   /* jQuery(window).load(function() {
            jQuery('#loading-header').hide();
          }); */
jQuery(document).ready(function ($) {
  window.onload=function(){
    setTimeout(function() {
      jQuery('#loading-header').hide();
    }, 2700);

  }
var flag_menu=0;
  jQuery("#mobile-nav-toggle").click(function(){
    if(flag_menu==0){
      jQuery(".leftpanel").css("display","none");
      jQuery(".rightpanel").css("margin-left","0px");
      jQuery("#mobile-nav-toggle").css("margin-left","260px");
      flag_menu=1;
    }else{
      jQuery(".leftpanel").css("display","block");
      jQuery(".rightpanel").css("margin-left","260px");
      jQuery("#mobile-nav-toggle").css("margin-left","30px");
      flag_menu=0;
    }


  });
  jQuery("#mobile-nav-toggle").toggle();
  jQuery("#mobile-nav-toggle").trigger('click');
});
</script>
<!--<div id="control_nav_div">
     <a id="mobile-nav-toggle"><i class="fas fa-align-justify"></i></a>
</div>-->
<div id="loading-header">
        <img id="loading-image-header" src="images/loaders/loader7.gif" alt="Loading..." />
    </div>
<div class="header">
  <div class="logo" style="padding-top: 11px;">
    <a href="dashboard.php">
    <img src="<?php echo $_SESSION["PANELLOGO"];?>"  alt="SoftPoint" /></a>
        <br /><span class="capital_word " id="clientSpan"  style="line-height: 20px;" >VENDOR</span>
        <!--<div style="margin-top:2px;" class="teamnote"><span style="line-height:20px;font-size: 13px">TEAM</span></div>-->
  </div>
  <div class="headerinner">
    <ul class="headmenu">
      <li <?php echo $page=='messages.php'?'':'class="odd"';?>>
        <!--<a class="dropdown-toggle" data-toggle="dropdown" href="messages.php">-->
                <a  href="messages.php">
          <span class="count"><?php echo $countmessage; ?></span>
          <span class="head-icon head-message"></span>
          <span class="headmenu-label">Messages</span>
        </a>
        <!--<ul class="dropdown-menu">
          <li class="nav-header">Messages</li>
          <li><a href=""><span class="icon-envelope"></span> New message from <strong>Jack</strong> <small class="muted"> - 19 hours ago</small></a></li>
          <li><a href=""><span class="icon-envelope"></span> New message from <strong>Daniel</strong> <small class="muted"> - 2 days ago</small></a></li>
          <li><a href=""><span class="icon-envelope"></span> New message from <strong>Jane</strong> <small class="muted"> - 3 days ago</small></a></li>
          <li><a href=""><span class="icon-envelope"></span> New message from <strong>Tanya</strong> <small class="muted"> - 1 week ago</small></a></li>
          <li><a href=""><span class="icon-envelope"></span> New message from <strong>Lee</strong> <small class="muted"> - 1 week ago</small></a></li>
          <li class="viewmore"><a href="messages.php">View More Messages</a></li>
        </ul>-->
      </li>
      <!-- <li <?php // echo $page=='dashboard.php'?'':'class="odd"';?>>
        <a   href="dashboard.php" >

        <span class="head-icon head-event"></span>
        <span class="headmenu-label">Dashboard</span>
        </a> -->
        <!--<ul class="dropdown-menu newusers">
          <li class="nav-header">New Users</li>
          <li>
            <a href="">
              <img src="images/photos/thumb1.png" alt="" class="userthumb" />
              <strong>Draniem Daamul</strong>
              <small>April 20, 2013</small>
            </a>
          </li>
          <li>
            <a href="">
              <img src="images/photos/thumb2.png" alt="" class="userthumb" />
              <strong>Shamcey Sindilmaca</strong>
              <small>April 19, 2013</small>
            </a>
          </li>
        </ul>-->
      <!-- </li> -->
      <li <?php echo $page=='help.php'?'':'class="odd"';?>>
        <a href="help.php">
        <span class="count"><?php echo $helpcount;?></span>
        <span class="head-icon head-help" style="background-repeat:no-repeat;"></span>
        <!-- <span class="headmenu-label"><?//=$_SESSION['Help']; ?></span> -->
        <span class="headmenu-label">Help</span>
        </a>
        <!--<ul class="dropdown-menu">
          <li class="nav-header">Statistics</li>
          <li><a href=""><span class="icon-align-left"></span> New Reports from <strong>Products</strong> <small class="muted"> - 19 hours ago</small></a></li>
          <li><a href=""><span class="icon-align-left"></span> New Statistics from <strong>Users</strong> <small class="muted"> - 2 days ago</small></a></li>
          <li><a href=""><span class="icon-align-left"></span> New Statistics from <strong>Comments</strong> <small class="muted"> - 3 days ago</small></a></li>
          <li><a href=""><span class="icon-align-left"></span> Most Popular in <strong>Products</strong> <small class="muted"> - 1 week ago</small></a></li>
          <li><a href=""><span class="icon-align-left"></span> Most Viewed in <strong>Blog</strong> <small class="muted"> - 1 week ago</small></a></li>
          <li class="viewmore"><a href="charts.html">View More Statistics</a></li>
        </ul>-->
      </li>
      <li class="right">
        <div class="userloggedinfo">
           <?php  $queryimg =   mysql_query("select em.image , V.StorePoint_Image,v.id AS vendor_id, v.name AS vendor_name,v.email AS vendor_email, v.phone AS vendor_phone from employees_master em LEFT JOIN vendors V ON V.id = em.StorePoint_vendor_id WHERE empmaster_id='". $_SESSION['empmaster_id'] ."'");
          $client_img = mysql_fetch_array($queryimg);?>
                      <span class="locthumb">

                      <?php
                        //if ($client_img["StorePoint_Image"] != ""){
                       ?>
                          <img src="<?php echo APIPHP;?>images/<?php echo $client_img["StorePoint_Image"];?>" onerror="this.src='images/Default - User.png'" alt="" style="max-height: 80px;max-width: 80px;height:80px;width:80px;"/>
                      <?php //} ?>
                      </span>
                      <span class="empthumb">
                      <?php if ($client_img["image"] != ""){ ?>
                            <img src="<?php echo APIPHP;?>images/<?php echo $client_img["image"];?>" onerror="this.src='images/Default - User.png'" alt="" style="max-height: 80px;max-width: 80px;height:80px;width:80px;"/>
                            <?php } else { ?>
                            <img src="images/Default - User.png" alt="" style="max-height: 80px;max-width: 80px;height:80px;width:80px;">
                            <?php } ?>
                     </span>
          <div class="userinfo notranslate">
            <h5><?=$_SESSION['first_name'].' '.$_SESSION['last_name'].'<small> - '.$_SESSION['email'].'</small>';?></h5>
            <ul class="notranslate">
              <li><a href="setup_editprofile.php">Edit Profile</a></li>
              <li><a href="help.php"><?php echo 'Help'; ?> (<span id="countmsg" style="line-height: 22px;"><?php echo $helpcount;?></span>)</a></li>
              <li><a href="logout.php">Log Out</a></li>
            </ul>
          </div>
        </div>
      </li>
    </ul><!--headmenu-->
  </div>
</div>
<script>
var lockidleTime = 0;
  var lockidleInterval ;
  var locgrowl = true;
       jQuery(document).ready(function () {
		   
		   /* jQuery(".locthumb").on("click", function () {
                if (locgrowl){
                    jQuery.jGrowl("<?php echo addslashes($client_img["vendor_name"] . " (".$client_img["vendor_id"].") <br> " .$client_img["vendor_phone"]."<br>".$client_img["vendor_email"]);?>");
                    locgrowl = false;
                }
            }); */
           //Increment the idle time counter every minute.
            lockidleInterval = setInterval(locktimerIncrement, 60000); // 1 minute

           //Zero the idle timer on mouse movement.
           jQuery(this).mousemove(function (e) {
               lockidleTime = 0;
           });
           jQuery(this).keypress(function (e) {
               lockidleTime = 0;
           });
       });

       function locktimerIncrement() {
          lockidleTime = lockidleTime + 1;
       //   console.log(lockidleTime);
          if (lockidleTime > 114) { // 5 minutes
              document.location="logout.php";
          }
          else if (lockidleTime > 112) { // 3 minutes
              jQuery.ajax({
                        url: 'ajax_set_timeout_lock.php',
                        type: 'POST',
                        success:function(data){

                        }
                        });
               //clearTimeout(lockidleInterval);
                if(jQuery('div.lockscreen').length==0){
                  jQuery('body').append('<div class="lockscreen"><div class="lock-overlay"></div><div class="logwindow"><div class="logwindow-inner"><form id="lockform" name="lockform" method="post" autocomplete="off"><h3>Locked</h3><?php if ($client_img["image"]!=""){ echo "<img src=\'".APIPHP."images/".$client_img["image"]."\' style=\'width:100px;height:100px\'>";}else{?><img src="images/Default - User.png" alt="" style="width:100px;height:100px"><?php }?><h5>Logged In: <?php echo $_SESSION['first_name'].' '.$_SESSION['last_name']?></h5><input type="password" name="idlepassword" id="idlepassword" class="input-block-level" placeholder="Enter password and hit enter to unlock..." /> <div style="text-align:center;"><button class="btn btn-info" type="submit">Log In</button>&nbsp;<a class="btn btn-info" href="logout.php" style="color:white;">Log Out</a> </div></form> </div></div></div>');
                  jQuery("#idlepassword").val('');

                   jQuery("#lockform").live("submit",function(e){

                    e.preventDefault();
                    jQuery.ajax({
                        url: 'check_password_idle.php',
                        data: {password: jQuery('#idlepassword').val()},
                        type: 'POST',
                        success:function(data){
                            if(data==1){
                                jQuery(".lockscreen").fadeOut(100);
                                jQuery(".lockscreen").remove();
                            }else{
                                document.location="logout.php";
                            }
                        }
                        });
                    })

                    jQuery("#idlepassword").live("keypress",function(event) {
                        if (event.which == 13) {
                            event.preventDefault();
                            jQuery("#lockform").submit();
                        }
                    });
               }

           }
       }
</script>
<?php
$uur=parse_url($_SERVER['REQUEST_URI']);
$page_name=basename($uur['path']);
?>
<style>
<?
//if($_SESSION['lang']=="en"  || $page_name!='dashboard.php'){ ?>
.userloggedinfo ul li a { padding: 2px 5px 3px 5px; }
<? // } ?>
</style>
