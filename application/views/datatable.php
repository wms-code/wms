<html>
<head>
<!-- jQuery 2.1.4 -->
<script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">


<script type="text/javascript">
 
$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "http://localhost:8080/CodeIgniter-admin/data"
    } );
} );
</script>
</head>
<body>

<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Gender</th>
            </tr>
        </tfoot>
    </table>    

    </body>

</html>