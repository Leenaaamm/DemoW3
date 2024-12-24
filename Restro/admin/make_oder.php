<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

if (isset($_POST['make'])) {
  if (empty($_POST["order_code"]) || empty($_POST["customer_name"]) || empty($_GET['prod_price'])) {
    $err = "Vui lòng điền đầy đủ thông tin.";
  } else {
    $order_id = $_POST['order_id'];
    $order_code = $_POST['order_code'];
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $prod_id = intval($_GET['prod_id']);
    $prod_name = $_GET['prod_name'];
    $prod_price = $_GET['prod_price'];
    $prod_qty = $_POST['prod_qty'];

    $postQuery = "INSERT INTO rpos_orders (prod_qty, order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price) VALUES(?,?,?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    $rc = $postStmt->bind_param('ssssssss', $prod_qty, $order_id, $order_code, $customer_id, $customer_name, $prod_id, $prod_name, $prod_price);
    $postStmt->execute();

    if ($postStmt) {
      $success = "Đặt hàng thành công!" && header("refresh:1; url=payments.php");
    } else {
      $err = "Đã xảy ra lỗi, vui lòng thử lại sau.";
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
    ?>
    <!-- Phần đầu -->
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body"></div>
      </div>
    </div>
    <!-- Nội dung trang -->
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Vui lòng điền đầy đủ thông tin</h3>
            </div>
            <div class="card-body">
              <?php if (isset($err)) {
                echo "<div class='alert alert-danger'>$err</div>";
              } ?>
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-4">
                    <label>Tên khách hàng</label>
                    <select class="form-control" name="customer_name" id="custName" onChange="getCustomer(this.value)">
                      <option value="">Chọn tên khách hàng</option>
                      <?php
                      $ret = "SELECT * FROM rpos_customers";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      while ($cust = $res->fetch_object()) {
                        echo "<option>" . htmlspecialchars($cust->customer_name) . "</option>";
                      }
                      ?>
                    </select>
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderid); ?>" class="form-control">
                  </div>
                  <div class="col-md-4">
                    <label>ID khách hàng</label>
                    <input type="text" name="customer_id" readonly id="customerID" class="form-control">
                  </div>
                  <div class="col-md-4">
                    <label>Mã đơn hàng</label>
                    <input type="text" name="order_code" value="<?php echo htmlspecialchars($alpha . '-' . $beta); ?>" class="form-control">
                  </div>
                </div>
                <hr>
                <?php
                $prod_id = intval($_GET['prod_id']);
                $ret = "SELECT * FROM rpos_products WHERE prod_id = ?";
                $stmt = $mysqli->prepare($ret);
                $stmt->bind_param('i', $prod_id);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($prod = $res->fetch_object()) {
                ?>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Giá sản phẩm (₫)</label>
                      <input type="text" readonly name="prod_price" value="<?php echo number_format($prod->prod_price, 0, ',', '.'); ?> ₫" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Số lượng sản phẩm</label>
                      <input type="number" name="prod_qty" class="form-control" value="1" min="1">
                    </div>
                  </div>
                <?php } ?>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="make" value="Đặt hàng" class="btn btn-success">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Chân trang -->
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