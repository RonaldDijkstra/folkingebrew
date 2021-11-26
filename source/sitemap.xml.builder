# frozen_string_literal: true

xml.instruct!
xml.urlset 'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9' do
  sitemap.resources.select { |page| page.destination_path =~ /\.html/ && page.data.noindex != true }.each do |page|
    xml.url do
      xml.loc URI.join('https://www.folkingebrew.nl', page.url)
      last_mod = Time.now
      xml.lastmod last_mod.iso8601
    end
  end
end
