<?php 
function wex_is_username( $username ){
	if( isset( $username ) ){
		$usname = sanitize_text_fields($username);
		if(preg_match('/^[a-zćęłńóśźżA-Z0-9_\s\.\/]{3,20}+$/', $usname )) {
			if( strlen( $username ) < 20 ){
				return $username;
			}
		}
	}
}
function wex_is_phone( $numeric ){
	if( isset( $numeric ) ){
		$is_valid = sanitize_text_fields( $numeric );
		if( strpos( $is_valid,'09' ) !== false) {
			if( preg_match("/^09[0-9]{9}$/", $is_valid )) {
				if( is_numeric($is_valid) ){
					return true;
				}
			}
		}
	}
}
function wex_is_email( $email ){
	if( isset( $email ) ){
		if ( strlen( $email ) > 6 ) {
			if ( strpos( $email, '@', 1 ) == true ) {
				@list( $local, $domain ) = explode( '@', $email, 2 );
				if( isset( $local , $domain ) ){
					if ( preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
						return true;
					}
				}
			}
		}
	}
}
function sanitize_text_fields( $str, $keep_newlines = false ) {
    if ( is_object( $str ) || is_array( $str ) ) {
        return '';
    }
 
    $str = (string) $str;
 
    $filtered = wex_check_invalid_utf8( $str );
 
    if ( strpos( $filtered, '<' ) !== false ) {
        $filtered = wex_pre_kses_less_than( $filtered );
        // This will strip extra whitespace for us.
        $filtered = wex_strip_all_tags( $filtered, false );
 
        // Use HTML entities in a special case to make sure no later
        // newline stripping stage could lead to a functional tag.
        $filtered = str_replace( "<\n", "&lt;\n", $filtered );
    }
 
    if ( ! $keep_newlines ) {
        $filtered = preg_replace( '/[\r\n\t ]+/', ' ', $filtered );
    }
    $filtered = trim( $filtered );
 
    $found = false;
    while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) {
        $filtered = str_replace( $match[0], '', $filtered );
        $found    = true;
    }
 
    if ( $found ) {
        // Strip out the whitespace that may now exist after removing the octets.
        $filtered = trim( preg_replace( '/ +/', ' ', $filtered ) );
    }
 
    return $filtered;
}

function wex_check_invalid_utf8( $string, $strip = false ) {
    $string = (string) $string;
 
    if ( 0 === strlen( $string ) ) {
        return '';
    }
 
    // Store the site charset as a static to avoid multiple calls to get_option().
    static $is_utf8 = null;
    if ( ! isset( $is_utf8 ) ) {
        $is_utf8 = in_array( get_option( 'blog_charset' ), array( 'utf8', 'utf-8', 'UTF8', 'UTF-8' ), true );
    }
    if ( ! $is_utf8 ) {
        return $string;
    }
 
    // Check for support for utf8 in the installed PCRE library once and store the result in a static.
    static $utf8_pcre = null;
    if ( ! isset( $utf8_pcre ) ) {
        // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
        $utf8_pcre = @preg_match( '/^./u', 'a' );
    }
    // We can't demand utf8 in the PCRE installation, so just return the string in those cases.
    if ( ! $utf8_pcre ) {
        return $string;
    }
 
    // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- preg_match fails when it encounters invalid UTF8 in $string.
    if ( 1 === @preg_match( '/^./us', $string ) ) {
        return $string;
    }
 
    // Attempt to strip the bad chars if requested (not recommended).
    if ( $strip && function_exists( 'iconv' ) ) {
        return iconv( 'utf-8', 'utf-8', $string );
    }
 
    return '';
}

function wex_pre_kses_less_than( $text ) {
    return preg_replace_callback( '%<[^>]*?((?=<)|>|$)%', 'wex_pre_kses_less_than_callback', $text );
}

function wex_strip_all_tags( $string, $remove_breaks = false ) {
    $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
    $string = strip_tags( $string );
 
    if ( $remove_breaks ) {
        $string = preg_replace( '/[\r\n\t ]+/', ' ', $string );
    }
 
    return trim( $string );
}

function wex_pre_kses_less_than_callback( $matches ) {

    if ( false === strpos( $matches[0], '>' ) ) {
        return esc_html( $matches[0] );
    }
    return $matches[0];
}
