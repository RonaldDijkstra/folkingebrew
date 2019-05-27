# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"

url = "https://untappd.com/Folkingebrew"
html = open(url)
doc = Nokogiri::HTML(html)

puts doc
