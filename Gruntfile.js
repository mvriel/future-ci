module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        banner: {
            css: '/*!\n' +
                ' * Future CI v<%= pkg.version %> (<%= pkg.homepage %>)\n' +
                ' * Copyright <%= grunt.template.today("yyyy") %> <%= pkg.author %>\n' +
                ' * Licensed under <%= _.pluck(pkg.licenses, "type") %> (<%= _.pluck(pkg.licenses, "url") %>)\n' +
                ' *\n' +
                ' * Bootstrap v3.1.1 (http://getbootstrap.com)\n' +
                ' * Copyright 2013 Twitter, Inc.\n' +
                ' * Licensed under http://www.apache.org/licenses/LICENSE-2.0\n' +
                ' */\n',
            js:  '/*!\n' +
                ' * Future CI v<%= pkg.version %> (<%= pkg.homepage %>)\n' +
                ' * Copyright <%= grunt.template.today("yyyy") %> <%= pkg.author %>\n' +
                ' * Licensed under <%= _.pluck(pkg.licenses, "type") %> (<%= _.pluck(pkg.licenses, "url") %>)\n' +
                ' */\n'
        },

        concat: {
            options: {
                banner: '<%= banner.js %>',
                stripBanners: false
            },
            f500ci: {
                src: [
                    'app/assets/js/app.js'
                ],
                dest: 'web/assets/js/<%= pkg.name %>.js'
            }
        },

        less: {
            f500ci: {
                options: {
                    strictMath: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapURL: '<%= pkg.name %>.css.map',
                    sourceMapFilename: 'web/assets/css/<%= pkg.name %>.css.map'
                },
                files: {
                    'web/assets/css/<%= pkg.name %>.css': 'app/assets/less/app.less'
                }
            },
            minify: {
                options: {
                    cleancss: true,
                    report: 'min'
                },
                files: {
                    'web/assets/css/<%= pkg.name %>.min.css': 'web/assets/css/<%= pkg.name %>.css'
                }
            }
        },

        uglify: {
            f500ci: {
                options: {
                    banner: '<%= banner.js %>\n',
                    report: 'min'
                },
                src: ['<%= concat.f500ci.dest %>'],
                dest: 'web/assets/js/<%= pkg.name %>.min.js'
            }
        },

        usebanner: {
            dist: {
                options: {
                    position: 'top',
                    banner: '<%= banner.css %>'
                },
                files: {
                    src: [
                        'web/assets/css/<%= pkg.name %>.css',
                        'web/assets/css/<%= pkg.name %>.min.css'
                    ]
                }
            }
        },

        watch: {
            css: {
                files: 'app/assets/less/*.less',
                tasks: ['dist-css']
            },
            js: {
                files: 'app/assets/js/*.js',
                tasks: ['dist-js']
            }
        }
    });

    // // These plugins provide necessary tasks.
    require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

    // JS distribution task.
    grunt.registerTask('dist-js', ['concat', 'uglify']);

    // CSS distribution task.
    grunt.registerTask('dist-css', ['less', 'usebanner']);

    // Full distribution task.
    grunt.registerTask('dist', ['dist-css', 'dist-js']);

    // Default task.
    grunt.registerTask('default', ['dist']);

};
