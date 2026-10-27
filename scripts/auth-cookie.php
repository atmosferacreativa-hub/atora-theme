<?php
/**
 * Cabecera Cookie de sesión para un usuario (CI). Uso:
 *   SHOT_USER=estudiante_e2e wp eval-file scripts/auth-cookie.php
 */

$user = get_user_by( 'login', (string) getenv( 'SHOT_USER' ) );
if ( ! $user ) {
	fwrite( STDERR, "Usuario no encontrado\n" );
	exit( 1 );
}

$expires = time() + HOUR_IN_SECONDS;
$token   = WP_Session_Tokens::get_instance( $user->ID )->create( $expires );

echo LOGGED_IN_COOKIE . '=' . rawurlencode( wp_generate_auth_cookie( $user->ID, $expires, 'logged_in', $token ) );
