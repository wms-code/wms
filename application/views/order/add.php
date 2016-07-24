  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
    <div class="col-md-8">
      <h3 style="margin-top: 5px">
        Order
      </h3>
    </div>
    <div class="col-md-4">

    </div>
  </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <form action="" method="post">
         <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Add New Order</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Select A Client Name</label>
                <select name="user" class="form-control select2" style="width: 100%;">                 
                  <?php
                   if ($user->num_rows() > 0)  {
                     foreach ($user->result() as $row) {
                      echo"<option value=".$row->id."> $row->first_name $row->last_name ($row->email )</option>";
                      }
                    }
                    ?>
                </select>
              </div>
              <br />
              <div class="form-group">
                <label>Select A Product/Service</label>
                <select name="product" class="form-control select2" style="width: 100%;">
                <option value="none" selected="selected">Empty ...</option>
                  <?php
                   if ($plans->num_rows() > 0)  {
                     foreach ($plans->result() as $row) {
                      echo"<option value=".$row->package_id."> $row->product_category - $row->product_name ($row->product_price )</option>";
                      }
                    }
                    ?>
                </select>
              </div>
               <br />
              <div class="form-group">
                  <label>Domain Name</label>
                  <input name="domain" pattern="^([a-z0-9-]{2,61}?\.)+[a-z]{2,24}$" type="text" class="form-control" placeholder="example.com" required>
              </div>
              <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="type" id="optionsRadios1" value="hosting" >
                      Place Order Only Hosting
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="type" id="optionsRadios2" value="domain">
                      Place Order Only Domain
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="type" id="optionsRadios3" value="bundle" checked="">
                      Place Order Domain with Hosting
                    </label>
                  </div>
                </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
      <!-- /.box -->
     </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->