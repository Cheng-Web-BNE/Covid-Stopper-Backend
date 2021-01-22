<?php
$num_pages = ceil(intval($num_appointments)/20);
$page_num = intval($page_num);
?>
<html>
<head>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/font_awesome/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        var testMode = '<?php echo $test_mode?>';
        var token = '<?php echo $_SESSION['jwt'];?>'
    </script>
    <script type="text/javascript" src="/js/table_enable.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(to right,<?php echo $nav_start_color?>,<?php echo $nav_end_color?>)">
    <a class="navbar-brand" href="#">Lab Staff Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if ($test_mode == "clinic"):?> active <?php endif; ?>">
                <a class="nav-link" href="/LabStaff/Clinic">Clinic Test <?php if ($test_mode=="clinic"):?> <span class="sr-only">(current)</span> <?php endif; ?></a>
            </li>
            <li class="nav-item <?php if ($test_mode=="driveThrough"):?> active <?php endif; ?>">
                <a class="nav-link" href="/LabStaff/drivethrough">Drive Through Test <?php if ($test_mode=="driveThrough"):?> <span class="sr-only">(current)</span> <?php endif; ?></a>
            </li>
            <li class="nav-item <?php if ($test_mode=="swabKit"):?> active <?php endif; ?>">
                <a class="nav-link" href="/LabStaff/swabkit">Swab Kit Test <?php if ($test_mode=="swabKit"):?> <span class="sr-only">(current)</span> <?php endif; ?></a>
            </li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link align-self-end" href="/Member/labLogOut">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex justify-content-center">


            </div>
            <div class="modal-footer">
                <button id="saveButton" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="jumbotron">
    <h1 class="display-4">Hello, <?php echo $staff_name?>!</h1>
    <p class="lead">Welcome to the Covid-Stopper Lab Staff Dashboard</p>
    <hr class="my-4">
    <p>For this type of the test: There is/are <?php echo $num_appointments ?> test(s) Open for further Operations. Thank you</p>
</div>
<table id="myTable" class="display">
    <thead>
    <tr>
        <th></th>
        <?php if ($test_mode == 'clinic'): ?>
            <th>Appointment ID</th>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>email</th>
            <th>Phone Number</th>
            <th>Clinic Name</th>
        <?php endif; ?>
        <?php if ($test_mode == 'driveThrough'): ?>
            <th>Appointment ID</th>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>email</th>
            <th>Phone Number</th>
            <th>Centre Name</th>
        <?php endif; ?>
        <?php if ($test_mode == 'swabKit'): ?>
            <th>Appointment ID</th>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>email</th>
            <th>Phone Number</th>
            <th>Send Tracking Number</th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <?php if( $test_mode=='clinic' && $num_appointments != 0): ?>
        <?php foreach ($appointments as $appointment): ?>
            <tr data-id="<?php echo $appointment['appointment_id'] ?>" data-send="">
            <td class="details-control"></td>
            <td><?php echo $appointment['appointment_id'] ?></td>
            <td><?php echo $appointment['user_id'] ?></td>
            <td><?php echo $appointment['first_name'] ?></td>
            <td><?php echo $appointment['last_name'] ?></td>
            <td><?php echo $appointment['email'] ?></td>
            <td><?php echo $appointment['phone_number'] ?></td>
            <td><?php echo $appointment['clinic_name'] ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if( $test_mode=='driveThrough' && $num_appointments != 0): ?>
        <?php foreach ($appointments as $appointment): ?>
            <tr data-id="<?php echo $appointment['appointment_id'] ?>" data-send="">
                <td class="details-control"></td>
                <td><?php echo $appointment['appointment_id'] ?></td>
                <td><?php echo $appointment['user_id'] ?></td>
                <td><?php echo $appointment['first_name'] ?></td>
                <td><?php echo $appointment['last_name'] ?></td>
                <td><?php echo $appointment['email'] ?></td>
                <td><?php echo $appointment['phone_number'] ?></td>
                <td><?php echo $appointment['centre_name'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if( $test_mode=='swabKit' && $num_appointments != 0): ?>
        <?php foreach ($appointments as $appointment): ?>
            <tr data-id="<?php echo $appointment['appointment_id'] ?>" data-send="<?php echo $appointment['send_tracking_number']?>">
                <td class="details-control"></td>
                <td><?php echo $appointment['appointment_id'] ?></td>
                <td><?php echo $appointment['user_id'] ?></td>
                <td><?php echo $appointment['first_name'] ?></td>
                <td><?php echo $appointment['last_name'] ?></td>
                <td><?php echo $appointment['email'] ?></td>
                <td><?php echo $appointment['phone_number'] ?></td>
                <td><?php echo $appointment['send_tracking_number'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<?php if ($num_pages != 0): ?>
<nav aria-label="...">
    <ul class="pagination">
        <li class="page-item <?php echo(($page_num == 1) ? :'disabled'); ?>" >
            <?php if ($page_num == 1): ?>
            <span class="page-link">Previous</span>
            <?php else:?>
            <a class="page-link" href="/LabStaff/<?php echo ($test_mode.'/'.strval(intval($page_num)-1))?>">Previous</a>
            <?php endif; ?>
        </li>

        <?php for ($i = $page_num-2;$i <= $num_pages;$i++): ?>
        <?php if ($i > 0):?>
        <li class="page-item <?php echo ($i == $page_num)? 'active':'' ?>"><a class="page-link" href="<?php echo ($i == $page_num)? '#':('/LabStaff/'.$test_mode.'/'.$i) ?>"><?php echo $i; ?></a></li>
        <?php endif; ?>
        <?php endfor;?>
        <li class="page-item<?php echo(($page_num == $num_pages) ? :'disabled'); ?>">
            <?php if ($page_num == $num_pages): ?>
                <span class="page-link">Next</span>
            <?php else:?>
            <a class="page-link" href="/LabStaff/<?php echo ($test_mode+'/'+strval(intval($page_num)+1))?>">Next</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>
<?php endif; ?>
</body>
</html>
