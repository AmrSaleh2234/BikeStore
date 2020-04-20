<?php require APPROOT.DS.'views'.DS.'inc'.DS.'header.php';?>
    <div class="container">
    <h1 class="text-center mt-4 mb-5" style="color: #333">Buy Page</h1>
    <div class="row mt-5">
<?php

$item = $data['item'];
if ($item[0]->quantity != 0) {

    echo '<div class="col-4 mb-4 mx-auto">';
    echo '<div class="card custom-card">';
    echo '<span class="price">$'.$item[0]->price.'</span>';
    echo '<img src="'.URLROOT.'/img/uploads/'. $item[0]->photoName .'" class="card-img-top" alt="..." style="width: 100%;height: 200px;">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">'.$item[0]->name.'</h5>';
    echo '<hr>';
    echo '<span style="color: red">Left: ' . $item[0]->quantity . '</span>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    ?>

    <form style="width: 40%" class="mx-auto mt-5" method="POST" action="<?php echo URLROOT ?>/shopping/buy/<?php echo  $item[0]->productId ?>">
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min='1' value='1' max="<?php echo $item[0]->quantity  ?>">
        </div>
        <div class="form-group">
            <label for="inputState">Select Payment VISA Number</label>
            <select id="inputState" class="form-control <?php echo (!empty($data['payment_err']))? 'is-invalid' : '' ?>" name="payment">
                <option selected value="0">Choose...</option>
                <?php
                foreach ($data['payments'] as $paymant) {
                    echo '<option value="' . $paymant->visaNumber . ' [$' . $paymant->money . ']">' . $paymant->visaNumber . ' [$' . $paymant->money . ']</option>';
                }
                ?>
            </select>
            <div class="invalid-feedback"><?php echo $data['payment_err'] ?></div>
        </div>
        <button type="submit" class="btn btn-primary customBtn" style="border-radius: 5px">Buy</button>
    </form>
    </div>
    </div>
    <?php
} else {
    die('the products not exist any more');
}
?>
<?php  require APPROOT.DS.'views'.DS.'inc'.DS.'footer.php';?>