module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            development: {
//                cwd: 'Resources/public/less/',
//                dest: 'Resources/public/css/',
                files: {
                    "Resources/public/css/styles.css": "Resources/public/less/styles.less"
                }
            },
            production: {
                options: {
                    cleancss: true
                },
                files: {
                    "Resources/public/css/styles.min.css": "Resources/public/less/styles.less"
                }
            }
        },
        watch: {
            styles: {
                files: ['Resources/public/less/*.less'], // which files to watch
                tasks: ['less'],
                options: {
                    nospawn: true
                }
            }
        }
    /*
        cssmin: {
            minify: {
                expand: true,
                cwd: 'Resources/public/css/',
                src: ['*.css', '!*.min.css'],
                dest: 'Resources/public/css/',
                ext: '.min.css'
            }
        }*/
    });

    // Load the plugin
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['watch']);
};
