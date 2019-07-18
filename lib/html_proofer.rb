# frozen_string_literal: true

# Require https://github.com/gjtorikian/html-proofer
require "html-proofer"

# Raise error if build directory does not exist
raise IOError, "Directory ./build does not exist." unless Dir.exist?("./build")

# Configuration
# All options: https://github.com/gjtorikian/html-proofer#configuration
options = {
  log_level: :debug,
  check_img_http: true,
  disable_external: true,
  allow_hash_href: true,
  check_html: true, validation: { report_missing_names: false },
  check_favicon: false,
  check_opengraph: true,
  http_status_ignore: [999, 403, 401],
  typhoeus: { timeout: 30 }
}

# Run html-proofer on build directory
HTMLProofer.check_directory("./build", options).run
