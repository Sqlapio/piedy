import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [

        require('./vendor/wireui/wireui/tailwind.config.js')

    ],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php',
        './node_modules/flowbite/**/*.js',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
        './vendor/wire-elements/modal/src/*.blade.php',
    ],
    plugins: [
        require('flowbite/plugin')
    ],
}
