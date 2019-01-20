<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('fa', array (
  'security' => 
  array (
    'An authentication exception occurred.' => 'خطایی هنگام تعیین اعتبار اتفاق افتاد.',
    'Authentication credentials could not be found.' => 'شرایط تعیین اعتبار پیدا نشد.',
    'Authentication request could not be processed due to a system problem.' => 'درخواست تعیین اعتبار به دلیل مشکل سیستم قابل بررسی نیست.',
    'Invalid credentials.' => 'شرایط نامعتبر.',
    'Cookie has already been used by someone else.' => 'کوکی قبلا برای شخص دیگری استفاده شده است.',
    'Not privileged to request the resource.' => 'دسترسی لازم برای درخواست این منبع را ندارید.',
    'Invalid CSRF token.' => 'توکن CSRF معتبر نیست.',
    'No authentication provider found to support the authentication token.' => 'هیچ ارایه کننده تعیین اعتباری برای ساپورت توکن تعیین اعتبار پیدا نشد.',
    'No session available, it either timed out or cookies are not enabled.' => 'جلسه‌ای در دسترس نیست. این میتواند یا به دلیل پایان یافتن زمان باشد یا اینکه کوکی ها فعال نیستند.',
    'No token could be found.' => 'هیچ توکنی پیدا نشد.',
    'Username could not be found.' => 'نام ‌کاربری پیدا نشد.',
    'Account has expired.' => 'حساب کاربری منقضی شده است.',
    'Credentials have expired.' => 'پارامترهای تعیین اعتبار منقضی شده‌اند.',
    'Account is disabled.' => 'حساب کاربری غیرفعال است.',
    'Account is locked.' => 'حساب کاربری قفل شده است.',
  ),
));

$catalogueEn = new MessageCatalogue('en', array (
  'security' => 
  array (
    'An authentication exception occurred.' => 'An authentication exception occurred.',
    'Authentication credentials could not be found.' => 'Authentication credentials could not be found.',
    'Authentication request could not be processed due to a system problem.' => 'Authentication request could not be processed due to a system problem.',
    'Invalid credentials.' => 'Invalid credentials.',
    'Cookie has already been used by someone else.' => 'Cookie has already been used by someone else.',
    'Not privileged to request the resource.' => 'Not privileged to request the resource.',
    'Invalid CSRF token.' => 'Invalid CSRF token.',
    'No authentication provider found to support the authentication token.' => 'No authentication provider found to support the authentication token.',
    'No session available, it either timed out or cookies are not enabled.' => 'No session available, it either timed out or cookies are not enabled.',
    'No token could be found.' => 'No token could be found.',
    'Username could not be found.' => 'Username could not be found.',
    'Account has expired.' => 'Account has expired.',
    'Credentials have expired.' => 'Credentials have expired.',
    'Account is disabled.' => 'Account is disabled.',
    'Account is locked.' => 'Account is locked.',
  ),
));
$catalogue->addFallbackCatalogue($catalogueEn);

return $catalogue;
