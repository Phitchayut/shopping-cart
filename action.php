<?php
session_start();
require_once 'config.php';
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $pqty = 1;

    $stmt = $conn->prepare("SELECT product_code FROM cart WHERE product_code = ?");
    $stmt->bind_param("s", $pcode);
    $stmt->execute();
    $res = $stmt->get_result();
    $r = $res->fetch_assoc();
    $code = $r['product_code'] ?? '';

    if (!$code) {
        $query = $conn->prepare("INSERT INTO cart(product_name,product_price,product_image,qty,total_price,product_code) VALUES (?,?,?,?,?,?)");
        $query->bind_param("sssiss", $pname, $pprice, $pimage, $pqty, $pprice, $pcode);
        $query->execute();
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            เพิ่มสินค้าลงตระกร้าสำเร็จ!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                คุณเพิ่มสินค้าลงตระกร้าแล้ว!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
}

if(isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item'){
    $stmt = $conn->prepare("SELECT * FROM cart");
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows;

    echo $row;
}

if(isset($_GET['remove'])){
    $id = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
    $stmt->bind_param("s",$id);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'ลบสินค้าออกจากตระกร้าเรียบร้อย!';
    header('location: cart.php');
}

if (isset($_GET['clear'])) {
    $stmt = $conn->prepare('DELETE FROM cart');
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'ล้างสินค้าออกจากตระกร้าเรียบร้อย!';
    header('location:cart.php');
  }

?>