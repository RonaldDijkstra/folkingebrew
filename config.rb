# frozen_string_literal: true

# Activate i18n for root locale
# activate :i18n, mount_at_root: root_locale, langs: %i[nl]
activate :autoprefixer
activate :directory_indexes
activate :inline_svg
activate :sprockets

# Set Google Analytics id
set :ga_code, "UA-24956010-7"

# Ignore the selection file for Icomoon
ignore "assets/fonts/selection.json"

set :css_dir, "assets/stylesheets"
set :fonts_dir, "assets/fonts"
set :images_dir, "assets/images"
set :js_dir, "assets/javascripts"
set :markdown,
  autolink: true,
  fenced_code_blocks: true,
  footnotes: true,
  highlight: true,
  smartypants: true,
  strikethrough: true,
  tables: true,
  with_toc_data: true
set :markdown_engine, :kramdown

page "/*.json", layout: false
page "/*.txt", layout: false
page "/*.xml", layout: false

page "nieuws/*", layout: :post_layout
# page "nieuws/feed.xml", layout: false
page "nieuws/index.html", layout: :blog_layout

# Activate and setup the blog content type
activate :blog do |blog|
  blog.name = "nieuws"
  blog.prefix = "nieuws"
  blog.permalink = ":title"
  blog.sources = "/{year}-{month}-{day}-{title}.html"
  # blog.tag_template = "blog/tag.html"
  blog.paginate = true
  blog.page_link = "{num}"
  blog.per_page = 10
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
