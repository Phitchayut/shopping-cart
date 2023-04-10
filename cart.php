<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตระกร้าสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php require_once "./components/navbar.php" ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div style="display:<?php if (isset($_SESSION['showAlert'])) {
                                        echo $_SESSION['showAlert'];
                                    } else {
                                        echo 'none';
                                    }
                                    unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <?php if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                    }
                    unset($_SESSION['showAlert']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <td colspan="7">
                                    <h1 class="text-center text-info m-0">Product in your cart!</h1>
                                </td>
                            </tr>
                            <tr>
                                <th>รหัสสินค้า</th>
                                <th>รูป</th>
                                <th>ชื่อสินค้า</th>
                                <th>ราคา</th>
                                <th>จำนวน</th>
                                <th>ราคารวม</th>
                                <th>
                                    <a href="action.php?clear=all" style="text-decoration: none;" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการล้างตระกร้าสินค้าหรือไม่ ?')"><i class="fas fa-trash"></i> ล้าง</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once 'config.php';
                            $stmt = $conn->prepare("SELECT * FROM cart");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $grand_total = 0;
                            while ($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <img src="<?= $row['product_image'] ?>" width="50" alt="">
                                    </td>
                                    <td><?= $row['product_name'] ?></td>
                                    <td><?= number_format($row['product_price'], 2) ?></td>
                                    <td>
                                        <input type="number" class="form-control itemQty" style="width: 75px;" value="<?= $row['qty'] ?>">
                                    </td>
                                    <td><?= number_format($row['total_price'], 2) ?></td>
                                    <td>
                                        <a href="action.php?remove=<?= $row['id'] ?>" class="text-danger lead" onclick="return confirm('คุณต้องการลบสินค้าออกจากตระกร้าหรือไม่ ?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php $grand_total += $row['total_price'] ?>
                            <?php } ?>
                            <tr>
                                <td colspan="3">
                                    <a href="index.php" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> เลือกสินค้าต่อ</a>
                                </td>
                                <td colspan="2"><b>ราคารวม</b></td>
                                <td><b><?= number_format($grand_total, 2) ?></b></td>
                                <td>
                                    <a href="checkout.php" class="btn btn-info btn-sm <?= ($grand_total > 1) ? '' : 'disabled'; ?>"><i class="fas fa-credit-card"></i> ชำระเงิน</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
    <script>
        $(document).ready(function() {

            load_cart_item_number();

            function load_cart_item_number() {
                $.ajax({
                    url: 'action.php',
                    method: 'get',
                    data: {
                        cartItem: "cart_item"
                    },
                    success: function(response) {
                        $("#cart-item").html(response);
                    }
                })
            }


        })
    </script>
</body>

</html>