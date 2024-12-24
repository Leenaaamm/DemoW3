<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Cập nhật thông tin nhân viên
if (isset($_POST['UpdateStaff'])) {
  // Kiểm tra nếu có giá trị trống
  if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
    $err = "Không chấp nhận giá trị trống.";
  } else {
    // Lấy thông tin từ biểu mẫu
    $staff_number = $_POST['staff_number'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $staff_password = password_hash($_POST['staff_password'], PASSWORD_DEFAULT); // Sử dụng password_hash để bảo mật mật khẩu
    $update = $_GET['update'];

    // Cập nhật thông tin nhân viên trong cơ sở dữ liệu
    $postQuery = "UPDATE rpos_staff SET staff_number =?, staff_name =?, staff_email =?, staff_password =? WHERE staff_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    // Bind các tham số
    $rc = $postStmt->bind_param('ssssi', $staff_number, $staff_name, $staff_email, $staff_password, $update);

    if ($postStmt->execute()) {
      $success = "Nhân viên đã được cập nhật thành công." && header("refresh:1; url=hrm.php");
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
    $ret = "SELECT * FROM rpos_staff WHERE staff_id = '$update'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($staff = $res->fetch_object()) {
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
                      <label>Số nhân viên</label>
                      <input type="text" name="staff_number" class="form-control" value="<?php echo $staff->staff_number; ?>">
                    </div>
                    <div class="col-md-6">
                      <label>Tên nhân viên</label>
                      <input type="text" name="staff_name" class="form-control" value="<?php echo $staff->staff_name; ?>">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Email nhân viên</label>
                      <input type="email" name="staff_email" class="form-control" value="<?php echo $staff->staff_email; ?>">
                    </div>
                    <div class="col-md-6">
                      <label>Mật khẩu nhân viên</label>
                      <input type="password" name="staff_password" class="form-control" value="">
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="UpdateStaff" value="Cập nhật nhân viên" class="btn btn-success">
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