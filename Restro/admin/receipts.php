<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');
?>

<body>
    <!-- Thanh điều hướng bên -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
    <!-- Nội dung chính -->
    <div class="main-content">
        <!-- Thanh điều hướng trên -->
        <?php
        require_once('partials/_topnav.php');
        ?>
        <!-- Tiêu đề -->
        <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>
        <!-- Nội dung trang -->
        <div class="container-fluid mt--8">
            <!-- Bảng -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Đơn hàng đã thanh toán
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Mã đơn</th>
                                        <th scope="col">Khách hàng</th>
                                        <th class="text-success" scope="col">Sản phẩm</th>
                                        <th scope="col">Đơn giá</th>
                                        <th class="text-success" scope="col">Số lượng</th>
                                        <th scope="col">Tổng tiền</th>
                                        <th class="text-success" scope="col">Ngày</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM  rpos_orders WHERE order_status = 'Paid' ORDER BY `rpos_orders`.`created_at` DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        $total = ($order->prod_price * $order->prod_qty);
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td class="text-success"><?php echo $order->prod_name; ?></td>
                                            <td><?php echo number_format($order->prod_price, 0, ',', '.'); ?>₫</td>
                                            <td class="text-success"><?php echo $order->prod_qty; ?></td>
                                            <td><?php echo number_format($total, 0, ',', '.'); ?>₫</td>
                                            <td><?php echo date('d/m/Y g:i', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <a target="_blank" href="print_receipt.php?order_code=<?php echo $order->order_code; ?>">
                                                    <button class="btn btn-sm btn-primary">
                                                        <i class="fas fa-print"></i>
                                                        In hóa đơn
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php
            require_once('partials/_footer.php');
            ?>
        </div>
    </div>
    <!-- Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>