<?php

// Google Captcha
$_ENV['GOOGLE_CAPTCHA_SITE_KEY'] = "6LdNijMdAAAAANctKNaUespZ-uPdjxFAVXUr_VYo";
$_ENV['GOOGLE_CAPTCHA_SECRET_KEY'] = "6LdNijMdAAAAAJ-h6RJEJSK3UGcQNzF9gzxMpepC";
$_ENV['GOOGLE_CAPTCHA_ENDPOINT'] = "https://www.google.com/recaptcha/api/siteverify?secret={$_ENV['GOOGLE_CAPTCHA_SECRET_KEY']}" . "&response=";

?>