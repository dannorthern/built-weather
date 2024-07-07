/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import json from './block.json';

import edit from './edit';
import icon from './icon';
import save from './save';

import './editor.scss';
import './style.scss';

/**
 * Destructure JSON
 */
const { name } = json;

/**
 * Register block
 * */
registerBlockType( name, {
	edit,
	save,
	icon,
} );