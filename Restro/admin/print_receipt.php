<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bắt đầu phát triển với một giao diện quản lý bán hàng cho Bootstrap 4.">
    <meta name="author" content="MartDevelopers Inc">
    <title>Hệ thống bán hàng nhà hàng</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/img/icons/site.webmanifest">
    <link rel="mask-icon" href="assets/img/icons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="assets/css/bootstrap.css" rel="stylesheet" id="bootstrap-css">
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery.js"></script>
    <style>
        body {
            margin-top: 20px;
        }
    </style>
</head>

<?php
$order_code = $_GET['order_code'];
$ret = "SELECT * FROM  rpos_orders WHERE order_code = '$order_code'";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($order = $res->fetch_object()) {
    $total = ($order->prod_price * $order->prod_qty);
?>

    <body>
        <div class="container">
            <div class="row">
                <div id="Receipt" class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <address>
                                <strong>Nhà Hàng ABC</strong>
                                <br>
                                số 123, đường ABC, Hà Đông, Hà Nội
                                <br>

                                <br>
                                (+84) 28 1234 5678
                            </address>

                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                            <p>
                                <em>Ngày: <?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></em>
                            </p>
                            <p>
                                <em class="text-success">Mã hóa đơn: <?php echo $order->order_code; ?></em>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center">
                            <h2>Hóa đơn</h2>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mặt hàng</th>
                                    <th>Số lượng</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-9"><em> <?php echo $order->prod_name; ?> </em></td>
                                    <td class="col-md-1" style="text-align: center"> <?php echo $order->prod_qty; ?></td>
                                    <td class="col-md-1 text-center"><?php echo number_format($order->prod_price, 2); ?> VNĐ</td>
                                    <td class="col-md-1 text-center"><?php echo number_format($total, 2); ?> VNĐ</td>
                                </tr>
                                <tr>
                                    <td>   </td>
                                    <td>   </td>
                                    <td class="text-center">
                                        <p>
                                            <strong>Tổng tạm tính: </strong>
                                        </p>
                                        <p>
                                            <strong>Thuế: </strong>
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <p>
                                            <strong><?php echo number_format($total, 2); ?> VNĐ</strong>
                                        </p>
                                        <p>
                                            <strong>14%</strong>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>   </td>
                                    <td>   </td>
                                    <td class="text-center">
                                        <h6><strong>Tổng cộng: </strong></h6>
                                    </td>
                                    <td class="text-center text-danger">
                                        <h4><strong><?php echo number_format($total * 1.14, 2); ?> VNĐ</strong></h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                    <button id="print" onclick="printContent('Receipt');" class="btn btn-success btn-lg text-justify btn-block">
                        In hóa đơn <span class="fas fa-print"></span>
                    </button>
                </div>
            </div>
        </div>
    </body>

</html>

<script>
    function printContent(el) {
        var restorepage = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage);
    }
</script>
<?php } ?>