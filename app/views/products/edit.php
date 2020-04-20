<?php require APPROOT . '/views/inc/header.php' ?>
<h1 class="text-center mt-4 mb-5" style="color: #333">
    <a style="font-size: 12px" class="btn btn-danger confirm"
       href="<?php echo URLROOT ?>/products/delete/<?php echo $data['id'] ?>"><i class="fas fa-trash-alt"></i> Delete</a>
    Edit Product
</h1>
<form style="width: 40%" class="mx-auto" method="POST"
      action="<?php echo URLROOT ?>/products/edit/<?php echo $data['id'] ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name Of The Product</label>
        <input type="text" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : '' ?>" name="name"
               id="name" value="<?php echo $data['name'] ?>">
        <div class="invalid-feedback"><?php echo $data['name_err'] ?></div>
    </div>
    <div class="form-group">
        <label for="feature">Product Features</label>
        <textarea class="form-control <?php echo (!empty($data['features_err'])) ? 'is-invalid' : '' ?>" id="feature"
                  rows="8" name="feature"><?php echo $data['features'] ?></textarea>
        <div class="invalid-feedback"><?php echo $data['features_err'] ?></div>
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : '' ?>" id="price"
               name="price" value="<?php echo $data['price'] ?>">
        <div class="invalid-feedback"><?php echo $data['price_err'] ?></div>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control <?php echo (!empty($data['quantity_err'])) ? 'is-invalid' : '' ?>"
               id="quantity" name="quantity" value="<?php echo $data['quantity'] ?>">
        <div class="invalid-feedback"><?php echo $data['quantity_err'] ?></div>
    </div>
    <div class="custom-file mt-2 mb-3">
        <input type="file" class="custom-file-input" name="photo" id="photo">
        <label class="custom-file-label" for="photo">Choose file</label>
        <small id="emailHelp" class="form-text text-muted">If you want to change the image select new one otherwise leave it
            empty</small>
    </div>
    <div class="custom-control custom-radio mt-3">
        <input type="radio" id="rent" name="renting" class="custom-control-input"
               value="1" <?php echo ($data['rentStatus'] == 1) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="rent">The Product is for renting</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" id="norent" name="renting" class="custom-control-input"
               value="0" <?php echo ($data['rentStatus'] == 0) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="norent">The Product isn't for renting</label>
    </div>


    <div class="custom-control custom-radio mt-3">
        <input type="radio" id="bike" name="isBike" class="custom-control-input"
               value="1" <?php echo ($data['isBike'] == 1) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="bike">The Product is Bike</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" id="accessories" name="isBike" class="custom-control-input"
               value="0" <?php echo ($data['isBike'] == 0) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="accessories">The Product is Accessories</label>
    </div>


    <div class="custom-control custom-radio mt-3">
        <input type="radio" id="new" name="isNew" class="custom-control-input"
               value="1" <?php echo ($data['isNew'] == 1) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="new">The Product is New</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" id="used" name="isNew" class="custom-control-input"
               value="0" <?php echo ($data['isNew'] == 0) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="used">The Product is Used</label>
    </div>
    <button type="submit" class="btn btn-primary customBtn btn-block mt-3" style="border-radius: 5px;padding: 8px 0;">Save
        Changes
    </button>
</form>

<?php require APPROOT . '/views/inc/footer.php' ?>
<script>
    $(function () {
        $('#photo').on('change', function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        $('.confirm').click(function () {
            return confirm('Are you sure??');
        });
    });

</script>