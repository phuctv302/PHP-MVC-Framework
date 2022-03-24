<?php
/** @var $this \core\View */
/** @var $model \models\Contactform */

use core\form\TextareaField;

$this->title = 'Contact';
?>

<h1>Contact</h1>

<?php $form = \core\form\Form::begin('', 'post') ?>
    <?php echo $form->field($model, 'subject') ?>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'body') ?>
    <?php echo new TextareaField($model, 'body') ?>

    <button type="submit" class="btn btn-primary">Submit</button>
<?php \core\form\Form::end(); ?>

