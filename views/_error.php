<?php
    $this->title = 'Something went wrong!';
?>

<h3 class="text--center"><?php echo $exception->getCode() ?> - <?php echo $exception->getMessage() ?></h3>
<div class="text--center">
    <a href="/"><- Back to home page</a>
</div>

