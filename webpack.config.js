module.exports = [

    {
        entry: {
            /*views*/
            'datacollectief-datacollectief-index': './app/views/admin/index.js',
            'datacollectief-datacollectief-salesviewer': './app/views/admin/salesviewer.js',
            'datacollectief-settings': './app/views/admin/settings.js',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        externals: {
            'lodash': '_',
            'jquery': 'jQuery',
            'uikit': 'UIkit',
            'vue': 'Vue',
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: 'vue',},
                {test: /\.html$/, loader: 'vue-html',},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015',],},},
            ],
        },

    },

];
