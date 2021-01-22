<html lang="en">
<head>
    <link rel="stylesheet" href="/css/lab_login.css">
    <link rel="stylesheet" href="/css/font_awesome/css/all.min.css">

</head>

<body>
<div id="particles"></div>
<div id="left-content">
    <h1><i class="fas fa-flask"></i> Lab Staff Entrance</h1>
</div>
<div id="right-content">
    <h2>
        Login
    </h2>
    <h3>
    <?php if (isset($error)){
        echo $error;
    } ?>
    </h3>
    <?php

    helper('form');
    echo form_open(base_url('/Member/labstafflogin'));

    echo form_label('Username');
    echo form_input('username');

    echo form_label('Password');
    echo form_password('password');

    echo form_submit('Login','LOGIN',"id='login'");

    echo form_close();
    ?>
</div>

<script type="text/javascript" src="/js/particles.js"></script>
<script type="text/javascript" src="/js/login.js"></script>
</body>
</html>