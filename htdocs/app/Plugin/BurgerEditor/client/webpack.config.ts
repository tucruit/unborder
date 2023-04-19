import webpack, { CliConfigOptions, Configuration } from 'webpack';
import path from 'path';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const packages = require('../package.json');

const config = (mode: CliConfigOptions['mode']): Configuration => ({
	mode,
	devtool: mode === 'development' ? 'inline-source-map' : false,
	resolve: {
		extensions: ['.tsx', '.ts', '.js'],
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
		new webpack.BannerPlugin({
			banner: `BurgerEditor v${packages.version}\n\nCopyright: ${packages.author}\nLicense: ${packages.license}\n`,
		}),
	],
});

export default function (_: any, argv: CliConfigOptions) {
	const mode = argv.mode;
	const conf = config(mode);

	return [
		{
			entry: path.resolve(__dirname, 'js/admin/index.ts'),
			output: {
				path: path.resolve(__dirname, '../webroot/js/admin/'),
				filename: 'burger_editor.js',
			},
		},
		{
			entry: path.resolve(__dirname, 'js/publish/bge_functions.ts'),
			output: {
				path: path.resolve(__dirname, '../webroot/js/bge_modules/'),
				filename: 'bge_functions.min.js',
			},
		},
	].map(({ entry, output }) => {
		return {
			...conf,
			entry,
			output,
		};
	});
}
