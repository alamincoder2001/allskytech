
<?php  
    $branch_id=$this->session->userdata('BRANCHid');
    $stickers = $this->db->query("select * from tbl_sticker where branch_id = $branch_id")->result();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Customer Sticker Generator</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
		   .article{}
		   .content{width:120px;float:left;padding:2px;}
		   .name{height:auto;width:120px;font-size:11px;}
		   .img{height:60px;width:120px;}
		   .pid{height:15px;width:120px;}
		   .price{height:10px;width:120px;}
		   .date{height:90px;width:20px;float:right;writing-mode: tb-rl;}
		   .mytext{height:25px !important;padding: 2px;}
        </style>
        <link type="text/css" rel="stylesheet" href="<?php echo base_url('barcode/style.css'); ?>" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="shortcut icon" href="<?php echo base_url('barcode/favicon.ico'); ?>" />
        <script src="<?php //echo base_url('barcode/jquery-1.7.2.min.js'); ?>"></script>
        <script src="<?php //echo base_url('barcode/barcode.js'); ?>"></script>
		<script type="text/javascript">
          function printpage() {
          // document.getElementById('printButton').style.visibility="hidden";
			  document.getElementById("printButton").style.cssText = "display:none;height:0px;margin-top:0px"
			  document.getElementById('printButton2').style.display="none";
			  window.print();
			  document.getElementById('printButton').style.display="block";  
			  location.reload();
          }
       </script>

    </head>
    <body class="">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form class="form-horizontal" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                        <section class="" id="printButton" style="background:#f4f4f4;padding:5px 0px 15px 0px">
                            <div class="">
                                <div class="col-sm-12 text-center">
                                    <h3 class="text-info">Customer Sticker Generator</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div>
                                    <label for="note" class="col-md-2 text-center">Description</label>
                                    <div class="col-md-4">
                                        <input type="text" list="datalistOptions" name="article" class="form-control">
                                        <datalist id="datalistOptions">
                                            <?php foreach($stickers as $item){?>
                                            <option value="<?php echo $item->name; ?>">
                                            <?php };?>
                                        </datalist>
                                    </div>
                                    <label for="note" class="col-md-1 text-center">Quantity</label>
                                    <div class="col-md-2">
                                                <input type="number" min=1 step="1" name="get_qty" class="form-control">
                                    </div>
                                <div>
                                <div>
                                    <input type="submit" name="submit" value="Generate" class="btn btn-primary btn-sm"  />
                                    <input name="print" type="button" value="Print" id="printButton2" onClick="printpage()" class="btn btn-success btn-sm" />
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="output col-md-10 col-md-offset-1">
                    <section class="output">
                        <?php 
                            if(isset($_REQUEST['submit'])){
                            $article = $_POST['article'];
                            $get_qty = $_POST['get_qty'];
                            $exists = $this->db->query("select * from tbl_sticker where name = '$article' and branch_id = $branch_id ")->num_rows();
                            if($exists == 0){
                                $this->db->query("insert into tbl_sticker(name,branch_id)values('$article', $branch_id)");
                            }
                            for ($x = 1; $x <= $get_qty; $x++) {
                        ?>

                            <div style="float:left;margin:0px;padding:0; height:80px; width:180px;overflow:hidden;border:1px solid #ccc;box-sizing:border-box;float: left">
                                <div style="width: 18 0px; height:115px;text-align: center;margin:0;padding:10px 0px 0px 0px;">
                                    <p style="font-size: 11px;text-align: center;margin:0px;letter-spacing: .5px;"><strong style="text-transform: capitalize;">Name:</strong> <?php echo $sale->Customer_Name; ?></p>
                                    <p style="font-size: 12px;text-align: center;margin:0px;letter-spacing: .5px;><strong style="text-transform: capitalize;">Address:</strong> <?php echo $sale->Customer_Address;?></p>
                                    <span class="article" style="font-size: 12px;"><?php echo $article; echo $row  ?></span>
                                </div>
                            </div>
                            
                        <?php } 
                    } ?>
                    </section>
                </div>
            </div>
                    
        </div>

    </body>
</html>


