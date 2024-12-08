<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>

<section>
    <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $statusCode ?> Error</div>
        <p class="text-center text-2xl mb-4">
            <?= $message ?>
        </p>
        <a href="/" class="block text-xl text-center hover:underline text-blue-500">
            Back To Home
        </a>
    </div>
</section>

<?php loadPartial('footer'); ?>