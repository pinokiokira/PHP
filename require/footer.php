<?php 

?>
                <div class="footer">
                    <div class="footer-left">
                        <span><?php echo str_replace($_SESSION["SITENAME"],'<a href="'.$_SESSION["SITE_URL"].'" target="_blank" >'.$_SESSION["SITENAME"].'</a>',$_SESSION["COPYRIGHT"]);?></span>
                    </div>
                    <div class="footer-right">
                        <span>Designed By: <a href="<?php echo $_SESSION["DESIGNEDBY_URL"];?>" target="_blank"><?php echo $_SESSION["DESIGNEDBY_NAME"];?></a></span>
                    </div>
                </div>