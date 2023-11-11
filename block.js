( function( blocks, React, blockEditor, i18n, components ) {
	var __ = i18n.__;
	var el = React.createElement,
		InspectorControls = blockEditor.InspectorControls,
		PanelBody = components.PanelBody,
		QueryControls = components.QueryControls,
		TextControl = components.TextControl,
		ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'plugins/list-last-changes', {
		title: __( 'List Last Changes' ),
		description: __( 'Shows a list of the last changes of a WordPress site.' ),
		icon: 'editor-ul',
		category: 'widgets',
		keywords: [ __( 'last changes' ) ],
		supports: {
			html: false,
		},

		attributes: {
			number: {
				type: 'number',
				default: 5,
			},
			showpages: {
				type: 'boolean',
				default: true,
			},
			showposts: {
				type: 'boolean',
				default: false,
			},
			showauthor: {
				type: 'boolean',
				default: false,
			},
			template: {
				type: 'string',
				default: "{title} {change_date}",
			},
		},

		edit: function( props ) {
			const { attributes, setAttributes } = props;
			const { number, showpages, showposts, showauthor, template } = attributes;

			if(showauthor === true && template === "{title} {change_date}") {
				props.setAttributes({showauthor: false, template: "{title} {change_date} {author}"});
			}

			const inspectorControls = el(
				InspectorControls,
				{ key: 'inspectorControls' },
				el(
					PanelBody,
					{ title: __( 'List Last Changes Settings' ) },
					el(
						QueryControls,
						{
							numberOfItems: number,
							onNumberOfItemsChange: ( value ) => props.setAttributes( { number: value } ),
						}
					),
					el(
						ToggleControl,
						{
							label: __('Show changed pages'),
							checked: showpages,
							onChange: function() {
								setAttributes( { showpages: !showpages } );
							},
						}
					),
					el(
						ToggleControl,
						{
							label: __('Show changed posts'),
							checked: showposts,
							onChange: function() {
								setAttributes( { showposts: !showposts } );
							},
						}
					),
					el(
						TextControl,
						{
							label: __('Template'),
							value: template,
							onChange: ( value ) => props.setAttributes( { template: value } ),
						}
					),
				),
			);

			return [
				inspectorControls,
				el(wp.serverSideRender, {
					block: "plugins/list-last-changes",
					attributes: props.attributes,
					key: 'serverSideRender'
				}),
			];
		},

		save() {
			return null;
		},
	} );
}(
	window.wp.blocks,
	window.React,
	window.wp.blockEditor,
	window.wp.i18n,
	window.wp.components,
) );
