<?php include("includes/header.php");?>


<form role="form" method="post" action="register.php" enctype="multipart/form-data">
                  <div class="form-group">
                  	<label>Name*</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Your Name">
                  </div>
                  <div class="form-group">
                  	<label>Email Address*</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Your Email Address">
                  </div>
                  <div>
                  <label>Chosse Username*</label>
                    <input type="text" name="username" class="form-control" placeholder="Create A Username">
                  </div>
                  <div class="form-group">
                  	<label>Password*</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter Password">
                  </div>
                  <div>
                  <label>Confirm Password*</label>
                    <input type="password" name="password2" class="form-control" placeholder=" Enter Password Again">
                  </div>
                  <div class="form-group">
                      <label class="mt-3">Upload Avatar</label>
                      <input type="file" name="avatar" class="form-control-file">
                      <p class="help-block"></p>
                  </div>

                  <div class="form-group">
                  	<label>About Me</label>
                    <textarea type="text" id="about" rows="6" cols="80" name="about" class="form-control" placeholder="Tell us about yourself (optional)"></textarea>
                  </div>
                  <input type="submit" name="register" value="Register" class="btn btn-outline-secondary mb-4">

                </form>

           
<?php include("includes/footer.php");?>