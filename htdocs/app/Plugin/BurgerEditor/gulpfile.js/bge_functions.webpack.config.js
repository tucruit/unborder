const webpack = require('webpack');
const pkg = require('../package.json');
const banner = `BurgerEditor v${pkg.version}`;

module.exports = {
	resolve: {
		extensions: ['.js', '.ts'],
	},
	output: {
		filename: 'bge_functions.js',
	},
	module: {
		rules: [
			{
				test: /\.ts$/,
				use: 'ts-loader',
				exclude: /node_modules/,
			},
		],
	},
	plugins: [
		new webpack.BannerPlugin(banner),
		new webpack.DefinePlugin({
			'process.env': { NODE_ENV: JSON.stringify('production') },
		}),
	],
	node: false,
};
