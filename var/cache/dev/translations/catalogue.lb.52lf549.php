<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('lb', array (
  'security' => 
  array (
    'An authentication exception occurred.' => 'Bei der Authentifikatioun ass e Feeler opgetrueden.',
    'Authentication credentials could not be found.' => 'Et konnte keng Zouganksdate fonnt ginn.',
    'Authentication request could not be processed due to a system problem.' => 'D\'Ufro fir eng Authentifikatioun konnt wéinst engem Problem vum System net beaarbecht ginn.',
    'Invalid credentials.' => 'Ongëlteg Zouganksdaten.',
    'Cookie has already been used by someone else.' => 'De Cookie gouf scho vun engem anere benotzt.',
    'Not privileged to request the resource.' => 'Keng Rechter fir d\'Ressource unzefroen.',
    'Invalid CSRF token.' => 'Ongëltegen CSRF-Token.',
    'No authentication provider found to support the authentication token.' => 'Et gouf keen Authentifizéierungs-Provider fonnt deen den Authentifizéierungs-Token ënnerstëtzt.',
    'No session available, it either timed out or cookies are not enabled.' => 'Keng Sëtzung disponibel. Entweder ass se ofgelaf oder Cookies sinn net aktivéiert.',
    'No token could be found.' => 'Et konnt keen Token fonnt ginn.',
    'Username could not be found.' => 'De Benotzernumm konnt net fonnt ginn.',
    'Account has expired.' => 'Den Account ass ofgelaf.',
    'Credentials have expired.' => 'D\'Zouganksdate sinn ofgelaf.',
    'Account is disabled.' => 'De Konto ass deaktivéiert.',
    'Account is locked.' => 'De Konto ass gespaart.',
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
