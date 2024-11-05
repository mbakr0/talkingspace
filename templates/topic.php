
<?php include('includes/header.php'); ?>

 <ul class="topics">
              	<li id="main-topic" class="topics topic">
              		<div class="row">
                    	<div class="col-md-3">
                    		<div class="user-info">
                    			<img class="avatar float-left" src="<?php echo BASE_URI;?>images/avatars/<?php echo $topic['avatar']?>">
                    			<ul>
                    				<li><strong><?php echo $topic["username"];?></strong></li>
                    				<li><?php echo userPostCount($topic["user_id"]);?> Posts</li>
                    				<li><a href="<?php echo BASE_URI;?>topics.php?id=<?php echo $topic['user_id']?>">Profile</a></li>
                    			</ul>
                    			
                    		</div>
                    	</div>
                    	<div class="col-md-9"> 
                    		<div class="topic-content float-right">
                    			<p>
                    			  <?php echo $topic["body"];?>
                    			</p>
                    			
                    		</div>
                    		
                    	</div>
                    </div>
              		
              	</li>

                <?php foreach($replies as $reply):?>
                <li class="topics topic">
                  <div class="row">
                      <div class="col-md-3">
                        <div class="user-info">
                          <img class="avatar float-left" src="<?php echo BASE_URI;?>images/avatars/<?php echo $reply['avatar']?>">
                          <ul>
                            <li><strong><?php echo $reply["username"];?></strong></li>
                            <li><?php echo userPostCount($reply["user_id"]);?> Posts</li>
                            <li><a href="<?php echo BASE_URI;?>topics.php?id=<?php echo $reply['user_id']?>">View Topics</a></li>
                          </ul>
                          
                        </div>
                      </div>
                      <div class="col-md-9"> 
                        <div class="topic-content float-right">
                          <p>
                            <?php echo $reply["body"];?>
                          </p>
                          
                        </div>
                        
                      </div>
                    </div>
                  
                </li>
              <?php endforeach;?>

              	
              </ul>
              <?php if(isLoggedIn()):?>
              <h3>Replay to the topic</h3>
              <form role="form" method="post" action="topic.php?id=<?php echo $topic['id'];?>">
              	<div class="form-group">
                  	<label>Topic body</label>
                    <textarea type="text" rows="10" cols="80" name="body" class="form-control"></textarea>
                    <button name="do_reply" type="submit" class="btn btn-primary btn-lg btn-block">Reply</button>
                  </div>
              	
              </form>
              <?php else :?>
                <p class="alert alert-dark mx-2" role="alert">Login to reply</p>
              <?php endif;?>


              
<?php  include('includes/footer.php');?>
