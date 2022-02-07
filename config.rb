# frozen_string_literal: true

# General config
# http://localhost:4567/__middleman

# Set root_locale
root_locale = :en

# Accessible as `root_locale` in helpers and `config[:root_locale]` in templates
set :root_locale, root_locale

# Set timezone
# Time.zone = "CET"

# Activate i18n for root locale
activate :i18n, mount_at_root: root_locale, langs: %i[en]

# Env var for production
production = ENV['PRODUCTION'] == 'true'
set :production, production

# Load Sass from node_modules
config[:sass_assets_paths] << File.join(root, 'node_modules')

# Set assets directories
set :css_dir,    'assets/stylesheets'
set :fonts_dir,  'assets/fonts'
set :images_dir, 'assets/images'
set :js_dir,     'assets/javascripts'

# Handled by Webpack
ignore File.join(config[:js_dir], '*')
ignore File.join(config[:css_dir], '*')
ignore File.join(config[:fonts_dir], '*')

# Activate and configure extensions
# https://middlemanapp.com/advanced/configuration/#configuring-extensions

# Webpack
activate :external_pipeline,
         name: :webpack,
         command: build? ? 'yarn run build' : 'yarn run start',
         source: 'dist',
         latency: 1

activate :dotenv
activate :directory_indexes
activate :inline_svg

# Activate DatoCMS
if ENV['DATO_PREVIEW']
  activate :dato, preview: true, live_reload: true
else
  activate :dato, preview: false, live_reload: false
end

# Activate Pagination
activate :pagination

# Use kramdown for markdown
set :markdown_engine, :kramdown

# Layouts
# https://middlemanapp.com/basics/layouts

page '/*.xml',  layout: false
page '/*.json', layout: false
page '/*.txt',  layout: false

ignore   File.join(config[:js_dir], '*')
ignore   File.join(config[:css_dir], '*')

# Load and activate all components
Dir['./components/**/*.rb'].each { |file| load file }
Pathname.new('./components').children.each do |entry|
  next unless entry.directory?

  activate "#{entry.basename}_component".to_sym
end

# Development-specific configuration
configure :development do
  set      :debug_assets, true
  activate :livereload
  activate :pry
end

# Build-specific configuration
# https://middlemanapp.com/advanced/configuration/#environment-specific-settings

configure :build do
  set      :relative_links, true
  activate :asset_hash, ignore: [
    'assets/images/logo-folkingebrew-black.svg'
  ]
  activate :gzip
  activate :minify_html, remove_input_attributes: false

  # Raise exception for missing translations during build
  require 'lib/test_exception_localization_handler'

  I18n.exception_handler = TestExceptionLocalizationHandler.new
end

ready do
  proxy '_headers', 'headers', ignore: true
  proxy '_redirects', 'redirects', ignore: true
end

dato.tap do |dato|
  paginate dato.beers, '/beers', '/templates/beers.html', per_page: 12
  paginate dato.products, '/store', '/templates/store.html'
  paginate dato.posts, '/blog', '/templates/blog.html'

  dato.beers.each do |beer|
    proxy "/beers/#{beer.slug}/index.html",
          '/templates/beer.html',
          locals: { beer: beer },
          ignore: true
  end

  dato.products.each do |product|
    proxy "/store/#{product.slug}/index.html",
          '/templates/product.html',
          locals: { product: product },
          ignore: true
  end

  dato.posts.each do |post|
    proxy "/blog/#{post.slug}/index.html",
          '/templates/article.html',
          locals: { post: post },
          ignore: true
  end
end

ignore '/templates/beers.html.erb'
ignore '/templates/store.html.erb'
ignore '/templates/blog.html.erb'
