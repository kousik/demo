<?php
/**
 * Created by PhpStorm.
 * User: kousik
 * Date: 29/5/18
 * Time: 11:22 PM
 */

$paymentinfo = get_payments_info();
$dashboard_container = new FED_Routes( $_REQUEST );
$menu                = $dashboard_container->setDashboardMenuQuery();

//print_r($menu);
?>


<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading"><?php if(is_super_admin()):?>All Payments<?php else:?>My Payments<?php endif;?></div>
    <div class="panel-body">
        <!-- Table -->
        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <?php if(is_super_admin()):?>
                <th>User</th>
                <?php endif;?>
                <th>Payment Type</th>
                <th>Transaction ID</th>
                <th>Payment Correlation ID</th>
                <th>Status</th>
                <th>Time</th>
                <th>Note</th>
            </tr>
            </thead>
            <?php $payment = $paymentinfo['payment'];
            if ( 0 < $payment->total() ):
            $i = 0;
            while ( $payment->fetch() ):
                $raw_data = $payment->data();
                $raw_data = $raw_data[$i];
            ?>
            <tr class="row-<?=encrypt_decrypt('encrypt', $payment->display('id'))?>">
                <td><?=$payment->display('id');?></td>
                <td><?=$payment->display('name');?></td>
                <?php if(is_super_admin()):?>
                <td><?=$payment->display('author');?></td>
                <?php endif;?>
                <td><?=$payment->display('type');?></td>
                <td><?=$payment->display('transaction_id');?></td>
                <td><?=$payment->display('correlation_id');?></td>
                <td><?=$payment->display('status');?></td>
                <td><?=$payment->display('payments_made');?></td>

                <td><?=$payment->display('payments_note');?></td>
            </tr>
            <?php
            $i++;
            endwhile;
            else:
                ?>
            <tr>
                <td colspan="9">
                    Sorry! You have no payment information!
                </td>
            </tr>
            <?php
            endif;
            ?>

        </table>
        </div>
    </div>
</div>



