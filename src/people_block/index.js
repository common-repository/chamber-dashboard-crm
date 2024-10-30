/**
 * Block dependencies
 */

 import edit from './edit';

 import { registerBlockType } from '@wordpress/blocks';
 import { __ } from '@wordpress/i18n';
 import { dateI18n, format, __experimentalGetSettings } from '@wordpress/date';
 import { setState } from '@wordpress/compose';

 registerBlockType( 'cdash-bd-blocks/cdcrm-people', {
    title: 'Display People',
    icon: 'admin-users',
    category: 'cd-blocks',
    description: 'The display people block displays the People records in the CD CRM plugin.',
    example: {
    },
    supports: {
        // Declare support for block's alignment.
        // This adds support for all the options:
        // left, center, right, wide, and full.
        align: [ 'wide', 'full' ]
    },
    attributes: {
        align: {
            type: 'string',
            default: ''
        },
        textAlignment: {
			type: 'string',
            default: 'left',
		},
        cd_block:{
            type: 'string',
            default: 'yes',
        },
        category: {
            type: 'string',
            default: '',
        },
        categoryArray: {
            type: 'array',
            default: [],
        },
        display: {
            type: 'string',
            default: '',
        },
        displayDescriptionToggle:{
            type: 'boolean',
            default: true,
        },
        displayImageToggle:{
            type: 'boolean',
            default: true,
        },
        displayPhoneToggle:{
            type: 'boolean',
            default: false,
        },
        displayEmailToggle:{
            type: 'boolean',
            default: false,
        },
        displayAddressToggle: {
            type: 'boolean',
            default: false,   
        },
        displayTitleToggle: {
            type: 'boolean',
            default: false,
        },
        displayPrefixToggle: {
            type: 'boolean',
            default: false,
        },
        displaySuffixToggle: {
            type: 'boolean',
            default: false,
        },
        displayBusinessToggle: {
            type: 'boolean',
            default: false,
        },
        orderby: {
            type: 'string',
            default: 'rand',
        },
        order: {
            type: 'string',
            default: 'ASC',
        },
        format: {
            type: 'string',
            default: 'list',
        },
        singleLinkToggle: {
            type: 'boolean',
            default: false,
        },
        single_link: {
            type: 'string',
            default: 'no',
        },
    },
    edit: edit,
    save() {
        // Rendering in PHP
        return null;
    },
} );