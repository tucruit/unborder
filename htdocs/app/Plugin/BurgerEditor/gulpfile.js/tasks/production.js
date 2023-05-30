const gulp = require('gulp');
const zip = require('gulp-zip');
const pkg = require('../../package.json');

const srcFull = [
	// 開発補助リソース
	'stylesheet/**/*',
	'.stylelintrc',
	'.eslintrc',
	'Addon/@types/BgE.d.ts',
	'Addon/jsconfig.json',

	// ブロック
	'Addon/block/category.php',
	'Addon/block/option.php',
	'Addon/block/button/**/*',
	'Addon/block/button2/**/*',
	'Addon/block/button3/**/*',
	'Addon/block/download-file/**/*',
	'Addon/block/download-file2/**/*',
	'Addon/block/download-file3/**/*',
	'Addon/block/embed/**/*',
	// 'Addon/block/gallery/**/*',
	'Addon/block/google-maps/**/*',
	'Addon/block/hr/**/*',
	'Addon/block/image-link-text2/**/*',
	'Addon/block/image-link-text3/**/*',
	'Addon/block/image-link-text4/**/*',
	'Addon/block/image-link-text5/**/*',
	'Addon/block/image-link1/**/*',
	'Addon/block/image-link2/**/*',
	'Addon/block/image-link3/**/*',
	'Addon/block/image-link4/**/*',
	'Addon/block/image-link5/**/*',
	'Addon/block/image-text2/**/*',
	'Addon/block/image-text3/**/*',
	'Addon/block/image-text4/**/*',
	'Addon/block/image-text5/**/*',
	'Addon/block/image1/**/*',
	'Addon/block/image2/**/*',
	'Addon/block/image3/**/*',
	'Addon/block/image4/**/*',
	'Addon/block/image5/**/*',
	'Addon/block/table/**/*',
	'Addon/block/text-float-image1/**/*',
	'Addon/block/text-float-image2/**/*',
	// 'Addon/block/text-gallery1/**/*',
	// 'Addon/block/text-gallery2/**/*',
	'Addon/block/text-image1/**/*',
	'Addon/block/text-image2/**/*',
	'Addon/block/title/**/*',
	'Addon/block/title2/**/*',
	'Addon/block/trimmed-image-link2/**/*',
	'Addon/block/trimmed-image-link3/**/*',
	'Addon/block/trimmed-image-link4/**/*',
	'Addon/block/trimmed-image-link5/**/*',
	'Addon/block/trimmed-image2/**/*',
	'Addon/block/trimmed-image3/**/*',
	'Addon/block/trimmed-image4/**/*',
	'Addon/block/trimmed-image5/**/*',
	'Addon/block/wysiwyg/**/*',
	'Addon/block/wysiwyg2/**/*',
	'Addon/block/youtube/**/*',

	// タイプ
	'Addon/type/button/**/*',
	'Addon/type/ckeditor/**/*',
	'Addon/type/download-file/**/*',
	'Addon/type/embed/**/*',
	// 'Addon/type/gallery/**/*',
	'Addon/type/google-maps/**/*',
	// 'Addon/type/google-street-view/**/*',
	'Addon/type/hr/**/*',
	'Addon/type/image/**/*',
	'Addon/type/image-link/**/*',
	'Addon/type/table/**/*',
	'Addon/type/title-h2/**/*',
	'Addon/type/title-h3/**/*',
	'Addon/type/trimmed-image/**/*',
	'Addon/type/trimmed-image-link/**/*',
	'Addon/type/youtube/**/*',

	// 設定ファイル
	'README.md',
	'LICENCE.txt',
	'VERSION.txt',
	'bgeconfig.json.sample',

	// プラグインソース
	'config.php',
	'Config/**/*',
	'Controller/**/*',
	'Event/**/*',
	'Lib/**/*',
	'Model/**/*',
	'Routing/**/*',
	'Vendor/**/*',
	'View/**/*',
	'webroot/**/*',

	// 不要ファイル
	'!webroot/css/admin/**/*.html',
];
const ignoreAddonsForStandard = [
	'!Addon/block/gallery/*',
	'!Addon/block/text-gallery1/*',
	'!Addon/block/text-gallery2/*',
	'!Addon/type/gallery/*',
];
const ignoreAddonsForTrial = [
	'!stylesheet/sass/*',
	'!.stylelintrc',
	'!.eslintrc',
	'!Addon/@types/BgE.d.ts',
	'!Addon/jsconfig.json',
	'!Addon/package.json',
	'!Addon/tsconfig.json',
	'!Addon/block/button2/*',
	'!Addon/block/button3/*',
	'!Addon/block/download-file2/*',
	'!Addon/block/download-file3/*',
	'!Addon/block/embed/*',
	'!Addon/block/image-link*',
	'!Addon/block/image-link*/*',
	'!Addon/block/trimmed*',
	'!Addon/block/trimmed*/*',
	'!Addon/block/*4',
	'!Addon/block/*4/*',
	'!Addon/block/*5',
	'!Addon/block/*5/*',
	'!Addon/type/embed',
	'!Addon/type/embed/*',
	'!Addon/type/trimmed*',
	'!Addon/type/trimmed*/*',
	'!Addon/type/image-link',
	'!Addon/type/image-link/*',
	'!bgeconfig.json.sample',
];
const ignoreAddonsForLE = [
	'!stylesheet/sass/*',
	'!.stylelintrc',
	'!.eslintrc',
	'!Addon/@types/BgE.d.ts',
	'!Addon/jsconfig.json',
	'!Addon/package.json',
	'!Addon/tsconfig.json',
	'!Addon/block/button2/**/*',
	'!Addon/block/button3/**/*',
	'!Addon/block/download-file2/**/*',
	'!Addon/block/download-file3/**/*',
	'!Addon/block/embed/**/*',
	'!Addon/block/image-link-text4/**/*',
	'!Addon/block/image-link-text5/**/*',
	'!Addon/block/image-text4/**/*',
	'!Addon/block/image-text5/**/*',
	'!Addon/block/image4/**/*',
	'!Addon/block/image5/**/*',
	'!Addon/block/table/**/*',
	'!Addon/block/trimmed-image-link2/**/*',
	'!Addon/block/trimmed-image-link3/**/*',
	'!Addon/block/trimmed-image-link4/**/*',
	'!Addon/block/trimmed-image-link5/**/*',
	'!Addon/block/trimmed-image2/**/*',
	'!Addon/block/trimmed-image3/**/*',
	'!Addon/block/trimmed-image4/**/*',
	'!Addon/block/trimmed-image5/**/*',
	'!bgeconfig.json.sample',
];

gulp.task('v2-normal', done => {
	gulp.src(srcFull, {
		base: './',
		allowEmpty: true,
	})
		.pipe(gulp.dest('production/v2n/BurgerEditor'))
		.on('end', () => {
			gulp.src('production/v2n/BurgerEditor/**/*', {
				base: 'production/v2n',
				allowEmpty: true,
			})
				.pipe(zip(`BurgerEditor-v${pkg.version}.zip`))
				.pipe(gulp.dest('production/'))
				.on('end', () => done());
		});
});

gulp.task('v2-le', done => {
	gulp.src(srcFull.concat(ignoreAddonsForLE).concat(ignoreAddonsForTrial), {
		base: './',
		allowEmpty: true,
	})
		.pipe(gulp.dest('production/v2le/BurgerEditor'))
		.on('end', () => {
			gulp.src('production/v2le/BurgerEditor/**/*', {
				base: 'production/v2le',
				allowEmpty: true,
			})
				.pipe(zip(`BurgerEditor-LE-v${pkg.version}.zip`))
				.pipe(gulp.dest('production/'))
				.on('end', () => done());
		});
});

gulp.task('v3-pro', done => {
	gulp.src(srcFull, {
		base: './',
		allowEmpty: true,
	})
		.pipe(gulp.dest('production/pro/BurgerEditor'))
		.on('end', () => {
			gulp.src('production/pro/BurgerEditor/**/*', {
				base: 'production/pro',
				allowEmpty: true,
			})
				.pipe(zip(`BurgerEditor-Pro-v${pkg.version}.zip`))
				.pipe(gulp.dest('production/'))
				.on('end', () => done());
		});
});

gulp.task('v3-normal', done => {
	gulp.src(srcFull.concat(ignoreAddonsForStandard), {
		base: './',
		allowEmpty: true,
	})
		.pipe(gulp.dest('production/standard/BurgerEditor'))
		.on('end', () => {
			gulp.src('production/standard/BurgerEditor/**/*', {
				base: 'production/standard',
				allowEmpty: true,
			})
				.pipe(zip(`BurgerEditor-Standard-v${pkg.version}.zip`))
				.pipe(gulp.dest('production/'))
				.on('end', () => done());
		});
});

gulp.task('v3-trial', done => {
	gulp.src(srcFull.concat(ignoreAddonsForStandard).concat(ignoreAddonsForTrial), {
		base: './',
		allowEmpty: true,
	})
		.pipe(gulp.dest('production/trial/BurgerEditor'))
		.on('end', () => {
			gulp.src('production/trial/BurgerEditor/**/*', {
				base: 'production/trial',
				allowEmpty: true,
			})
				.pipe(zip(`BurgerEditor-Trial-v${pkg.version}.zip`))
				.pipe(gulp.dest('production/'))
				.on('end', () => done());
		});
});

gulp.task('production', gulp.parallel('v2-le', 'v2-normal', 'v3-pro', 'v3-normal', 'v3-trial'));
