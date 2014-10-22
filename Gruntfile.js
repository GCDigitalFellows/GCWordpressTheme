module.exports = function(grunt) {

	// To support SASS/SCSS or Stylus, just install
	// the appropriate grunt package and it will be automatically included
	// in the build process, Sass is included by default:
	//
	// * for SASS/SCSS support, run `npm install --save-dev grunt-contrib-sass`
	// * for Stylus/Nib support, `npm install --save-dev grunt-contrib-stylus`

	var npmDependencies = require('./package.json').devDependencies;
	var hasLess = npmDependencies['grunt-contrib-less'] !== undefined;
	var hasStylus = npmDependencies['grunt-contrib-stylus'] !== undefined;
	var hasAutoprefixer = npmDependencies['grunt-autoprefixer'] !== undefined;
	

	grunt.initConfig({

		// Watches for changes and runs tasks
		watch : {
			less : {
				files : ['library/theme/less/**/*.less'],
				tasks : (hasLess) ? ['less:dev','autoprefixer'] : null,
				options : {
					livereload : true
				}
			},
			stylus : {
				files : ['stylus/**/*.styl'],
				tasks : (hasStylus) ? ['stylus:dev'] : null,
				options: {
					livereload : true
				}
			},
			js : {
				files : ['library/js/**/*.js'],
				tasks : ['jshint'],
				options : {
					livereload : true
				}
			},
			php : {
				files : ['**/*.php'],
				options : {
					livereload : true
				}
			}
		},

		// JsHint your javascript
		jshint : {
			all : ['library/js/*.js', '!library/js/modernizr.js', '!library/js/*.min.js', '!js/vendor/**/*.js'],
			options : {
				browser: true,
				curly: false,
				eqeqeq: false,
				eqnull: true,
				expr: true,
				immed: true,
				newcap: true,
				noarg: true,
				smarttabs: true,
				sub: true,
				undef: false
			}
		},

		// Dev and production build for less
		less : {
			options: {
				paths: ['./bower_components'],
			},
			production : {
				options: {
					cleancss: true,
					report: 'gzip'
				},
				files : [{
					expand: true,
					cwd: 'library/theme',
					src: 'less/**/*.less',
					dest: 'css',
					ext : '.css',
				}],
			},
			dev : {
				options: {
		          sourceMap: true,
		          //sourceMapBasepath: '<%= config.app %>/',
		          //sourceMapRootpath: '../'
		        },
				files : [{
					expand: true,
					cwd: 'library/theme',
					src: 'less/**/*.less',
					dest: 'css',
					ext: '.css'
				}],
			}
		},

		// Dev and production build for stylus
		stylus : {
			production : {
				files : [
					{
						src : ['**/*.styl', '!**/_*.styl'],
						cwd : 'stylus',
						dest : 'css',
						ext: '.css',
						expand : true
					}
				],
				options : {
					compress : true
				}
			},
			dev : {
				files : [
					{
						src : ['**/*.styl', '!**/_*.styl'],
						cwd : 'stylus',
						dest : 'css',
						ext: '.css',
						expand : true
					}
				],
				options : {
					compress : false
				}
			},
		},

		// Add vendor prefixed styles
	    autoprefixer: {
	      options: {
	        browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1']
	      },
	      dist: {
	        files: [{
	          expand: true,
	          cwd: 'library/theme/css',
	          src: '{,*/}*.css',
	          dest: 'library/theme/css'
	        }]
	      }
	    },

		// Bower task sets up require config
		bower : {
			all : {
				rjsConfig : 'library/js/global.js'
			}
		},

		// Require config
		requirejs : {
			production : {
				options : {
					name : 'global',
					baseUrl : 'library/js',
					mainConfigFile : 'library/js/global.js',
					out : 'library/js/optimized.min.js'
				}
			}
		},

		// Image min
		imagemin : {
			production : {
				files : [
					{
						expand: true,
						cwd: 'library',
						src: '**/*.{png,jpg,jpeg}',
						dest: 'library'
					}
				]
			}
		},

		// SVG min
		svgmin: {
			production : {
				files: [
					{
						expand: true,
						cwd: 'library',
						src: '**/*.svg',
						dest: 'library'
					}
				]
			}
		}

	});

	// Default task
	grunt.registerTask('default', ['watch']);

	// Build task
	grunt.registerTask('build', function() {
		var arr = ['jshint'];

		if (hasLess) {
			arr.push('less:production');
		}

		if (hasStylus) {
			arr.push('stylus:production');
		}

		if (hasAutoprefixer) {
			arr.push('autoprefixer');
		}

		arr.push('imagemin:production', 'svgmin:production', 'requirejs:production');

		return arr;
	});

	// Template Setup Task
	grunt.registerTask('setup', function() {
		var arr = [];

		if (hasLess) {
			arr.push('less:dev');
		}

		if (hasStylus) {
			arr.push('stylus:dev');
		}

		if (hasAutoprefixer) {
			arr.push('autoprefixer');
		}

		arr.push('bower-install');
	});

	// Load up tasks
	if (hasLess) {
		grunt.loadNpmTasks('grunt-contrib-less');
	}

	if (hasStylus) {
		grunt.loadNpmTasks('grunt-contrib-stylus');
	}

	if (hasAutoprefixer) {
		grunt.loadNpmTasks('grunt-autoprefixer');
	}
	
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-bower-requirejs');
	grunt.loadNpmTasks('grunt-contrib-requirejs');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-svgmin');

	// Run bower install
	grunt.registerTask('bower-install', function() {
		var done = this.async();
		var bower = require('bower').commands;
		bower.install().on('end', function(data) {
			done();
		}).on('data', function(data) {
			console.log(data);
		}).on('error', function(err) {
			console.error(err);
			done();
		});
	});

};
