exports.config =
  files:
    stylesheets:
      joinTo: 'css/app.css'
      order:
        before: 'app/styles/reset.styl'

    javascripts:
      joinTo: 'js/app.js'

  paths:
     public: '../web'

  plugins:
    babel:
      presets: ['latest', 'react']
      plugins: [
        'transform-class-properties'
        'transform-object-rest-spread'
      ]

    postcss:
      processors: [
        require('autoprefixer')
      ]
