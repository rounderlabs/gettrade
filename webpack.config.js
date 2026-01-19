const path = require('path');



module.exports = {
    output: {
        chunkFilename: 'js/[name].js?id=[chunkhash]',
    },
    module: {
        rules: [
            {
                test: /\.json$/,
                type: 'json'
            }
        ]
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '@assets': path.resolve('resources/assets')
        }
    },
    stats: {
        children: true, // 👈 show detailed warnings
    },
    ignoreWarnings: [
        {
            module: /@countrystatecity\/countries/,
            message: /Critical dependency: the request of a dependency is an expression/,
        },
    ],

};
