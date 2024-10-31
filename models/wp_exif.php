<?php

if ( ! class_exists( 'MPRS_Exif' ) ) {

	class MPRS_Exif {

		public static function initialize() {
			add_filter('attachment_fields_to_edit', array('MPRS_Exif', 'displayFields'), 10, 2);
			add_filter('attachment_fields_to_save', array('MPRS_Exif', 'saveFields'), 11, 2);
			add_action('admin_post_prs_convert_image',   array('MPRS_Exif', 'convertImage'));
		}

		public static function convertImage()
		{
			$p      = $_POST;
			$id     = sanitize_text_field($p['id']);
			$remove = sanitize_text_field($p['remove']);

			$image = null;
			$ext   = null;
			$mime  = get_post_mime_type($id);
			if ($mime == 'image/png') {
				$image = imagecreatefrompng(get_attached_file($id));
				$ext = '.png';
			} elseif ($mime == 'image/gif') {
				$image = imagecreatefromgif(get_attached_file($id));
				$ext = '.gif';
			} else {
				PRS_Init::json('error', 'Select image has a format that is not supported!');
			}

			$outputFile = str_replace($ext, '.jpg', get_attached_file($id));

			imagejpeg($image, $outputFile, 100);
			imagedestroy($image);

			// Check the type of file. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype(basename($outputFile), null);

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = array(
				'guid' => $wp_upload_dir['url'] . '/' . basename($outputFile),
				'post_mime_type' => $filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($outputFile)),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			// Insert the attachment.
			$attach_id = wp_insert_attachment($attachment, $outputFile);

			// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
			require_once(ABSPATH . 'wp-admin/includes/image.php');

			// Generate the metadata for the attachment, and update the database record.
			$attach_data = wp_generate_attachment_metadata($attach_id, $outputFile);
			wp_update_attachment_metadata($attach_id, $attach_data);

			if ($remove == 'true') {
				wp_delete_attachment($id, true);
			}

			PRS_Init::json('success', 'The image has been successfully converted to JPEG! Refreshing this page ...');
			wp_die();
		}

		public static function saveFields($post, $attachment) {
			// Load PEL Exif Class
			$aClass = PRS_PATH.'/ext/Pel/autoload.php';
			if (file_exists($aClass)) {
				require_once ( $aClass );
			} else {
				return $post;
			}

			if (
				$post['post_mime_type'] != 'image/jpeg' &&
				$post['post_mime_type'] != 'image/jpg'
			) {
				$post['errors']['field_name']['errors'][] = __('EXIF is supported only by JPEG/JPG images.');
				return $post;
			}
			if (isset($attachment['prs_exif_desc']))
				update_post_meta($post['ID'], 'prs_exif_desc', $attachment['prs_exif_desc']);

			if (isset($attachment['prs_exif_latitude']))
				update_post_meta($post['ID'], 'prs_exif_latitude', $attachment['prs_exif_latitude']);

			if (isset($attachment['prs_exif_longitude']))
				update_post_meta($post['ID'], 'prs_exif_longitude', $attachment['prs_exif_longitude']);

			if ((isset($attachment['prs_exif_longitude']) && !empty($attachment['prs_exif_longitude']))
			    && (isset($attachment['prs_exif_latitude']) && !empty($attachment['prs_exif_latitude']))
			    && (isset($attachment['prs_exif_desc']) && !empty($attachment['prs_exif_desc']))
			) {

				// Save Address -.-"
				update_post_meta($post['ID'], 'ps_exif_address', ps_exif_address($_POST['ps_exif_address']));

				MPRS_Exif::writeEXIF(get_attached_file($post['ID']), $attachment['prs_exif_latitude'], $attachment['prs_exif_longitude'], $attachment['prs_exif_desc']);

				$dir = str_replace(basename(get_attached_file($post['ID'])), '', get_attached_file($post['ID']));
				$meta = wp_get_attachment_metadata($post['ID']);

				foreach ($meta['sizes'] as $f) {
					MPRS_Exif::writeEXIF($dir . $f['file'], $attachment['prs_exif_latitude'], $attachment['prs_exif_longitude'], $attachment['prs_exif_desc']);
				}
			}

			return $post;
		}

		public static function displayFields($form_fields, $post) {
			$mime_type = $post->post_mime_type;

			global $pagenow;
			if ( $pagenow == 'post.php' ) {
				return $form_fields;
			}

			$form_fields['ps_settings'] = array(
				'tr' => '<div class="ps_exif_marker">♦ Project Supremacy EXIF ♦</div>'
			);
			if (
				$mime_type == 'image/png' ||
				$mime_type == 'image/gif'
			) {
				$form_fields['error'] = array(
					'label' => '',
					'input' => 'html',
					'html' => '<div class="ps_exif_error">EXIF can only be applied to JPEG images.</div> <button class="button button-primary ps_convert_jpg" data-id="'.$post->ID.'">Convert to JPEG</button>'
				);
			} else {

				// Get Google Maps API
				$apis = get_option('ps_api_settings');
				$maps_canvas = '<div class="ps_map_input_group"><button type="button" class="button button-primary ps_search_maps">Search</button><input id="attachments-'.$post->ID.'-map" type="text" placeholder="Arizona" class="ps_search_input" name="ps_exif_address" value="'.get_post_meta($post->ID, 'ps_exif_address', true).'"/></div><div id="ps_map_canvas"></div>';
				if (!isset($apis['googlemaps_api'])) {
					$maps_canvas = '<span class="ps_exif_error">Your Google Maps API key is not set. Please set it at the Panel.</span>';
				}
				if (empty($apis['googlemaps_api'])) {
					$maps_canvas = '<span class="ps_exif_error">Your Google Maps API key is not set. Please set it at the Panel.</span>';
				}

				$form_fields['prs_exif_desc'] = array(
					'label' => 'Small Description',
					'input' => 'textarea',
					'value' => stripslashes_deep(get_post_meta($post->ID, 'prs_exif_desc', true))
				);
				$form_fields['prs_exif_latitude'] = array(
					'label' => 'Latitude',
					'input' => 'text',
					'value' => get_post_meta($post->ID, 'prs_exif_latitude', true),
				);
				$form_fields['prs_exif_longitude'] = array(
					'label' => 'Longitude',
					'input' => 'text',
					'value' => get_post_meta($post->ID, 'prs_exif_longitude', true)
				);
				$form_fields['map'] = array(
					'label' => 'Address',
					'input' => 'html',
					'html' => '<div class="ps_map_container">'.$maps_canvas.'</div>'
				);
				$form_fields['prs_exif_button'] = array(
					'label' => '',
					'input' => 'html',
					'html' => '<button type="button" class="button button-primary ps_exif_save_button">Save</button>'
				);
			}



			return $form_fields;
		}


		public static function readEXIF( $filename ) {
			if ( ! file_exists( $filename ) ) {
				return array( 'lat' => '', 'lon' => '', 'desc' => '' );
			}
			$jpeg = new PelJpeg( $filename );
			if ( ! is_object( $jpeg ) ) {
				return array( 'lat' => '', 'lon' => '', 'desc' => '' );
			}
			$exif = $jpeg->getExif();
			if ( ! is_object( $exif ) ) {
				return array( 'lat' => '', 'lon' => '', 'desc' => '' );
			}
			$tiff = $exif->getTiff();
			if ( ! is_object( $tiff ) ) {
				return array( 'lat' => '', 'lon' => '', 'desc' => '' );
			}
			try {
				$ifd0 = $tiff->getIfd();
				return self::getGPS( $ifd0 );
			} catch ( Exception $error ) {
				return array( 'lat' => '', 'lon' => '', 'desc' => '' );
			}
		}

		public static function writeEXIF( $filename, $lat, $lon, $desc ) {
			if ( ! file_exists( $filename ) ) {
				return false;
			}
			try {
				$pelJpeg = new PelJpeg( $filename );
			} catch ( PelDataWindowOffsetException $pel ) {
				if ( function_exists( 'imagecreatefromjpeg' ) ) {
					/* Attempt to open */
					$img = @imagecreatefromjpeg( $filename );
					if ( ! $img ) {
						return false;
					} else {
						imagejpeg( $img, $filename, 100 );
						imagedestroy( $img );
						$pelJpeg = new PelJpeg( $filename );
					}
				} else {
					return false;
				}
			}

			$pelExif = $pelJpeg->getExif();
			if ( $pelExif == null ) {
				$pelExif = new PelExif();
				$pelJpeg->setExif( $pelExif );
			}
			$pelTiff = $pelExif->getTiff();
			if ( $pelTiff == null ) {
				$pelTiff = new PelTiff();
				$pelExif->setTiff( $pelTiff );
			}

			$pelIfd0 = $pelTiff->getIfd();
			if ( $pelIfd0 == null ) {
				$pelIfd0 = new PelIfd( PelIfd::IFD0 );
				$pelTiff->setIfd( $pelIfd0 );
			}

			$pelIfd0->addEntry( new PelEntryAscii(
				PelTag::IMAGE_DESCRIPTION, $desc ) );

			$pelSubIfdGps = new PelIfd( PelIfd::GPS );
			$pelIfd0->addSubIfd( $pelSubIfdGps );

			self::setGPS( $pelSubIfdGps, $lat, $lon );

			$pelJpeg->saveFile( $filename );

			return true;
		}

		private static function setGPS( $pelSubIfdGps, $latitudeDegreeDecimal, $longitudeDegreeDecimal ) {
			$latitudeDegreeMinuteSecond  = self::degreeDecimalToDegreeMinuteSecond( abs( $latitudeDegreeDecimal ) );
			$longitudeDegreeMinuteSecond = self::degreeDecimalToDegreeMinuteSecond( abs( $longitudeDegreeDecimal ) );

			$longitudeRef = ( $longitudeDegreeDecimal >= 0 ) ? 'E' : 'W';
			$latitudeRef  = ( $latitudeDegreeDecimal >= 0 ) ? 'N' : 'S';

			$pelSubIfdGps->addEntry( new PelEntryAscii(
				PelTag::GPS_LATITUDE_REF, $latitudeRef ) );
			$pelSubIfdGps->addEntry( new PelEntryRational(
				PelTag::GPS_LATITUDE,
				array( $latitudeDegreeMinuteSecond['degree'], 1 ),
				array( $latitudeDegreeMinuteSecond['minute'], 1 ),
				array( round( $latitudeDegreeMinuteSecond['second'] * 1000 ), 1000 ) ) );
			$pelSubIfdGps->addEntry( new PelEntryAscii(
				PelTag::GPS_LONGITUDE_REF, $longitudeRef ) );
			$pelSubIfdGps->addEntry( new PelEntryRational(
				PelTag::GPS_LONGITUDE,
				array( $longitudeDegreeMinuteSecond['degree'], 1 ),
				array( $longitudeDegreeMinuteSecond['minute'], 1 ),
				array( round( $longitudeDegreeMinuteSecond['second'] * 1000 ), 1000 ) ) );
		}

		private static function getGPS( $ifd0 ) {
			$desc = $ifd0->getEntry( PelTag::IMAGE_DESCRIPTION );
			$gps  = $ifd0->getSubIfd( PelIfd::GPS );
			if ( ! $gps ) {
				$ar = array(
					'lat' => '',
					'lon' => ''
				);

				if ( ! $desc ) {
					$ar['desc'] = '';
				} else {
					$ar['desc'] = $desc->getValue();
				}

				return $ar;

			} else {
				$lat = $gps->getEntry( PelTag::GPS_LATITUDE );
				$lon = $gps->getEntry( PelTag::GPS_LONGITUDE );

				$lat_ref = $gps->getEntry( PelTag::GPS_LATITUDE_REF );
				$lon_ref = $gps->getEntry( PelTag::GPS_LONGITUDE_REF );

				if ( ! is_object( $lat ) || ! is_object( $lon ) || ! is_object( $lat_ref ) || ! is_object( $lon_ref ) ) {
					return array( 'lat' => 0, 'lon' => 0, 'desc' => '' );
				}

				$lat_ref = $lat_ref->getValue();
				$lon_ref = $lon_ref->getValue();

				$lat = $lat->getValue();
				$lon = $lon->getValue();
				$lat = self::DMStoDEC( $lat[0][0], $lat[1][0], ( $lat[2][0] / 1000 ) );
				$lon = self::DMStoDEC( $lon[0][0], $lon[1][0], ( $lon[2][0] / 1000 ) );


				if ( $lon_ref == 'W' ) {
					$lon = - $lon;
				}
				if ( $lat_ref == 'S' ) {
					$lat = - $lat;
				}

				if ( ! $desc ) {
					$desc = '';
				} else {
					$desc = $desc->getValue();
				}

				return array( 'lat' => $lat, 'lon' => $lon, 'desc' => $desc );
			}
		}

		private static function degreeDecimalToDegreeMinuteSecond( $degreeDecimal ) {
			$degree    = floor( $degreeDecimal );
			$remainder = $degreeDecimal - $degree;
			$minute    = floor( $remainder * 60 );
			$remainder = ( $remainder * 60 ) - $minute;
			$second    = $remainder * 60;

			return array( 'degree' => $degree, 'minute' => $minute, 'second' => $second );
		}

		private static function DMStoDEC( $deg, $min, $sec ) {
			return $deg + $min / 60 + $sec / 3600;
		}


	}

}