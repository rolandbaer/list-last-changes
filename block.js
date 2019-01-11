( function( blocks, editor, i18n, element, components, data ) {
	var __ = i18n.__;
	var el = element.createElement,
		InspectorControls = editor.InspectorControls,
		PanelBody = components.PanelBody,
		QueryControls = components.QueryControls,
		ToggleControl = components.ToggleControl,
		Fragment = element.Fragment,
		Spinner = components.Spinner,
		Placeholder = components.Placeholder,
		withSelect = data.withSelect;

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
		edit: withSelect( function( select ) {
			return {
				changedItems: select( 'core' ).getEntityRecords( 'postType', 'post' )
			};
		} )(function( props ) {
			const { attributes, setAttributes } = props;
			const { number, showpages, showposts, changedItems } = attributes;

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

			const hasChangedItems = Array.isArray( changedItems ) && changedItems.length;
			if ( ! hasChangedItems ) {
				var innerPlaceholder = ! Array.isArray( changedItems ) ?
					el(Spinner) :
					__( 'No posts found.' );

				return el(
					Fragment,
					null,
					inspectorControls,
					el(
						Placeholder,
						{
							icon: "admin-post",
							label:  __( 'List Last Changes' ),
						},
						innerPlaceholder
					)
				);
			}
			
			// Removing items from display should be instant.
			const displayItems = changedItems.length > number ?
				changedItems.slice( 0, number ) :
				changedItems;

			return [
				inspectorControls,
				el(
					"ul",
					null,
					displayItems.map( ( item, i ) =>
						el(
							"li",
							{
								key: i
							},
							el(
								"a",
								{
									href: item.link,
									target: "_blank",
								}
								, decodeEntities( item.title.rendered.trim() ) || __( '(Untitled)' ) 
							),
							el(
								"span",
								{
									className: "wp-block-list-last-changes__item-date",
								}
								, dateI18n( dateFormat, item.date_gmt )
							)
						)
					)
				)
			];
		}),
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
	window.wp.data,
) );



