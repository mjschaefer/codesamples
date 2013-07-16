<?php
    session_start();
?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8" />
        <title>Print Invoice</title>

        <script src="../js/jquery-1.6.min.js"></script>

        <script>
            $(document).ready(function() {
                window.print();
                window.onfocus = function() { window.close(); }
            });
        </script>

        <link rel="stylesheet" href="../css/invoices_print.css" />
    </head>
<body>
    <?php include('../includes/database.inc.php'); ?>
    <?php include('../includes/globals.inc.php'); ?>
    <?php
        extract($_GET);

        mysql_connect($server, $login, $pass) or die("Unable to Connect");
        mysql_select_db($db) or die("Unable to select database");
        
        $query = "SELECT * FROM invoices WHERE id='$invNum'";
        $invoice_data = mysql_query($query) or die(mysql_error());
        $invoice_array = mysql_fetch_assoc($invoice_data);

        $customer_id = $invoice_array['customer_id'];

        $query = "SELECT * FROM customers WHERE id='$customer_id'";
        $customer_data = mysql_query($query) or die(mysql_error());
        $customer_array = mysql_fetch_assoc($customer_data);
                        
        $invoice_id = $invoice_array['id'];
        $query = "SELECT * FROM items_sold WHERE invoice_id='$invoice_id'";
        $items_data = mysql_query($query) or die(mysql_error());
    ?>


    <div id="header">
        <div id="header_wrapper">
            <img src="../img/icon_warning_32x32.png" style="float: left;" /><h3 id="warning">Make sure you print these in portrait.</h3>
            <a href="javascript:window.print()" id="button_print">Print</a>
        </div>
    </div>

    <div id="wrapper_main">
        <h1>
            <?php
                if($invoice_array['paid']) {
                    echo 'RECEIPT - <font color="red">PAID IN FULL</font>';
                } else {
                    echo 'INVOICE';
                }
            ?>
        </h1>
        <div id="info_company">
            <img src="../img/logo_company_invoice.png" /><br />
            <?php
                if($location_based_invoices) {
                    echo $company_name . '<br />';
                    echo $_SESSION['location_street'] . '<br />';
                    echo $_SESSION['location_city'] . ', ' . $_SESSION['location_state'] . ' ' . $_SESSION['location_zip'] . '<br />';
                    echo $_SESSION['location_phone'] . '<br />';
                    echo $_SESSION['location_email'] . '<br />';
                    echo $_SESSION['location_website'] . '<br />';                    
                } else {
                    echo $company_name . '<br />';
                    echo $company_street . '<br />';
                    echo $company_city . ', ' . $company_state . ' ' . $company_zip . '<br />';
                    echo $company_phone . '<br />';
                    echo $company_email . '<br />';
                    echo $company_website . '<br />';                      
                }
            ?>
        </div>
        <?php
        //echo the customer info
            echo '<div id="client_information">';
                echo '<h2>Invoice No: '.$invoice_array['id'].'</h2>';
                if($invoice_array['paid']) {
                    //echo '<h2>Created On: ' . date('m-d-y \a\t g:i a', $invoice_array['date']) . '</h2>';
                    echo '<h2>Paid On: ' . date('m-d-y \a\t g:i a', $invoice_array['paid_date']) . '</h2>';                
                } else {
                    echo '<h2>Created On: ' . date('m-d-y \a\t g:i a', $invoice_array['date']) . '</h2>';
                }
                
                echo '<br /><br /><br />';
                echo $customer_array['name'] . '<br />';
                if($customer_array['business'] != '') {
                    echo $customer_array['business'].'<br />';
                }
                if($customer_array['street'] != '') {
                    echo $customer_array['street'].'<br />';
                    echo $customer_array['city'].', '.$customer_array['state'].' '.$customer_array['zip'].'<br />';
                }
                if($customer_array['phone_primary'] != '') {
                    echo $customer_array['phone_primary'].'<br />';
                }
                if($customer_array['phone_secondary'] != '') {
                    echo $customer_array['phone_secondary'].'<br />';
                }
            echo '</div>';
        ?>
        <?php //Headers for the Items ?>
        <table style="width: 100%;">
            <tr id="table_header">
                <th class="item_title">Item</th>
                <th>Price Ea.</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
            <?php        
                while($items_array = mysql_fetch_assoc($items_data)) {
                    echo '<tr>';                    
                        echo '<td class="item_title">' . $items_array['title'] . '</td>';
                        echo '<td>$ ' . $items_array['price'] . '</td>';
                        echo '<td>' . $items_array['quantity'] . '</td>';
                        echo '<td>$ ' . ($items_array['price'] * $items_array['quantity']) . '</td>';
                    echo '</tr>';

                    echo '<tr class="description">';
                        echo '<td>' . $items_array['description'] . '</td>';
                        echo '<td>&nbsp;</td>';
                        echo '<td>&nbsp;</td>';
                        echo '<td>&nbsp;</td>';
                    echo '</tr>';
                }
                mysql_close(); 
            ?>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

            <tr class="totals">
                <td class="agreement" rowspan="100" colspan="2">
                    <?php
                        if(!$invoice_array['paid']) {
                            echo stripslashes(html_entity_decode($agreement_unpaid));
                    ?>
                            <div id="signature">
                                <h2>Signature</h2>
                            </div>
                    <?php                    
                        } else {
                            echo stripslashes(html_entity_decode($agreement_paid));
                        }
                    ?>
                </td>

                <td>Subtotal</td>
                <td>$ <?php echo $invoice_array['subtotal']; ?></td>
            </tr>

            <tr class="totals"><td>Discount</td><td>$ <?php echo $invoice_array['discounts']; ?></td></tr>
            <tr class="totals">
                <td>Tax</td>
                <td>
                    <?php
                        if($invoice_array['tax_exempt'] == '1') {
                            echo 'EXEMPT';
                        } else {
                            echo '$ ' . $invoice_array['tax'];
                        }
                    ?>
                </td>
            </tr>
            <tr class="totals"><td>Total</td><td>$ <?php echo $invoice_array['total']; ?></td></tr>

            <tr class="totals"><td>Payments</td><td style="color: red;">($<?php echo ($invoice_array['total'] - $invoice_array['balance']) ?>)</td></tr>
            <tr class="totals"><td>Balance</td><td>$ <?php echo $invoice_array['balance']; ?></td></tr>
            
        </table>
        <?php
            if($invoice_array['paid']) {
                if($print_invoice_referrals) {
                    echo '<div id="referral">';
                        echo '<div id="referral_text_wrapper">';
                            echo stripslashes(html_entity_decode($invoice_referral_text));
                        echo '</div>';

                        echo '<div id="barcode_wrapper">';
                            //echo '<span class="barcode">' . $customer_array['id'] . '</span>';
                            //echo '<span class="barcode_under">' . $customer_array['id'] . '</span>';
                            echo '<img src="../img/qr_facebook.png" />';
                        echo '</div>';
                    echo '</div>';
                }
            }
        ?>
    </div>
</body>
</html>
