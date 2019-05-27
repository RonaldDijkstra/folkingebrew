# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"

url = "https://untappd.com/Folkingebrew"
html = open(url)
doc = Nokogiri::HTML(html)

checkins = doc.css("#main-stream .item .photo img")

checkins[0..2].each do |checkin|
  puts checkin.attr('data-original')
end
