<div id="head">
                <div class="content">
                     <div class="left"><a href="index.php" id="page_logo">L Share</a></div>
                     <div class="middle">
                            <input id="photo_search" type="text" placeholder="">
                      </div>
                     <div class="right">
                            <a id="users"> </a><a id="like"> </a><a <?php if($user_id>0) echo 'href= "photos_home_page.php?page_user_id='.$user_id.'"'; ?> id="myself"> </a>
                     </div>
                </div>
 </div>