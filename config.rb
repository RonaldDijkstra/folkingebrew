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
production = ENV["PRODUCTION"] == "true"
set :production, production

# Load Sass from node_modules
config[:sass_assets_paths] << File.join(root, "node_modules")

# Set assets directories
set :css_dir,    "assets/stylesheets"
set :fonts_dir,  "assets/fonts"
set :images_dir, "assets/images"
set :js_dir,     "assets/javascripts"

# Handled by Webpack
ignore File.join(config[:js_dir], '*')
ignore File.join(config[:css_dir], '*')

# Activate and configure extensions
# https://middlemanapp.com/advanced/configuration/#configuring-extensions

activate :autoprefixer do |config|
  config.browsers = "last 2 versions"
end

# Webpack
activate :external_pipeline,
         name: :webpack,
         command: build? ? 'yarn run build' : 'yarn run start',
         source: "dist",
         latency: 1

activate :dotenv
activate :directory_indexes
activate :inline_svg

# Activate DatoCMS
activate :dato, preview: true, live_reload: true

# Activate Pagination
activate :pagination

# Use Webshop?
set :use_webshop?, true

# Set Google Analytics id
set :ga_code, "UA-24956010-7"

# Ignore the selection file for Icomoon
ignore "assets/fonts/selection.json"

# Use redcarpet for markdown
set :markdown_engine, :redcarpet

# Layouts
# https://middlemanapp.com/basics/layouts

page "/*.xml",  layout: false
page "/*.json", layout: false
page "/*.txt",  layout: false

# With layout
page "blog/index.html", layout: :blog_index
page "blog/*", layout: :blog_show
# page "beers/index.html", layout: :beer_index
# page "beers/*", layout: :beer_show
page "store/index.html", layout: :store_index
page "store/*", layout: :store_product_detail

# Activate and setup the blog content type
activate :blog do |blog|
  blog.name = "blog"
  blog.prefix = "blog"
  blog.permalink = ":title"
  blog.sources = "/posts/{year}-{month}-{day}-{title}.html"
  # blog.tag_template = "blog/tag.html"
  blog.paginate = true
  blog.page_link = "{num}"
  blog.per_page = 10
end

# Activate and setup the product content type
activate :blog do |blog|
  blog.name = "store"
  blog.prefix = "store"
  blog.permalink = ":title"
  blog.sources = "/products/{title}.html"
  # blog.tag_template = "blog/tag.html"
  blog.paginate = true
  blog.page_link = "/page/{num}"
  blog.per_page = 12
end

# With alternative layout
# page "/path/to/file.html", layout: "other_layout"

# Proxy pages
# https://middlemanapp.com/advanced/dynamic-pages

# proxy(
#   "/this-page-has-no-template.html",
#   "/template-file.html",
#   locals: {
#     which_fake_page: "Rendering a fake page with a local variable"
#   }
# )

# Helpers
# Methods defined in the helpers block are available in templates
# https://middlemanapp.com/basics/helper-methods

# helpers do
#   def some_helper
#     "Helping"
#   end
# end

ignore   File.join(config[:js_dir], '*')
ignore   File.join(config[:css_dir], '*')

# Build-specific configuration
# https://middlemanapp.com/advanced/configuration/#environment-specific-settings

configure :development do
  set      :debug_assets, true
  activate :livereload
  activate :pry
end

configure :build do
  ignore   File.join(config[:js_dir], "*") # handled by webpack
  ignore   File.join(config[:css_dir], "*") # handled by webpack
  set      :asset_host, @app.data.site.base_url
  set      :relative_links, true
  activate :asset_hash, ignore: [
    %r{^assets/fonts/.*},
    "assets/images/logo-folkingebrew-black.svg"
  ]
  activate :gzip
  activate :minify_css
  activate :minify_html
  activate :minify_javascript
  activate :relative_assets

  # Raise exception for missing translations during build
  require "lib/test_exception_localization_handler"

  I18n.exception_handler = TestExceptionLocalizationHandler.new
end

ready do
  proxy "_headers", "headers", ignore: true
  proxy "_redirects", "redirects", ignore: true
end

dato.tap do |dato|
  paginate dato.beers, "/beers", "/templates/beers.html", per_page: 2
end 

ignore "/templates/beers.html.erb"
