 
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

     

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">PAGES</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="<?php echo base_url('customer')?>"><i class="fa fa-user"></i> <span> Client Management</span></a></li>
        <li ><a href="<?php echo base_url('order')?>"><i class="fa fa-book"></i> <span> Order Management</span></a></li>
        <li><a href="#"><i class="fa fa-files-o"></i> <span> Invoices</span></a></li>
        <li><a href="<?php echo base_url('products/domains')?>"><i class="fa fa fa-list-alt"></i> <span> Domain list</span></a></li>
        <li><a href="<?php echo base_url('products')?>"><i class="fa fa-th-list"></i> <span> Web Hosting</span></a></li>
        <li><a href="<?php echo base_url('tickets')?>"><i class="fa  fa-life-ring"></i> <span>Support Tickets</span></a></li>
        
        <li class="treeview">
          <a href="#"><i class="fa fa-cog"></i> <span>Settings</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('setup/hosting')?>">Hosting plan & Price</a></li>
            <li><a href="<?php echo base_url('setup/domain')?>">Domain price</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>