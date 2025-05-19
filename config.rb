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

activate :blog do |blog|
  blog.name = "blog"
  blog.prefix = "blog"
  blog.permalink = ":title"
  blog.sources = "/posts/{year}-{month}-{day}-{title}.html"
  blog.paginate = true
  blog.page_link = "{num}"
  blog.per_page = 10
  blog.summary_separator = /<\/p>/
end

activate :blog do |blog|
  blog.name = "beers"
  blog.prefix = "beers"
  blog.permalink = ":title"
  blog.sources = "/beers/{id}-{title}.html"
  blog.paginate = true
  blog.per_page = 24
  blog.page_link = "page/{num}"
  blog.filter = ->(article) { article.path.match(/\/(\d+)-/)[1].to_i }
end

activate :blog do |blog|
  blog.name = "webshop"
  blog.prefix = false
  blog.sources = "/webshop/products/{id}-{title}.html"
  blog.permalink = "/webshop/{title}"
  blog.paginate = false
end

# With no layout
page "/*.xml", layout: false
page "/*.json", layout: false
page "/*.txt", layout: false

# Layouts
page "blog/index.html", layout: :posts_layout
page "blog/*", layout: :post_layout
page "blog/feed.xml", layout: false

page "beers/index.html", layout: :beers_layout
page "beers/*", layout: :beer_layout

page "webshop/index.html", layout: :products_layout
page "webshop/*", layout: :product_layout
