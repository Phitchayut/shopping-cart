<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php require_once "./components/navbar.php" ?>
    <div class="container">
        <div class="mt-3" id="message"></div>
        <h3 class="text-center mt-3">สินค้าทั้งหมด</h3>
        <div class="row mt-2 pb-3">
            <?php
            include 'config.php';
            $stmt = $conn->prepare("SELECT * FROM product");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                    <div class="card-deck">
                        <div class="card p-2 mb-2">
                            <img src="<?= $row['product_image'] ?>" class="card-img-top" height="250" alt="">
                            <div class="card-body p-1">
                                <h4 class="card-title text-center text-info"><?= $row['product_name'] ?></h4>
                                <h5 class="card-title text-center text-danger">
                                    <?= number_format($row['product_price'], 2) ?>.-
                                </h5>
                            </div>
                            <div class="card-footer p-1">
                                <form action="" method="POST" class="form-submit">
                                    <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                                    <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                                    <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                                    <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
                                    <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                                    <button class="text-white btn btn-info w-100 addItemBtn"><i class="fas fa-cart-plus"></i> Add to cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
    <script>
        $(document).ready(function() {
            $(".addItemBtn").click(function(e) {
                e.preventDefault();
                var $form = $(this).closest(".form-submit");
                var pid = $form.find(".pid").val();
                var pname = $form.find(".pname").val();
                var pprice = $form.find(".pprice").val();
                var pimage = $form.find(".pimage").val();
                var pcode = $form.find(".pcode").val();
                $.ajax({
                    url: 'action.php',
                    method: 'POST',
                    data: {
                        pid: pid,
                        pname: pname,
                        pprice: pprice,
                        pimage: pimage,
                        pcode: pcode
                    },
                    success: function(response) {
                        $("#message").html(response);
                        window.scrollTo(0, 0);
                        load_cart_item_number();
                    }
                });
            })

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