<div class="content_scroll" style="width: 850px; ">
    <a id="printIcon" style="cursor:pointer"> <i class="fa fa-print" style="font-size:24px;color:green"></i> Print</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <!-- <a href="<?php echo base_url(); ?>customerPaymentPage" title="" class="buttonAshiqe">Go Back</a> -->
    <div id="reportContent">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12">
                <h6 style="background:#ddd; text-align: center; font-size: 18px; font-weight: 900; padding: 5px; color: #bd4700;">Employee Payment Slip</h6>
            </div>
        </div>
        <?php
        // echo '<pre>';
        // print_r($payment);
        // echo '</pre>';
        ?>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-6">
                <table style="width: 100%;">
                    <tr>
                        <td><b>Name :</b> <?php echo $payment->Employee_Name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Phone No. : </b><?php echo $payment->Employee_ContactNo; ?></td>
                    </tr>
                    <tr>
                        <td><b>Designation : </b><?php echo $payment->Designation_Name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Department : </b><?php echo $payment->Department_Name; ?></td>
                    </tr>

                </table>
            </div>
            <div class="col-xs-6 text-right">
                <table style="width: 100%;">
                    <tr>
                        <td><b>Payment Date: </b><?php echo $payment->payment_date; ?> </td>
                    </tr>
                    <tr>
                        <td><b>Payment By: </b><?php echo $payment->payment_by; ?> </td>
                    </tr>
                    <tr>
                        <td><b>Payment For: </b><?php echo $payment->payment_for; ?> </td>
                    </tr>

                </table>
            </div>
        </div>

        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12">
                <table class="border" cellspacing="0" cellpadding="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Sl No</th>
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Month</th>
                            <!-- <th style="font-size: 14px; font-weight: 700;text-align:center;">Payment By</th> -->
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Payment</th>
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Deduct</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">01</td>
                            <td style="text-align: center;"><?php echo $payment->month_name; ?></td>
                            <!-- <td><?php echo $payment->payment_by; ?></td> -->
                            <td style="text-align: right;"><?php echo $payment->payment_amount; ?></td>
                            <td style="text-align: right;"><?php echo $payment->deduction_amount; ?></td>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12">
                <h6 style=" font-size: 12px; font-weight: 600;">Paid (In Word): <?php echo convertNumberToWord($payment->payment_amount); ?></h6>
            </div>
            <div class="col-xs-12">
                <div style="float:right;text-decoration: overline; padding-top:30px;">
                    <strong>Autorizied signature</strong>
                </div>
            </div>
        </div>

    </div>
</div>

<?php

function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '', trim($num));
    if (!$num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array(
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array(
        '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ($tens < 20) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    $inword = implode(' ', $words) . "Taka Only";
    return strtoupper($inword);
}

?>

<script>
    let printIcon = document.querySelector('#printIcon');
    printIcon.addEventListener('click', () => {
        event.preventDefault();
        print();
    })
    async function print() {
        let reportContent = `
            <div class="container">
                ${document.querySelector('#reportContent').innerHTML}
            </div>
        `;

        var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
        reportWindow.document.write(`
            <?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
        `);

        reportWindow.document.head.innerHTML += `<link href="<?php echo base_url() ?>assets/css/prints.css" rel="stylesheet" />`;
        reportWindow.document.body.innerHTML += reportContent;

        reportWindow.focus();
        await new Promise(resolve => setTimeout(resolve, 1000));
        reportWindow.print();
        reportWindow.close();
    }
</script>