( function( blocks, editor, i18n, element, components ) {
	var __ = i18n.__;
	var el = element.createElement,
		InspectorControls = editor.InspectorControls,
		PanelBody = components.PanelBody,
		QueryControls = components.QueryControls,
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
		},
		/*getEditWrapperProps( attributes ) {
			const { align } = attributes;
			if ( [ 'left', 'center', 'right', 'wide', 'full' ].includes( align ) ) {
				return { 'data-align': align };
			}
		},*/
		edit: function( props ) {
			const { attributes, setAttributes } = props;
			const { number, showpages, showposts } = attributes;

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
				),
			);

			return [
				inspectorControls,
				el(
					"ul",
					null,
					el(
						"li",
						null,
						"Article 1"
					),
					el(
						"li",
						null,
						"Article 2"
					)
				)
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



