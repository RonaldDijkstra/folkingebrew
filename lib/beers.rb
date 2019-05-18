# frozen_string_literal: true

require "net/http"
require "uri"
require "yaml"
require "json"
require "fileutils"

uri = URI.parse("https://business.untappd.com/api/v1/sections/393867/items")
request = Net::HTTP::Get.new(uri)
request["Authorization"] = "Basic anVzdF9pbl9iZWVyQGhvdG1haWwuY29tOlBIeEFUdHJZejZReGh5cjRCc1Zm"

req_options = {
  use_ssl: uri.scheme == "https"
}

response = Net::HTTP.start(uri.hostname, uri.port, req_options) do |http|
  http.request(request)
end

if response.code == "200"

  File.delete("data/beers.yml") if File.exist?("data/beer.yml")

  result = JSON.parse(response.body, object_class: OpenStruct)

  File.open("data/beers.yml", "w") do |f|
    result.items.each do |doc|
      f.write("- item: \"#{doc.name}\"\n")
      f.write("\s\sdate: #{doc.updated_at}\n")
      f.write("\s\sstyle: \"#{doc.style}\"\n")
      f.write("\s\sbrewery: \"#{doc.brewery}\"\n")
      f.write("\s\simage: \"#{doc.label_image_hd}\"\n")
      f.write("\s\srating: \"#{doc.rating.to_f.round(2)}\"\n")
      f.write("\s\sdescription: \"#{doc.description.gsub(/\n/, " ").gsub(/"/, " ")}\"\n")
      f.write("\s\suntappd_url: \"https://untappd.com/b/#{doc.untappd_beer_slug}/#{doc.untappd_id}\"\n")
      f.write("\s\sabv: \"#{doc.abv}\"\n")
      f.write("\s\sibu: \"#{doc.ibu.to_f.round(0)}\"\n")
    end
    f.close
  end
else
  puts "Error retrieving data"
end
