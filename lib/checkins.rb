# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"
require_relative "colorizer"

# Get checkins
class Checkins
  checkins = nil
  retries = 0
  url = "https://untappd.com/Folkingebrew"

  begin
    puts "== Opening connection with #{url}...".green if retries.zero?
    doc = Nokogiri::HTML(open(url))
  rescue OpenURI::HTTPError => e
    raise e unless (retries += 1) <= 2

    puts "== Error (#{e}), retrying in 1 second... ".red
    sleep(1)
    retry
  else
    puts "== Successfully connected".green
    puts "== Fetching checkins..."
    checkins = doc.css("#main-stream .item")
  ensure
    if checkins
      puts "== Building checkins.yml..."

      File.open("data/checkins.yml", "w") do |f|
        checkins.each do |checkin|
          next unless checkin.css("p.photo img").attr("data-original")

          user = checkin.at(".text .user").text
          f.write("- user: \"#{user}\"\n")

          title = checkin.at(".text").text.strip.gsub(/\s+/, " ")
          f.write("\s\stitle: \"#{title}\"\n")

          checkin_image = checkin.css("p.photo img").attr("data-original")
          f.write("\s\simage: \"#{checkin_image}\"\n")

          date_time = checkin.at(".time").text
          date = Time.parse(date_time)
          f.write("\s\sdate: \"#{date.day}-#{date.month}-#{date.year}\"\n")
        end

        puts "== Checkins.yml built".green
      end
    end
  end
end
