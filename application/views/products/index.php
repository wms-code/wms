  <!-- Content Wrapper. Contains page content -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
    <div class="col-md-8">
      <h3 style="margin-top: 5px">
        Web Hosting 
      </h3>
    </div>
    <div class="col-md-4">
      <div class="pull-right">
      <a href="<?php echo base_url('order/add')?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add New</a>
      </div>
    </div>
  </div>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
<div class="row">
    <div class="col-md-12">
      <div class="box box-primary" style="padding-top:15px">
        <div class="box-body">
              
    <div class="row">
        <div class="col-xs-12">
        
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Orders</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="sorder" class="table table-bordered">
              <thead>
              <tr>
                  <th>ID</th>
                  <th>Domain name</th>
                  <th>Hosting Plan</th>
                  <th>Status</th>
                  <th>Date</th>
               </tr>
                </thead>
                <tbody>
               <?php echo $html ?>
              </tbody>
              <tfoot>
              <tr>
                  <th>ID</th>
                  <th>Domain name</th>
                  <th>Hosting Plan</th>
                  <th>Status</th>
                  <th>Date</th>
               </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>




          
        </div>
      </div> 
    </div>
</div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->