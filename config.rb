# frozen_string_literal: true

# Activate i18n for root locale
# activate :i18n, mount_at_root: root_locale, langs: %i[nl]
activate :autoprefixer
activate :directory_indexes
activate :inline_svg
activate :sprockets

ENV["SEGMENT_KEY"] = "F7nm44dcOKITesjMED0uy3jShu9XrWE"

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
set :markdown_engine, :redcarpet

page "/*.json", layout: false
page "/*.txt", layout: false
page "/*.xml", layout: false

# Settings for production
configure :production do
  activate :asset_hash, ignore: [
    %r{^assets/fonts/.*}
  ]
  activate :gzip
  activate :minify_css
  activate :minify_html
  activate :minify_javascript

  # Raise exception for missing translations during build
  require "lib/test_exception_localization_handler"

  I18n.exception_handler = TestExceptionLocalizationHandler.new
end
