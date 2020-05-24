<?php
if (!function_exists('generate_client_id')) {
    /**
     * Generates a random client id
     *
     * @return string
     */
    function generate_client_id(): string {
        return sprintf(
            '%s-%s.clients.%s',
            value(function () {
                $random = '';

                for ($i = 0; $i < 12; $i++) {
                    $random .= random_int(0, 9);
                }

                return $random;
            }),
            str_random(32),
            env('APP_HOST', 'oauth.example.com')
        );
    }
}

if (!function_exists('generate_client_secret')) {
    /**
     * Generates a random client secret
     *
     * @return string
     */
    function generate_client_secret(): string {
        return str_random(24);
    }
}
