# frozen_string_literal: true

def project_name
  "Folkingebrew 🍺"
end

## Serve
task :serve do
  puts "== Project: " + project_name
  puts "== Start kettle..."
  system "bundle exec middleman serve" || exit(1)
end

## Build the website
task :build do
  puts "== Project: " + project_name
  puts "== Brewing the website..."
  system "bundle exec middleman build" || exit(1)
end

# Test Suite
namespace :test do
  def test(test)
    puts "== Project: " + project_name
    puts "== Test: #{test}"
  end

  task :html do
    test :html
    system "bundle exec middleman build --verbose"
    system "ruby test.rb"
  end

  task :ruby do
    test :ruby
    system "rubocop"
  end
end
