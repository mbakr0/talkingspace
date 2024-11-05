              
            </div>
            
          </div>
          
        </div>
        <div class="col-md-4">
          <div id="sidebar">
            <div class="block">
              <h3 class="my-5">Login Form</h3>
              <?php if(isLoggedIn()) : ?>
                <div class="userdata">
                  Welcome back, <?php echo getUser()['username'];?>
                </div>
                <br>
                <form role="form" method="post" action="logout.php">
                  <input type="submit" value="Logout"name="do_logout" class="btn btn-primary">
                  
                </form>
              <?php else : ?>
              <form role="form" method="post" action="login.php">
                  <div class="form-group">
                    <input name="username" type="text" class="form-control" placeholder="Username">
                  </div>
                  <div class="form-group">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                  </div>
                  <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                  </div>
                  <button name="do_login" type="submit" class="btn btn-primary">Login</button>
                  <a href="register.php" type="button" class="btn btn-outline-secondary">Create account</a>
                </form>
              <?php endif;?>


              
            </div>
            <div class="block">
              <h3 class="my-4">Categories</h3>
              <div class="list-group">
                <a href="topics.php" class="list-group-item <?php echo is_active(null);?>">All topics
                  <span class="badge float-right"><?php echo $totalTopics;?></span>
                </a>
                 <?php foreach (getCategories() as $category): ?>
                  <a href="topics.php?category=<?php echo $category['id'];?>" class="list-group-item <?php echo is_active($category['id']);?>"><?php echo $category['name'];?></a>
                <?php endforeach;?>
              </div>
              
            </div>
            
          </div>
        </div>
        
      </div>

    </main><!-- /.container -->
    <script type="text/javascript" src="<?php echo BASE_URI ;?>templates/js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URI;?>templates/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URI;?>templates/js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">CKEDITOR.replace("body");</script>
    <script type="text/javascript">CKEDITOR.replace("about");</script>

    </body>
</html>
