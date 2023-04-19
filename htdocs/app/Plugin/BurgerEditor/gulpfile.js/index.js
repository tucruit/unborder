const glob = require('glob');
const gulp = require('gulp');
const through = require('through2');
const Vinyl = require('vinyl');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const icon = require('gulp-iconfont');

require('./tasks/production');

gulp.task('css-public', () =>
	gulp
		.src(['stylesheet/sass/bge_style.scss', 'stylesheet/sass/bge_style_default.scss'])
		.pipe(
			through.obj((chunk, enc, cb) => {
				const f = new Vinyl(chunk);
				if (f.basename === 'bge_style_default.scss') {
					let scss = f.contents.toString();
					const blocks = glob.sync('./Addon/block/**/style.scss');
					const types = glob.sync('./Addon/type/**/style.scss');
					scss = scss.replace(
						/\/\*\s*@SCSS_FILES_BLOCK\s*\*\//,
						blocks.map(block => `@import "${block}";`).join('\n'),
					);
					scss = scss.replace(
						/\/\*\s*@SCSS_FILES_TYPE\s*\*\//,
						types.map(type => `@import "${type}";`).join('\n'),
					);
					f.contents = new Buffer(scss);
				}
				cb(null, f);
			}),
		)
		.pipe(sass({ outputStyle: 'expanded' }))
		.pipe(postcss())
		.pipe(gulp.dest('webroot/css/'))
		.pipe(gulp.dest('stylesheet/')),
);

gulp.task('css-editor', () =>
	gulp.src('client/css/burger_editor.scss').pipe(sass()).pipe(postcss()).pipe(gulp.dest('webroot/css/admin/')),
);

gulp.task('icon-editor', () =>
	gulp
		.src(['src/icons/*.svg'])
		.pipe(
			icon({
				fontName: 'icons',
				prependUnicode: true,
				formats: ['ttf', 'eot', 'woff'],
				timestamp: Math.round(Date.now() / 1000),
			}),
		)
		.pipe(gulp.dest('webroot/fonts/')),
);

gulp.task('watch', () => {
	gulp.watch('src/css/**/*.scss', ['css-editor']);
	gulp.watch('stylesheet/**/*.scss', ['css-public']);
});

gulp.task('default', gulp.parallel('css-editor', 'css-public'));
