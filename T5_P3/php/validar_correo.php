<?php

/**
 * Validación de correo (estructura, TLD y registros MX).
 * Basado en la lógica de correoValido / dominioTieneRegistrosDNS de ValidarContacto.
 */

const P3_TLD_PERMITIDOS = [
    'com',
    'net',
    'org',
    'edu',
    'gov',
    'mil',
    'int',
    'io',
    'co',
    'ai',
    'app',
    'dev',
    'info',
    'biz',
    'pa',
    'mx',
    'es',
    'ar',
    'cl',
    'pe',
    'ec',
    'uy',
    'bo',
    'py',
    'us',
    'uk',
    'ca',
    'br',
    'de',
    'fr',
    'it',
    'jp',
    'au',
    'nz',
];

const P3_DOMINIOS_CONOCIDOS = [
    'gmail.com',
    'yahoo.com',
    'hotmail.com',
    'outlook.com',
    'icloud.com',
    'live.com',
    'msn.com',
    'me.com',
    'protonmail.com',
    'proton.me',
    'zoho.com',
    'aol.com',
    'ymail.com',
    'mail.com',
];

function p3_dominio_tiene_registros_dns(string $dominio): bool
{
    if (in_array(strtolower($dominio), P3_DOMINIOS_CONOCIDOS, true)) {
        return true;
    }

    $intentos = 2;
    for ($i = 0; $i < $intentos; $i++) {
        $mx = @dns_get_record($dominio, DNS_MX);
        if (!empty($mx)) {
            return true;
        }
        if ($i < $intentos - 1) {
            usleep(300000);
        }
    }

    return false;
}

function p3_error_correo(string $correo): ?string
{
    if ($correo === '') {
        return 'El correo electrónico es obligatorio.';
    }
    if (strlen($correo) > 254) {
        return 'El correo no puede superar 254 caracteres.';
    }
    if (preg_match('/\s/', $correo)) {
        return 'El correo no puede contener espacios.';
    }
    if (str_contains($correo, '..')) {
        return 'El correo no puede contener puntos consecutivos.';
    }
    if (!preg_match('/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/', $correo)) {
        return 'El formato del correo electrónico no es válido.';
    }

    [$local, $dominio] = explode('@', $correo, 2);
    if ($local === '' || $dominio === '') {
        return 'El formato del correo electrónico no es válido.';
    }

    if (preg_match('/^[^a-zA-Z0-9]/', $local) || preg_match('/[^a-zA-Z0-9]$/', $local)) {
        return 'La parte antes de @ del correo no es válida.';
    }
    if (
        str_starts_with($dominio, '.') || str_ends_with($dominio, '.') ||
        str_starts_with($dominio, '-') || str_ends_with($dominio, '-')
    ) {
        return 'El dominio del correo no es válido.';
    }

    $partesDominio = explode('.', $dominio);
    foreach ($partesDominio as $parte) {
        if ($parte === '' || str_starts_with($parte, '-') || str_ends_with($parte, '-')) {
            return 'El dominio del correo no es válido.';
        }
    }

    $tld = strtolower((string) end($partesDominio));
    if (!in_array($tld, P3_TLD_PERMITIDOS, true)) {
        return "La extensión del dominio (.$tld) no está permitida.";
    }

    if (!p3_dominio_tiene_registros_dns($dominio)) {
        return "El dominio @$dominio no puede recibir correos electrónicos.";
    }

    return null;
}

function p3_correo_valido(string $correo): bool
{
    return p3_error_correo($correo) === null;
}
