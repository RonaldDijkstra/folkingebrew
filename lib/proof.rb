# frozen_string_literal: true

require "html-proofer"

HTMLProofer.check_directory("./build").run
