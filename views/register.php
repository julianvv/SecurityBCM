<?php
$registerModel = new \models\User();

$form = \form\Form::begin('', 'POST');
echo $form->field($registerModel, "firstName");
echo $form->field($registerModel, "lastName");
echo $form->field($registerModel, "email");
echo $form->field($registerModel, "password")->passwordField();
echo $form->field($registerModel, "confirmPassword")->passwordField();
echo "<button type='submit' class='btn btn-primary mr-auto'>Registreren!</button>";
echo $form::end();