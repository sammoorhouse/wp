<?php

abstract class WPF_Integrations_Base {

	public $slug;

	public $name;

	public function __construct() {

		$this->init();

		if ( isset( $this->slug ) ) {
			wp_fusion()->integrations->{$this->slug} = $this;
		}

	}

	/**
	 * Gets things started
	 *
	 * @access  public
	 * @since   1.0
	 * @return  void
	 */

	abstract protected function init();

	/**
	 * Map meta fields collected at registration / profile update to internal fields
	 *
	 * @access  public
	 * @since   3.0
	 * @return  array Meta Fields
	 */

	protected function map_meta_fields( $meta_fields, $field_map ) {

		foreach ( $field_map as $key => $field ) {

			if ( ! empty( $meta_fields[ $key ] ) ) {
				$meta_fields[ $field ] = $meta_fields[ $key ];
			}
		}

		return $meta_fields;

	}

	/**
	 * Handles signups from plugins which support guest registrations
	 *
	 * @access  public
	 * @since   3.26.6
	 * @return  mixed Contact ID
	 */

	public function guest_registration( $email_address, $update_data ) {

		wp_fusion()->logger->handle( 'info', 0, $this->name . ' guest registration:', array( 'meta_array' => $update_data ) );

		$contact_id = wp_fusion()->crm->get_contact_id( $email_address );

		if ( false == $contact_id ) {

			$contact_id = wp_fusion()->crm->add_contact( $update_data );

		} else {

			wp_fusion()->crm->update_contact( $contact_id, $update_data );

		}

		if ( is_wp_error( $contact_id ) ) {

			wp_fusion()->logger->handle( $contact_id->get_error_code(), 0, 'Error adding contact: ' . $contact_id->get_error_message() );
			return false;

		}

		return $contact_id;

	}

}
