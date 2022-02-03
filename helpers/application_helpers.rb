# frozen_string_literal: true

## Application Helpers
module ApplicationHelpers
  # Get the website name from site.yml
  def website_name
    data.site.name
  end

  # Get the base url from data
  def base_url
    data.site.base_url
  end

  # Permalink url for sharing
  def permalink_url
    full_url(current_page.url)
  end

  # Get the title from frontmatter if any
  def frontmatter_title
    current_page.data.title
  end

  # If there's a title in frontmatter then join them with the website_name
  def local_title(page = current_page)
    dont_append = page.data.dont_append_page_title

    if dont_append
      frontmatter_title
    elsif frontmatter_title
      [frontmatter_title, website_name].join(' | ')
    end
  end

  # Page title is localized or title
  def page_title
    local_title || website_name
  end

  # Use frontmatter for meta description
  def meta_description(page = current_page)
    if content_for?(:meta_description)
      yield_content(:meta_description)
    elsif page.data.description
      page.data.description
    else
      data.site.meta_description
    end
  end

  # Robots is current page data or default
  def robots
    current_page.data.robots || 'noydir,noodp,index,follow'
  end

  # Get full url
  def full_url(url)
    URI.join(base_url, url).to_s
  end

  # Get full locale (eg. nl_NL)
  def full_locale(lang = I18n.locale.to_s)
    case lang
    when 'en'
      'en_US'
    else
      "#{lang.downcase}_#{lang.upcase}"
    end
  end

  # 404?
  def x404?
    current_page.url == '/404.html'
  end

  def blog?(page = current_page)
    page.url.start_with?('/blog/')
  end

  def current_page_url
    if current_page.url.start_with?('/blog/')
      '/blog/'
    elsif current_page.url.start_with?('/beers/')
      '/beers/'
    elsif current_page.url.start_with?('/store/')
      '/store/'
    else
      current_page.url
    end
  end

  # Add aria current to current page navigation item
  def current_link_to(*arguments, aria_current: 'page', **options, &block)
    if block_given?
      text = capture(&block)
      path = arguments[0]
    else
      text = arguments[0]
      path = arguments[1]
    end

    options.merge!('aria-current' => aria_current) if path == current_page_url

    link_to(text, path, options)
  end

  def stocked_products
    products = []
    dato.products.select do |product|
      next unless product.in_stock

      products << product
    end
    products
  end

  def markdownify(text)
    Kramdown::Document.new(text, input: "GFM").to_html
  end
end
