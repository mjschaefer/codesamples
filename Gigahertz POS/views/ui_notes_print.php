<?php
    session_start();
?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8" />
        <title>Print Note</title>

        <?php include('../includes/database.inc.php'); ?>
        <?php include('../includes/globals.inc.php'); ?>
        <script src="../js/jquery-1.6.min.js"></script>

        <script>
            $(document).ready(function() {
               window.print();
               window.onfocus = function() { window.close(); }
            });
        </script>

        <link rel="stylesheet" href="../css/notes_print.css" />
    </head>
    
    <body>
        <?php
            extract($_GET);

            mysql_connect($server, $login, $pass) or die("Unable to Connect");
            mysql_select_db($db) or die("Unable to select database");
            
            $query = "SELECT * FROM notes WHERE id='$note_id'";
            $dropoff_data = mysql_query($query) or die(mysql_error());
            $dropoff_array = mysql_fetch_assoc($dropoff_data);

            $customer_id = $dropoff_array['customer_id'];

            $query = "SELECT * FROM customers WHERE id='$customer_id'";
            $customer_data = mysql_query($query) or die(mysql_error());
            $customer_array = mysql_fetch_assoc($customer_data);
                            
            $query = "SELECT * FROM notes_added WHERE note_id='$note_id'";
            $notes_added_data = mysql_query($query) or die(mysql_error());
        ?>


        <div id="header">
            <div id="header_wrapper">
                <img src="../img/icon_warning_32x32.png" style="float: left;" /><h3 id="warning">Make sure you print these in portrait.</h3>
                <a href="javascript:window.print()" id="button_print">Print</a>
            </div>
        </div>

        <div id="wrapper_main">
            <div id="info_company">
                <img src="../img/logo_company_invoice.png" /><br />   
                <?php
                    if($location_based_invoices) {
                        echo $_SESSION['location_street'] . '<br />';
                        echo $_SESSION['location_city'] . ', ' . $_SESSION['location_state'] . ' ' . $_SESSION['location_zip'] . '<br />';
                        echo $_SESSION['location_phone'] . '<br />';
                        echo $_SESSION['location_email'] . '<br />';
                        echo $_SESSION['location_website'] . '<br />';                    
                    } else {
                        echo $company_street . '<br />';
                        echo $company_city . ', ' . $company_state . ' ' . $company_zip . '<br />';
                        echo $company_phone . '<br />';
                        echo $company_email . '<br />';
                        echo $company_website . '<br />';                      
                    }
                ?>
                <?php 
                    echo '<br />';
                    echo '<h2>Note ID: '.$dropoff_array['id'].'</h2>';
                ?>
            </div>
            <?php
                //echo the customer info
                echo '<div id="top">';
                    echo '<span class="header time">' . date('m-d-y \a\t g:i a', $dropoff_array['date_entered']) . '</span>';
                    echo '<hr>';
                    echo '<span class="header">INFORMATION</span>';

                    echo '<div class="info" id="client_information">';
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
                    //echo the computer information

                    echo '<div class="info" id="computer_information">';
                        echo 'Brand: ' . $dropoff_array['pc_brand'] . '<br />';
                        echo 'Model: ' . $dropoff_array['pc_model'] . '<br />';
                        echo 'Serial: ' . $dropoff_array['pc_serial'] . '<br />';
                        echo 'OS: ' . $dropoff_array['pc_os'] . '<br />';
                        echo 'Type: ' . $dropoff_array['pc_type'] . '<br />';
                        echo 'Password: ' . $dropoff_array['pc_password'] . '<br />';
                    echo '</div>';
                echo '</div>';

                echo '<hr>';
            ?>
            <?php //Headers for the Items ?>
            <table style="width: 100%;">
                <?php
                    echo '<tr><td class="header">NOTES</td></tr>';
                    echo '<tr><td class="item_title">Original Problems</td></tr>';
                    echo '<tr><td>' . stripslashes(html_entity_decode($dropoff_array['problems'])) . '</td></tr>'; 
                         
                    echo '<tr><td><hr class="dashed"></td></tr>';  

                    echo '<tr><td class="item_title">Items Left</td></tr>';
                    echo '<tr><td>' . stripslashes(html_entity_decode($dropoff_array['items_left'])) . '</td></tr>';
                    
                    echo '<tr><td><hr class="dashed"></td></tr>';

                    while($notes_added_array = mysql_fetch_assoc($notes_added_data)) {
                        echo '<tr>';                    
                            echo '<td class="item_title">' . $notes_added_array['entered_by'] . ' @ ' . date('m-d-y \a\t g:i a', $notes_added_array['date']) . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>' . stripslashes(html_entity_decode($notes_added_array['content'])) . '</td>';
                        echo '</tr>';
                    
                    echo '<tr><td><hr class="dashed"></td></tr>';
                    }
                ?>        
            </table>
            <div id="cust_agreement" class="agreement">
                <?php echo stripslashes(html_entity_decode($agreement_dropoff)); ?>
                
                <div id="signature">
                    <h2 id="date">Date</h2><h2 id="sig">Signature</h2>
                </div>
            </div>
        </div>
    </body>
</html>
