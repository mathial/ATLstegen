<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('id', array (
  'security' => 
  array (
    'An authentication exception occurred.' => 'Terjadi sebuah pengecualian otentikasi.',
    'Authentication credentials could not be found.' => 'Kredensial otentikasi tidak bisa ditemukan.',
    'Authentication request could not be processed due to a system problem.' => 'Permintaan otentikasi tidak bisa diproses karena masalah sistem.',
    'Invalid credentials.' => 'Kredensial salah.',
    'Cookie has already been used by someone else.' => 'Cookie sudah digunakan oleh orang lain.',
    'Not privileged to request the resource.' => 'Tidak berhak untuk meminta sumber daya.',
    'Invalid CSRF token.' => 'Token CSRF salah.',
    'No authentication provider found to support the authentication token.' => 'Tidak ditemukan penyedia otentikasi untuk mendukung token otentikasi.',
    'No session available, it either timed out or cookies are not enabled.' => 'Tidak ada sesi yang tersedia, mungkin waktu sudah habis atau cookie tidak diaktifkan',
    'No token could be found.' => 'Tidak ada token yang bisa ditemukan.',
    'Username could not be found.' => 'Username tidak bisa ditemukan.',
    'Account has expired.' => 'Akun telah berakhir.',
    'Credentials have expired.' => 'Kredensial telah berakhir.',
    'Account is disabled.' => 'Akun dinonaktifkan.',
    'Account is locked.' => 'Akun terkunci.',
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
