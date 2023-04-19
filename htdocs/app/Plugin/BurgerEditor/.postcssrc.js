module.exports = {
	plugins: {
		'postcss-import': true,
		'postcss-gap-properties': true,
		'postcss-custom-media': true,
		'postcss-math': true,
		'postcss-calc': true,
		'postcss-color-function': true,
		'postcss-clip-path-polyfill': true,
		autoprefixer: {
			grid: true,
		},
		'postcss-object-fit-images': true,
		'postcss-base64': {
			pattern: /<svg.*<\/svg>/i,
			prepend: 'data:image/svg+xml;base64,',
		},
		cssnano: true,
	},
};
