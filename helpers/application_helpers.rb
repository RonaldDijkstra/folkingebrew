module ApplicationHelpers
  def markdown(contents)
    renderer = Redcarpet::Render::HTML
    markdown = Redcarpet::Markdown.new(
      renderer,
      autolink: true,
      fenced_code_blocks: true,
      footnotes: true,
      highlight: true,
      smartypants: true,
      strikethrough: true,
      tables: true,
      with_toc_data: true
    )
    markdown.render(contents)
  end

  # Get the website name from site.yml
  def website_name
    data.site.name
  end

  # Get the base url from data
  def base_url
    data.site.base_url
  end

  # Get the title from frontmatter if any
  def frontmatter_title
    current_page.data.title
  end

  # If there's a title in frontmatter check if it's localized
  # and then join them with the website_name
  def local_title
    [frontmatter_title, website_name].join(" - ") if frontmatter_title
  end

  # Page title is localized or title
  def page_title
    local_title || website_name
  end

  # Meta description is localized_description or description
  def meta_description
    current_page.data.description
  end

  # Robots is current page data or default
  def robots
    current_page.data.robots || "noydir,noodp,index,follow"
  end

  # Get full url
  def full_url(url)
    URI.join(base_url, url).to_s
  end

  # Define image for Open Graph
  def og_image
    full_url(asset_url(current_page.data.image || "assets/images/folkingebrew-1200x630.jpg"))
  end

  # Get full locale (eg. nl_NL)
  def full_locale(lang = I18n.locale.to_s)
    case lang
    when "en"
      "en_US"
    else
      "#{lang.downcase}_#{lang.upcase}"
    end
  end

  # 404?
  def x404?
    current_page.url == "/404.html"
  end

  # Add aria current to current page navigation item
  def current_link_to(*arguments, aria_current: "page", **options, &block)
    if block_given?
      text = capture(&block)
      path = arguments[0]
    else
      text = arguments[0]
      path = arguments[1]
    end

    options.merge!("aria-current" => aria_current) if path == current_page.path

    link_to(text, path, options)
  end
end
