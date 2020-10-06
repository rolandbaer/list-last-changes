( function( blocks, editor, i18n, element, components ) {
	var __ = i18n.__;
	var el = element.createElement,
		InspectorControls = editor.InspectorControls,
		PanelBody = components.PanelBody,
		QueryControls = components.QueryControls,
		ToggleControl = components.ToggleControl,
		ServerSideRender = components.ServerSideRender;

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
		},

		edit: function( props ) {
			const { attributes, setAttributes } = props;
			const { number, showpages, showposts, showauthor, changedItems } = attributes;

			const inspectorControls = el( 
				InspectorControls, 
				null, 
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
						ToggleControl,
						{
							label: __('Show author'),
							checked: showauthor,
							onChange: function() {
								setAttributes( { showauthor: !showauthor } );
							},
						}
					),
				),
			);

			return [
				inspectorControls,
				el(ServerSideRender, {
					block: "plugins/list-last-changes",
					attributes: props.attributes,
				}),
			];
		},

		save() {
			return null;
		},
	} );
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
) );
