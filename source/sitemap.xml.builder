# frozen_string_literal: true

xml.instruct!
xml.urlset "xmlns" => "http://www.sitemaps.org/schemas/sitemap/0.9" do
  def sitemap_pages
    sitemap.resources.select do |page|
      page.destination_path =~ /\.html/ &&
        !(page.data.robots && page.data.robots.include?("noindex"))
    end
  end

  sitemap_pages.each do |page|
    xml.url do
      xml.loc "https://www.folkingebrew.nl#{page.url}"
      xml.lastmod Date.today.to_time.iso8601
    end
  end
end
