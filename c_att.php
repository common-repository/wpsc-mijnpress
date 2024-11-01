<?php
class wpsc_att_c extends mijnpress_plugin_framework {
	function get_src($id)
	{
		return wp_get_attachment_url( $id );
	}
}
?>