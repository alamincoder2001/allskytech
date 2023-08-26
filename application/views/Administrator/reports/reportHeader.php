<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
    <style>
    body {
        padding: 20px !important;
    }

    body,
    table {
        font-size: 13px;
    }

    table th {
        text-align: center;
    }

    .logo {
        width: 30%;
        float: left;
        overflow: hidden;
    }

    .logo img {
        margin-left: 55px;
    }

    .heading {
        width: 70%;
        float: left;
        padding-top: 25px;
    }
    </style>
</head>

<body>
    <?php 
        $branchId = $this->session->userdata('BRANCHid');
        $companyInfo = $this->Billing_model->company_branch_profile($branchId);
    ?>
    <div class="container">
        <div class="row">
            <div class="logo">
                <img src="<?php echo base_url();?>uploads/company_profile_thum/<?php echo $companyInfo['Company_Logo_org']; ?>"
                    alt="Logo" style="height:138px; width:170px;" />
            </div>

            <div class="heading" style="padding-top:25px;">
                <strong style="font-size:22px;"><?php echo $companyInfo['Company_Name'] ?></strong><br>
                <p style="white-space: pre-line; font-size:15px;"> <?php echo $companyInfo['Repot_Heading'] ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
            </div>
        </div>
    </div>
</body>

</html>