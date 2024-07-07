/**
 * WordPress dependencies
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, ToggleControl } from '@wordpress/components';
import { Fragment, useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
	import './editor.scss';

/**
 * Edit function
 */
const Edit = ( props ) => {

	const {
		attributes: { 
			city,
			toggleIcon,
			units
  		},
		setAttributes,
	} = props;

	const [weather, setWeather] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);

	const blockProps = useBlockProps({
		className: 'built-weather',
	});

     /**
     * Fetch weather data when the city changes.
     */
	 useEffect(() => {
        if (city) {
            fetchWeatherData();
        }
    }, [city, units]);

    /**
     * Fetch weather data from the API.
     */
    const fetchWeatherData = async () => {
        setIsLoading(true);
        setError(null);

        try {
            const data = await apiFetch({
				path: `/built-weather/v1/weather?city=${encodeURIComponent(city)}&units=${units}`,
            });
            setWeather(data);
        } catch (err) {
            setError(__('Error fetching weather data', 'built-weather'));
        } finally {
            setIsLoading(false);
        }
    };

	const getTempUnitSymbol = () => units === 'imperial' ? '°F' : '°C';

	function onChangeCity( newValue ) {
		setAttributes( { city: newValue } );
	}

	function onChangeToggleField( newValue ) {
		setAttributes( { toggleField: newValue } );
	}

	function onChangeSelectField( newValue ) {
		setAttributes( { selectField: newValue } );
	}

  	/**
	 * Return
	 */
    return (

		<Fragment>
			
			<InspectorControls>

				
				<PanelBody title={ __( 'City', 'built-weather' ) }>
					<TextControl
						label={ __( 'City', 'built-weather' ) }
						value={ city }
						onChange={ onChangeCity }
					/>
					<SelectControl
                        label={__('Units', 'built-weather')}
                        value={units}
                        options={[
                            { label: __('Metric (°C)', 'built-weather'), value: 'metric' },
                            { label: __('Imperial (°F)', 'built-weather'), value: 'imperial' },
                        ]}
                        onChange={(value) => setAttributes({ units: value })}
                    />
				</PanelBody>

				<PanelBody title={ __( 'Display', 'built-weather' ) } initialOpen={ false }>
					<SelectControl>
					</SelectControl>
					<ToggleControl>
					</ToggleControl>
				</PanelBody>


			</InspectorControls>

			<div { ...blockProps }>
				<h2>{city}</h2>
                    {isLoading && <p>{__('Loading...', 'built-weather')}</p>}
                    {error && <p className="error">{error}</p>}
                    {weather && (
                        <>
                            <img 
                                src={`http://openweathermap.org/img/w/${weather.icon}.png`} 
                                alt={weather.description} 
                            />
                            <p>{weather.temp}{getTempUnitSymbol()}</p>
                            <p>{weather.description}</p>
                        </>
                    )}
			</div>

		</Fragment>

    );

}

/**
 * Export
*/
export default Edit;