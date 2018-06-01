# Serve
task :serve do
  puts "*"*50
  puts "Serving Folkingebrew"
  puts "*"*50
  system "bundle exec middleman"
end

# Build
task :build do
  puts "*"*50
  puts "Brewing Beer!"
  puts "*"*50
  system "bundle exec middleman build --clean" or exit(1)
end
