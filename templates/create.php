<?php include('includes/header.php'); ?>


              <form role="form" method="post" action="create.php">
                      <div class="form-group">
                      	<label>Topic Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter Post Title">
                      </div>
                      <div class="form-group">
                        	<label>Categories</label>
                          <select class="form-control" name="category">
                            <?php foreach (getCategories() as $category): ?>
                            <option value="<?php echo $category['id'];?>"><?php echo $category["name"]?></option>
                            <?php endforeach;?>
      				            </select>
                      </div>

                    <div class="form-group">
                  	<label>Topic body</label>
                    <textarea type="text" rows="10" cols="80" name="body" class="form-control"></textarea>

                  </div>
                  <input name="do_create" type="submit" value="Create" class="btn btn-outline-primary mb-4">
                </form>
          
              


<?php  include('includes/footer.php');?>