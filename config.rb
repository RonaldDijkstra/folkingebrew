# frozen_string_literal: true

# We are dutch but talk english
root_locale = :en

# Accessible as `root_locale` in helpers and `config[:root_locale]` in templates
set :root_locale, root_locale

# Activate i18n for root locale
activate :i18n, mount_at_root: root_locale, langs: %i[en]
activate :autoprefixer
activate :directory_indexes
activate :dotenv
activate :inline_svg
activate :sprockets

# Set timezone
Time.zone = "CET"

# Set Google Analytics id
set :ga_code, "UA-24956010-7"

# Ignore the selection file for Icomoon
ignore "assets/fonts/selection.json"

set :css_dir, "assets/stylesheets"
set :fonts_dir, "assets/fonts"
set :images_dir, "assets/images"
set :js_dir, "assets/javascripts"

# Use kramdown for markdown
# https://kramdown.gettalong.org/
set :markdown_engine, :kramdown
set :markdown, input: "GFM",
               auto_ids: true

page "/*.json", layout: false
page "/*.txt", layout: false
page "/*.xml", layout: false

# With layout
page "blog/index.html", layout: :blog_index
page "blog/*", layout: :blog_show
page "beers/index.html", layout: :beer_index
page "beers/*", layout: :beer_show
page "store/index.html", layout: :store_index
page "store/*", layout: :store_show

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

# Activate and setup the beer content type
activate :blog do |blog|
  blog.name = "beers"
  blog.prefix = "beers"
  blog.permalink = ":title"
  blog.sources = "/beers/{year}-{month}-{day}-{title}.html"
  # blog.tag_template = "blog/tag.html"
  blog.paginate = true
  blog.page_link = "page/{num}"
  blog.per_page = 12
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

# Settings for production
configure :production do
  activate :asset_hash, ignore: [
    %r{^assets/fonts/.*},
    "assets/images/logo-folkingebrew-black.svg"
  ]
  activate :gzip
  activate :minify_css
  activate :minify_html
  activate :minify_javascript

  # Raise exception for missing translations during build
  require "lib/test_exception_localization_handler"

  I18n.exception_handler = TestExceptionLocalizationHandler.new
end

ready do
  proxy "_redirects", "redirects", ignore: true
end
