<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Cập nhật thông tin khách hàng
if (isset($_POST['updateCustomer'])) {
  // Kiểm tra giá trị trống
  if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email']) || empty($_POST['customer_password'])) {
    $err = "Không chấp nhận giá trị trống.";
  } else {
    $customer_name = $_POST['customer_name'];
    $customer_phoneno = $_POST['customer_phoneno'];
    $customer_email = $_POST['customer_email'];
    $customer_password = password_hash($_POST['customer_password'], PASSWORD_DEFAULT); // Sử dụng password_hash để bảo mật mật khẩu
    $update = $_GET['update'];

    // Cập nhật thông tin khách hàng trong cơ sở dữ liệu
    $postQuery = "UPDATE rpos_customers SET customer_name =?, customer_phoneno =?, customer_email =?, customer_password =? WHERE customer_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    $postStmt->bind_param('sssss', $customer_name, $customer_phoneno, $customer_email, $customer_password, $update);

    if ($postStmt->execute()) {
      $success = "Khách hàng đã được cập nhật thành công." && header("refresh:1; url=customes.php");
    } else {
      $err = "Lỗi! Vui lòng thử lại sau.";
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
    $update = $_GET['update'];
    $ret = "SELECT * FROM rpos_customers WHERE customer_id = '$update'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($cust = $res->fetch_object()) {
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
        <!-- Form cập nhật -->
        <div class="row">
          <div class="col">
            <div class="card shadow">
              <div class="card-header border-0">
                <h3>Vui lòng điền đầy đủ thông tin</h3>
              </div>
              <div class="card-body">
                <form method="POST">
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Tên khách hàng</label>
                      <input type="text" name="customer_name" value="<?php echo $cust->customer_name; ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Số điện thoại khách hàng</label>
                      <input type="text" name="customer_phoneno" value="<?php echo $cust->customer_phoneno; ?>" class="form-control">
                    </div>
                  </div>
                  <hr>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Email khách hàng</label>
                      <input type="email" name="customer_email" value="<?php echo $cust->customer_email; ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Mật khẩu khách hàng</label>
                      <input type="password" name="customer_password" class="form-control">
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="updateCustomer" value="Cập nhật khách hàng" class="btn btn-success">
                    </div>
                  </div>
                </form>
                <?php if (isset($err)) {
                  echo "<div class='alert alert-danger'>$err</div>";
                } ?>
                <?php if (isset($success)) {
                  echo "<div class='alert alert-success'>$success</div>";
                } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
  </div>

  <!-- Footer -->
  <?php
  require_once('partials/_footer.php');
  ?>

  <!-- Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>