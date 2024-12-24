<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

if (isset($_POST['pay'])) {
  // Kiểm tra các giá trị nhập
  if (empty($_POST["pay_code"]) || empty($_POST["pay_amt"]) || empty($_POST['pay_method'])) {
    $err = "Không được để trống các trường.";
  } else {
    $pay_code = $_POST['pay_code'];
    $order_code = $_GET['order_code'];
    $customer_id = $_GET['customer_id'];
    $pay_amt  = $_POST['pay_amt'];
    $pay_method = $_POST['pay_method'];
    $pay_id = $_POST['pay_id'];

    $order_status = $_GET['order_status'];

    // Thêm thông tin thanh toán vào cơ sở dữ liệu
    $postQuery = "INSERT INTO rpos_payments (pay_id, pay_code, order_code, customer_id, pay_amt, pay_method) VALUES(?,?,?,?,?,?)";
    $upQry = "UPDATE rpos_orders SET order_status =? WHERE order_code =?";

    $postStmt = $mysqli->prepare($postQuery);
    $upStmt = $mysqli->prepare($upQry);

    $rc = $postStmt->bind_param('ssssss', $pay_id, $pay_code, $order_code, $customer_id, $pay_amt, $pay_method);
    $rc = $upStmt->bind_param('ss', $order_status, $order_code);

    $postStmt->execute();
    $upStmt->execute();

    if ($upStmt && $postStmt) {
      $success = "Thanh toán thành công!" && header("refresh:1; url=receipts.php");
    } else {
      $err = "Vui lòng thử lại sau.";
    }
  }
}
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
    $order_code = $_GET['order_code'];
    $ret = "SELECT * FROM rpos_orders WHERE order_code ='$order_code'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($order = $res->fetch_object()) {
      $total = ($order->prod_price * $order->prod_qty);
    ?>

      <!-- Tiêu đề -->
      <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
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
                <h3>Vui lòng điền đầy đủ thông tin</h3>
              </div>
              <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Mã thanh toán</label>
                      <input type="text" name="pay_id" readonly value="<?php echo $payid; ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Mã giao dịch</label>
                      <input type="text" name="pay_code" value="<?php echo $mpesaCode; ?>" class="form-control">
                    </div>
                  </div>
                  <hr>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Số tiền (₫)</label>
                      <input type="text" name="pay_amt" readonly value="<?php echo number_format($total, 0, ',', '.'); ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Phương thức thanh toán</label>
                      <select class="form-control" name="pay_method">
                        <option selected>Tiền mặt</option>
                        <option>Paypal</option>
                      </select>
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="pay" value="Thanh toán" class="btn btn-success">
                    </div>
                  </div>
                </form>
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
    }
?>
</body>

</html>