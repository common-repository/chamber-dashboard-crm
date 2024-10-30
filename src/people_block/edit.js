import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import { SelectControl, 
    Toolbar,
    Button,
    Tooltip,
    PanelBody,
    PanelRow,
    FormToggle,
    ToggleControl,
    ToolbarGroup,
    Disabled, 
    RadioControl,
    RangeControl,
    FontSizePicker } from '@wordpress/components';

    import {
        RichText,
        AlignmentToolbar,
        BlockControls,
        BlockAlignmentToolbar,
        InspectorControls,
        InnerBlocks,
        withColors,
        PanelColorSettings,
        getColorClassName
    } from '@wordpress/block-editor';
import { withSelect, widthDispatch } from '@wordpress/data';

const {
    withState
} = wp.compose;

const formatOptions = [
    { label: 'List', value: 'list' },
    { label: '2 Columns', value: 'grid2' },
    { label: '3 Columns', value: 'grid3' },
    { label: '4 Columns', value: 'grid4' },
 ];

const orderbyOptions = [
    { label: 'Title', value: 'title' },
    { label: 'Date', value: 'date' },
    { label: 'Menu Order', value: 'menu_order' },
    { label: 'Random', value: 'rand' },
 ];

const orderOptions = [
    { label: 'Ascending', value: 'ASC' },
    { label: 'Descending', value: 'DESC' },
];

const categoryOptions = [
    { label: 'Select one or more categories', value: null }
];

wp.apiFetch({path: "/wp/v2/people_category?per_page=100"}).then(posts => {
    jQuery.each( posts, function( key, val ) {
        categoryOptions.push({label: val.name, value: val.slug});
    });
}).catch( 

)

const titleFontSizes = [
    {
        name: __( 'Small' ),
        slug: 'small',
        size: 12,
    },
    {
        name: __( 'Medium' ),
        slug: 'medium',
        size: 18,
    },
    {
        name: __( 'Big' ),
        slug: 'big',
        size: 26,
    },
];
const titleFallbackFontSize = 16;

const edit = props => {
    const {attributes: {align, textAlignment, cd_block, category, categoryArray, display, displayDescriptionToggle, displayImageToggle, displayPhoneToggle, displayEmailToggle, displayAddressToggle, displayTitleToggle, displayPrefixToggle, displaySuffixToggle, displayBusinessToggle, orderby, order, format, singleLinkToggle, single_link,}, className, setAttributes } = props;

    const setCategories = categoryArray => {
        props.setAttributes( { categoryArray} );
    };

    const setSingleLink = singleLinkToggle =>{
        props.setAttributes({singleLinkToggle})
        !! singleLinkToggle ? __( props.setAttributes({single_link: 'yes'}) ) : __( props.setAttributes({single_link: 'no'}) );
        
    };

    const inspectorControls = (
        <InspectorControls key="inspector">
            <PanelBody title={ __( 'Formatting Options' )}>
                <PanelRow>
                    <SelectControl
                        label="Format"
                        value={ format }
                        options= { formatOptions }
                        onChange={ ( value ) => setAttributes( { format: value} ) }
                    />
                </PanelRow>
                <PanelRow>
                    <SelectControl
                        label="Order By"
                        value={orderby}
                        options= { orderbyOptions }
                        onChange={ ( nextValue ) =>
                            setAttributes( {orderby:  nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <SelectControl
                        label="Order"
                        value={order}
                        options= { orderOptions }
                        onChange={ ( nextValue ) =>
                            setAttributes( {order:  nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <SelectControl 
                        multiple
                        className = "cdash_multi_select"
                        label = "Limit by Categories"
                        value = {categoryArray}
                        options = {categoryOptions}
                        onChange = {setCategories}
                    />
                </PanelRow>
            </PanelBody>
            <PanelBody title={ __( 'Display Options' )} initialOpen={ false }>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Description' ) }
                        checked={ displayDescriptionToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayDescriptionToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Image' ) }
                        checked={ displayImageToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayImageToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Phone' ) }
                        checked={ displayPhoneToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayPhoneToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Email' ) }
                        checked={ displayEmailToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayEmailToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Address' ) }
                        checked={ displayAddressToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayAddressToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Title' ) }
                        checked={ displayTitleToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayTitleToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Prefix' ) }
                        checked={ displayPrefixToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayPrefixToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Suffix' ) }
                        checked={ displaySuffixToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displaySuffixToggle: nextValue } )
                        }
                    />
                </PanelRow>
                <PanelRow>
                    <ToggleControl
                        label={ __( 'Display Business' ) }
                        checked={ displayBusinessToggle }
                        onChange={ ( nextValue ) =>
                            setAttributes( { displayBusinessToggle: nextValue } )
                        }
                    />
                </PanelRow>        
            </PanelBody>
        </InspectorControls>
    );
    const alignmentControls = (
        <BlockControls>
            <AlignmentToolbar
                value={textAlignment}
                onChange={(newalign) => setAttributes({ textAlignment: newalign })}
            />
        </BlockControls>
    );

    return [
        <div className={ props.className }>
            <ServerSideRender
                block="cdash-bd-blocks/cdcrm-people"
                attributes = {props.attributes}
            />
            { alignmentControls }
            { inspectorControls }
            <div className="cdcrm-people">
                
            </div>
        </div>
    ];
};

export default edit;