/* global wp, progressally_gutenberg */

var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	shortcode_attributes = {
		shortcode_type: {
			type: 'string',
			default: ''
		},
		current_post_id: {
			type: 'integer',
			default: 0
		}
	},
	key, input_key;

for (key in progressally_gutenberg.additional_input_type) {
	shortcode_attributes['param_' + key] = { type: 'string'};
}

registerBlockType( 'progressally-gutenberg/shortcode', {
	title: 'ProgressAlly',

	icon: el('svg', { width: 64, height: 64, viewBox: "0 0 64 64" },
		el('g', {
			transform: "translate(-451 -316)"
		},
			el('circle', {
				cx: 32,
				cy: 32,
				r: 32,
				transform: "translate(451 316)",
				fill: "#7f7f7f"
			}),
			el('line', {
				x2: 45,
				stroke: "#fff",
				strokeWidth: 1,
				transform: "translate(457 348)",
				fill: "none"
			}),
			el('circle', {
				cx: 10.5,
				cy: 10.5,
				r: 10.5,
				transform: "translate(491 338)",
				fill: "#fff"
			}),
			el('circle', {
				cx: 8,
				cy: 8,
				r: 8,
				transform: "translate(470 340)",
				fill: "#b3b3b3"
			}),
			el('circle', {
				cx: 5.5,
				cy: 5.5,
				r: 5.5,
				transform: "translate(454 343)",
				fill: "#999"
			}),
		),
	),

	category: 'layout',

	attributes: shortcode_attributes,

	edit: function( props ) {
		var InspectorControls = wp.editor.InspectorControls,
			Fragment = wp.element.Fragment,
			SelectControl = wp.components.SelectControl,
			TextControl = wp.components.TextControl,
			ServerSideRender = wp.components.ServerSideRender,
			BlockControls = wp.editor.BlockControls;
		function create_additional_input_element(key, props) {
			var selected_value = '', assignment = {};
			if ('param_' + key in props.attributes) {
				selected_value = props.attributes['param_' + key];
			}
			var config = progressally_gutenberg.additional_input_type[key];

			assignment['param_' + key] = '' + selected_value;	// the value must be a string
			props.setAttributes( assignment );
			var elem_param = {
						label: config['label'],
						value: selected_value,
						onChange: function(new_value) {
							var assignment = {};
							assignment['param_' + key] = '' + new_value;	// the value must be a string
							props.setAttributes( assignment );
						},
					}
			return el(
					TextControl,
					elem_param
					);
		}
		props.setAttributes( { current_post_id: wp.data.select("core/editor").getCurrentPostId() } );

		var selector_elements = [],
			selected_type = props.attributes.shortcode_type,
			additional_input, key;
		if (selected_type in progressally_gutenberg.type_mapping) {
			additional_input = progressally_gutenberg.type_mapping[selected_type];
			for (key in additional_input) { 
				selector_elements.push(create_additional_input_element(additional_input[key], props));
			}
		}
		return [
			el(ServerSideRender, {
				block: "progressally-gutenberg/shortcode",
				attributes:  props.attributes
			}),
			el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						SelectControl,
						{
							label: 'Select element type',
							options: progressally_gutenberg.shortcode_type_options,
							value: props.attributes.shortcode_type,
							onChange: function(new_type) {
								props.setAttributes( { shortcode_type: new_type } );
							}
						}
					),
					selector_elements
				),
				el(
					BlockControls,
					null
				),
			),
		 ];
	},

	save: function( props ) {
		return null;
	},
} );