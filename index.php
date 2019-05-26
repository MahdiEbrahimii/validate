<?php 
/*	
	decription : validate Phone , Email , string
*/
	function is_mobile( $phone ){

		// check Send Value
		if (isset($phone) ){
			
			// This function converts all characters that are applicable to HTML entity.  
			$phones = htmlspecialchars( strip_tags( trim  ( $phone , " \t\n\r\0\x0B-" ) ) );
		
				// IF FIND 09 IN STRING
				if( preg_match("/^09[0-9]{9}$/", $phones )) {
					
					// IF FOUND 09
					
					if( strpos( $phones,'09' ) !== false) {		
					
						// IF NUMBERIC 
						if( is_numeric($phones) ){
							
							return $phones;
							
						}
						
					}
				
				}
			}
	}
	
		function is_email( $email ) {
		
		//check empty value
		if (isset($email) ){
	 
			// check string lenght > 6 char
			if ( strlen( $email ) > 6 ) {

				// check found @ in string 
				if ( strpos( $email, '@', 1 ) == true ) {
							
					// after explode width delimiter @ return to list ( variable ) 		
					list( $local, $domain ) = explode( '@', $email, 2 );
				 
					// check valid string
					if (  preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
						
						// check domain after @ 
						if ( !preg_match( '/\.{2,}/', $domain ) ) {
							
							// remove extera char in domain
							if ( trim( $domain, " \t\n\r\0\x0B." ) === $domain ) {

								// explode . and return to $sub
								$subs = explode( '.', $domain );
								
								// check domain 
								if ( 2 <= count( $subs ) ) {

									foreach ( $subs as $sub ) {

										if ( trim( $sub, " \t\n\r\0\x0B-" ) === $sub ) {

											
										}

										if (  preg_match( '/^[a-z0-9-]+$/i', $sub ) ) {

											
										}
									}
									
									return $email;
									
								}

							}
							
						}

					}
					
				}
				
			}
		}
	}
	
	function is_str( $str ){
		
		if ( isset( $str ) ) {
			
			if ( strlen ( $str > '0'  ) ){

				// REMOVE EXTERA SPACE BEGIN STRING 
				$string =  trim( $str );
				
				// Remove HTML tags and all characters with ASCII value > 127
				$string = filter_var($string, FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
				
				// REMOVE HTML TAG IN INPUT STRING 
				$string = strip_tags($string);
				
				// Convert the predefined characters "<" (less than) and ">" (greater than) to HTML entities: 
				$string = htmlspecialchars($string);
				
				// Convert some characters to HTML entities: 
				$string = htmlentities($string);
				
				// AFTER CHECK STRING AND VALIDATE RETURN TO FUNCTION 
				return $string;

			} 
			
		}
		
	}
	
	function is_number( $numb ){
		
		// Check not empty value.
		if(isset( $numb ) ){
			
			// check numberic number 
			if ( is_numeric( $numb ) ) {
				
				// remove other string and return 
				return is_str( $numb );
				
			}
			
		}
		
	}
