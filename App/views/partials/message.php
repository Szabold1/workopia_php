<?php

use Framework\Session;
?>

<!-- Success Message -->
<?php $successMessage = Session::getFlashMessage('success'); ?>
<?php if ($successMessage) : ?>
    <div class="message bg-green-100 p-3 my-3 rounded">
        <?= $successMessage ?>
    </div>
<?php endif; ?>

<!-- Error Message -->
<?php $errorMessage = Session::getFlashMessage('error'); ?>
<?php if ($errorMessage) : ?>
    <div class="message bg-red-100 p-3 my-3 rounded">
        <?= $errorMessage ?>
    </div>
<?php endif; ?>