<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('ua', array (
  'security' => 
  array (
    'An authentication exception occurred.' => 'Помилка автентифікації.',
    'Authentication credentials could not be found.' => 'Автентифікаційні дані не знайдено.',
    'Authentication request could not be processed due to a system problem.' => 'Запит на автентифікацію не може бути опрацьовано у зв’язку з проблемою в системі.',
    'Invalid credentials.' => 'Невірні автентифікаційні дані.',
    'Cookie has already been used by someone else.' => 'Хтось інший вже використав цей сookie.',
    'Not privileged to request the resource.' => 'Відсутні права на запит цього ресурсу.',
    'Invalid CSRF token.' => 'Невірний токен CSRF.',
    'No authentication provider found to support the authentication token.' => 'Не знайдено провайдера автентифікації, що підтримує токен автентифікаціії.',
    'No session available, it either timed out or cookies are not enabled.' => 'Сесія недоступна, її час вийшов, або cookies вимкнено.',
    'No token could be found.' => 'Токен не знайдено.',
    'Username could not be found.' => 'Ім’я користувача не знайдено.',
    'Account has expired.' => 'Термін дії облікового запису вичерпано.',
    'Credentials have expired.' => 'Термін дії автентифікаційних даних вичерпано.',
    'Account is disabled.' => 'Обліковий запис відключено.',
    'Account is locked.' => 'Обліковий запис заблоковано.',
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
