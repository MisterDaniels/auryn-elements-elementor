const wordpressPot = require('wp-pot');

wordpressPot({
    destFile: './languages/auryn-elements.pot',
    domain: 'auryn-elements',
    package: 'auryn-elements',
    src: './*.php'
});