const plugin = require('tailwindcss/plugin');

// Function to multiply spacing values
const applyMultiplier = (values, multiplier) => {
	const newValues = {};
	Object.keys(values).forEach(key => {
	  const value = values[key];
	  // Apply the multiplier to each value and convert it to rem
	  newValues[key] = `${(parseFloat(value) * multiplier).toFixed(3)}rem`;
	});
	return newValues;
  };

  // https://www.modularscale.com/?14&px&1.125
	const baseSpacing = {
		0: '0rem',
		9: '.6rem',
		12: '.89rem',
		14: '1rem',
		16: '1.13rem',
		18: '1.27rem',
		20: '1.4rem',
		22: '1.6rem',
		25: '1.8rem',
		28: '2rem',
		32: '2.3rem',
		36: '2.57rem',
		40: '2.89rem',
		45: '3.25rem',
		51: '3.65rem',
		57: '4.1rem'
	};

  const fontSizeWithMultiplier = applyMultiplier(baseSpacing, .8);

module.exports = {
	input: './tailwind/input.css',
	output: './dist/tailwind.css',
	content: [
		// Root
		'./404.php',
		'./archive.php',
		'./author.php',
		'./footer.php',
		'./front-page.php',
		'./header.php',
		'./index.php',
		'./page.php',
		'./page-listing-form.php',
		'./page-listing-form-dev.php',
		'./page-password-reset.php',
		'./search.php',
		'./searchform.php',
		'./template-home.php',
		'./single.php',
		'./page-*',
		'./page-request-password-reset.php',
		// Lib
		'./lib/blocks/*.{php,js}',
		'./lib/inc/*.{php,js}',
		'./lib/js/*.{php,js}',
		'./lib/js/**/*.{php,js}',
		'./lib/plugins/*.{php,js}',
		'./lib/plugins/**/*.{php,js}',
		'./template-parts/*.{php,js}',
		'./template-parts/**/*.{php,js}',
		'./template-parts/listing-form/**/*.{php,js}',
		'./template-parts/content/*.{php,js}',
		'./template-parts/filters/*.{php,js}',
		'./template-parts/filters/**/*.{php,js}',
		'./template-parts/global/**/*.{php,js}',
		'./template-parts/global/calendar.php',
		'./template-parts/filters/elements/*.{php,js}',
		'./template-parts/global/listing-form/basic-info.php',
		'./template-parts/account/*.{php,js}',
		'./template-parts/listings/*.{php,js}',
		'./template-parts/search/*.{php,js}',
		'./template-parts/search/sort.php',
		'./template-parts/login/*.{php,js}',
		'./template-parts/login/forms/*.{php,js}',
		'./template-parts/html-api/*.{php,js}',
		// Other
		'./templates/**/*.{php,js}',
		'./tailwind/missing.php',
	],
	theme: {
		fontSize: fontSizeWithMultiplier,
		container: {
			center: true,
			padding: {
				DEFAULT: '1rem',
			},
		},
		colors: {
			// Standard
			white: '#FFFFFF',
			black: '#000000',
            grey: '#808080',
			// Browns
			'brown-dark-1': '#312922',
			'brown-dark-2': '#5B4A38',
			'brown-dark-3': '#846B4E',
			'brown-light-1': '#DDD7BC',
			'brown-light-3': '#F8F3DD',
			// Yellow
			'yellow': '#D29429',
			'yellow-light': '#EDD4A9',
			'yellow-60': '#E4BF7F',
			'yellow-50': '#F6E9D4',
			'yellow-20': '#F6EAD4',
			'yellow-10': '#FAF4EA',
			// Other
			'navy': '#0F384C',
			'red': '#C23D28',
			'red-60': '#DA8B7E'
		},
		fontFamily: {
			sans: ['Poppins', 'sans-serif'],
			'sun-motter': ['Sun Motter', 'sans-serif'],
		},
		extend: {
			boxShadow: {
				'black-offset': '2px 2px 0px 0px black',
			  },
			borderRadius: {
			},
			aspectRatio: {
				'4/3': '4 / 3',
			},
            minHeight: (theme) => ({
                ...theme('spacing'),
            }),
            minWidth: (theme) => ({
                ...theme('spacing'),
            }),
		},
	},
	plugins: [
		plugin(function({ addVariant }) {
		  addVariant('hocus', ['&:hover', '&:focus']),
		  addVariant("group-hocus", [".group:hover &", ".group:focus &"]),
		  addVariant("has-disabled", `&:has(input:is(:disabled),button:is(:disabled))`);
		}),
		function ({ addComponents }) {
			addComponents({
			  '.container': {
				maxWidth: '100%',
				marginLeft: 'auto',
				marginRight: 'auto',
				paddingLeft: '1rem',
				paddingRight: '1rem',
				'@screen sm': {
				  maxWidth: '100%',
				},
				'@screen md': {
				  maxWidth: '100%',
				},
				'@screen lg': {
				  maxWidth: '1020px',
				},
				'@screen xl': {
				  maxWidth: '1200px',
				},
			  }
			})
		  }
	  ],
}
